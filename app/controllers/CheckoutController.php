<?php
// app/controllers/CheckoutController.php
// Implementasi TRANSACTION (START TRANSACTION, COMMIT, ROLLBACK)
class CheckoutController extends Controller
{
    private Cart $cartModel;
    private Order $orderModel;
    private Payment $paymentModel;
    private Product $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->cartModel    = new Cart();
        $this->orderModel   = new Order();
        $this->paymentModel = new Payment();
        $this->productModel = new Product();
    }

    public function index(): void
    {
        Session::requireCustomer();
        $userId    = Session::get('user_id');
        $cartItems = $this->cartModel->getCartItems($userId);

        if (empty($cartItems)) {
            Session::flash('error', 'Keranjang belanja kosong.');
            $this->redirect('cart');
            return;
        }

        $total     = $this->cartModel->getCartTotal($userId);
        $user      = (new User())->findById($userId);
        $pageTitle = 'Checkout — ShineSync';

        $this->view('customer/checkout', compact('cartItems', 'total', 'user', 'pageTitle'));
    }

    /**
     * TRANSACTION: START TRANSACTION → INSERT orders → INSERT order_details
     *              (trigger otomatis kurangi stok) → INSERT payments → DELETE cart
     *              → COMMIT jika sukses, ROLLBACK jika gagal
     */
    public function process(): void
    {
        Session::requireCustomer();
        if (!$this->isPost()) { $this->redirect('checkout'); return; }

        $userId    = Session::get('user_id');
        $cartItems = $this->cartModel->getCartItems($userId);

        if (empty($cartItems)) {
            Session::flash('error', 'Keranjang belanja kosong.');
            $this->redirect('cart');
            return;
        }

        // Validasi stok
        foreach ($cartItems as $item) {
            if ($item['quantity'] > $item['stock']) {
                Session::flash('error', "Stok {$item['name']} tidak mencukupi. Sisa: {$item['stock']}");
                $this->redirect('cart');
                return;
            }
        }

        $total        = $this->cartModel->getCartTotal($userId);
        // Gunakan custom MySQL FUNCTION HitungDiskonMember
        $discountRow  = $this->db->queryOne(
            "SELECT HitungDiskonMember(?) AS diskon", [(float)$total]
        );
        $discount     = (float)($discountRow['diskon'] ?? 0);
        $grandTotal   = $total - $discount;

        // Gunakan custom MySQL FUNCTION HitungPoinLoyalitas
        $poinRow      = $this->db->queryOne(
            "SELECT HitungPoinLoyalitas(?) AS poin", [(float)$grandTotal]
        );
        $loyaltyPoin  = (int)($poinRow['poin'] ?? 0);

        $orderNumber  = 'SS-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . '-' . time() % 1000;

        // ── START TRANSACTION ──────────────────────────────────
        $this->db->beginTransaction();

        try {
            // Step 1: Insert order
            $orderId = $this->orderModel->create([
                'user_id'          => $userId,
                'order_number'     => $orderNumber,
                'total_amount'     => $total,
                'discount'         => $discount,
                'grand_total'      => $grandTotal,
                'loyalty_points'   => $loyaltyPoin,
                'shipping_name'    => trim($_POST['shipping_name'] ?? ''),
                'shipping_phone'   => trim($_POST['shipping_phone'] ?? ''),
                'shipping_address' => trim($_POST['shipping_address'] ?? ''),
                'notes'            => trim($_POST['notes'] ?? '')
            ]);

            if (!$orderId) throw new RuntimeException('Gagal membuat order.');

            // Step 2: Insert order_details (trigger stok dan total order otomatis berjalan)
            foreach ($cartItems as $item) {
                $ok = $this->orderModel->addDetail($orderId, [
                    'product_id' => $item['product_id'],
                    'name'       => $item['name'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price']
                ]);
                if (!$ok) throw new RuntimeException("Gagal menambahkan detail order untuk {$item['name']}.");
            }

            // Step 3: Insert payment record
            $paymentOk = $this->paymentModel->create([
                'order_id' => $orderId,
                'amount'   => $grandTotal,
                'method'   => $_POST['payment_method'] ?? 'transfer',
                'bank_name'=> $_POST['bank_name'] ?? '',
                'account_number' => ''
            ]);
            if (!$paymentOk) throw new RuntimeException('Gagal membuat record pembayaran.');

            // Step 4: Hapus cart
            $this->cartModel->clearCart($userId);

            // ── COMMIT ────────────────────────────────────────────
            $this->db->commit();

            Session::flash('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');
            $this->redirect('checkout/success/' . $orderNumber);

        } catch (Throwable $e) {
            // ── ROLLBACK ──────────────────────────────────────────
            $this->db->rollback();

            Session::flash('error', 'Transaksi gagal: ' . $e->getMessage() . '. Semua perubahan dibatalkan (ROLLBACK).');
            $this->redirect('checkout');
        }
    }

    public function success(string $orderNumber): void
    {
        Session::requireCustomer();
        $order = $this->orderModel->findByOrderNumber($orderNumber);
        if (!$order || (int)$order['user_id'] !== Session::get('user_id')) {
            $this->redirect('');
            return;
        }
        $payment   = $this->paymentModel->findByOrderId((int)$order['id']);
        $pageTitle = 'Pesanan Berhasil — ShineSync';
        $this->view('customer/checkout_success', compact('order', 'payment', 'pageTitle'));
    }
}
