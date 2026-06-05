<?php
// app/controllers/OrderController.php
class OrderController extends Controller
{
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
    }

    public function history(): void
    {
        Session::requireCustomer();
        $orders    = $this->orderModel->getByUser(Session::get('user_id'));
        $pageTitle = 'Riwayat Pesanan — ShineSync';
        $this->view('customer/order_history', compact('orders', 'pageTitle'));
    }

    public function detail(string $orderNumber): void
    {
        Session::requireCustomer();
        $order = $this->orderModel->findByOrderNumber($orderNumber);
        if (!$order || (int)$order['user_id'] !== Session::get('user_id')) {
            $this->redirect('orders');
            return;
        }
        $orderFull = $this->orderModel->findByIdWithDetails((int)$order['id'], Session::get('user_id'));
        $pageTitle = 'Detail Pesanan #' . $orderNumber;
        $this->view('customer/order_detail', ['order' => $orderFull, 'pageTitle' => $pageTitle]);
    }

    // Admin
    public function adminIndex(): void
    {
        Session::requireAdmin();
        $orders    = $this->orderModel->getAll();
        $pageTitle = 'Kelola Pesanan';
        $this->view('admin/orders/index', compact('orders', 'pageTitle'));
    }

    public function adminAktif(): void
    {
        Session::requireAdmin();
        $orders    = $this->orderModel->getAktif();
        $pageTitle = 'Order Aktif';
        $this->view('admin/orders/aktif', compact('orders', 'pageTitle'));
    }

    public function adminArsip(): void
    {
        Session::requireAdmin();
        $orders    = $this->orderModel->getArsip();
        $pageTitle = 'Order Arsip';
        $this->view('admin/orders/arsip', compact('orders', 'pageTitle'));
    }
    
    public function adminDetail(int $id): void
    {
        Session::requireAdmin();
        $order     = $this->orderModel->findByIdWithDetails($id);
        if (!$order) { $this->redirect('admin/orders'); return; }
        $pageTitle = 'Detail Pesanan #' . $order['order_number'];
        $this->view('admin/orders/detail', compact('order', 'pageTitle'));
    }

    public function adminUpdateStatus(): void
    {
        Session::requireAdmin();
        $id     = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        $allowed = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        if ($id <= 0 || !in_array($status, $allowed, true)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Status pesanan tidak valid.'], 422);
                return;
            }
            Session::flash('error', 'Status pesanan tidak valid.');
            $this->redirect('admin/orders');
            return;
        }

        $this->orderModel->updateStatus($id, $status);
        if ($this->isAjax()) {
            $this->json(['success' => true]);
            return;
        }
        Session::flash('success', 'Status pesanan diperbarui.');
        $this->redirect('admin/orders');
    }
}
