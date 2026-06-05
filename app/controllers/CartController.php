<?php
// app/controllers/CartController.php
class CartController extends Controller
{
    private Cart $cartModel;
    private Product $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->cartModel    = new Cart();
        $this->productModel = new Product();
    }

    public function index(): void
    {
        Session::requireCustomer();
        $userId    = Session::get('user_id');
        $cartItems = $this->cartModel->getCartItems($userId);
        $total     = $this->cartModel->getCartTotal($userId);
        $pageTitle = 'Keranjang Belanja — ShineSync';
        $this->view('customer/cart', compact('cartItems', 'total', 'pageTitle'));
    }

    public function add(): void
    {
        Session::requireCustomer();
        $userId    = Session::get('user_id');
        $productId = (int)($_POST['product_id'] ?? 0);
        $qty       = max(1, (int)($_POST['quantity'] ?? 1));

        $product = $this->productModel->findById($productId);
        if (!$product || $product['stock'] < $qty) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Stok tidak mencukupi.']);
                return;
            }
            Session::flash('error', 'Stok tidak mencukupi.');
            $this->redirect('products');
            return;
        }

        $this->cartModel->addItem($userId, $productId, $qty, (float)$product['price']);

        $count = $this->cartModel->getCartCount($userId);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'count' => $count, 'message' => 'Produk ditambahkan ke keranjang!']);
            return;
        }
        Session::flash('success', 'Produk berhasil ditambahkan ke keranjang!');
        $this->redirect('cart');
    }

    public function update(): void
    {
        Session::requireCustomer();
        $userId   = Session::get('user_id');
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $qty      = max(1, (int)($_POST['quantity'] ?? 1));

        $item = $this->cartModel->getItemForUser($detailId, $userId);
        if (!$item || !$item['is_active']) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
                return;
            }
            Session::flash('error', 'Produk tidak ditemukan.');
            $this->redirect('cart');
            return;
        }

        if ($qty > (int)$item['stock']) {
            if ($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => "Stok {$item['name']} tersisa {$item['stock']}.",
                    'max' => (int)$item['stock']
                ], 422);
                return;
            }
            Session::flash('error', "Stok {$item['name']} tersisa {$item['stock']}.");
            $this->redirect('cart');
            return;
        }

        $this->cartModel->updateItem($detailId, $userId, $qty);

        if ($this->isAjax()) {
            $total = $this->cartModel->getCartTotal($userId);
            $count = $this->cartModel->getCartCount($userId);
            $this->json(['success' => true, 'total' => $total, 'count' => $count]);
            return;
        }
        $this->redirect('cart');
    }

    public function remove(): void
    {
        Session::requireCustomer();
        $userId   = Session::get('user_id');
        $detailId = (int)($_POST['detail_id'] ?? $_GET['id'] ?? 0);
        $this->cartModel->removeItem($detailId, $userId);

        if ($this->isAjax()) {
            $total = $this->cartModel->getCartTotal($userId);
            $count = $this->cartModel->getCartCount($userId);
            $this->json(['success' => true, 'total' => $total, 'count' => $count]);
            return;
        }
        $this->redirect('cart');
    }

    public function count(): void
    {
        if (!Session::isLoggedIn()) {
            $this->json(['count' => 0]);
            return;
        }
        $count = $this->cartModel->getCartCount(Session::get('user_id'));
        $this->json(['count' => $count]);
    }
}
