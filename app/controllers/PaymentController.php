<?php
// app/controllers/PaymentController.php
class PaymentController extends Controller
{
    private Payment $model;
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->model      = new Payment();
        $this->orderModel = new Order();
    }

    public function upload(string $orderNumber): void
    {
        Session::requireCustomer();
        $order = $this->orderModel->findByOrderNumber($orderNumber);
        if (!$order || (int)$order['user_id'] !== Session::get('user_id')) {
            $this->redirect('orders'); return;
        }
        $payment   = $this->model->findByOrderId((int)$order['id']);
        $pageTitle = 'Upload Bukti Pembayaran';

        if ($this->isPost()) {
            $file = $_FILES['proof'] ?? null;
            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                Session::flash('error', 'File bukti pembayaran wajib diupload.');
                $this->redirect('orders/payment/' . $orderNumber);
                return;
            }
            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg','jpeg','png','pdf'];
            if (!in_array($ext, $allowed)) {
                Session::flash('error', 'Format file tidak didukung (jpg, png, pdf).');
                $this->redirect('orders/payment/' . $orderNumber);
                return;
            }
            $filename = 'pay_' . $order['id'] . '_' . time() . '.' . $ext;
            $dest     = UPLOAD_PATH . 'payments/' . $filename;
            if (!is_dir(UPLOAD_PATH . 'payments')) mkdir(UPLOAD_PATH . 'payments', 0777, true);
            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                Session::flash('error', 'Upload gagal. Silakan coba lagi.');
                $this->redirect('orders/payment/' . $orderNumber);
                return;
            }
            $this->model->uploadProof((int)$order['id'], $filename);
            Session::flash('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
            $this->redirect('orders');
        }

        $this->view('customer/upload_payment', compact('order', 'payment', 'pageTitle'));
    }

    // Admin
    public function adminIndex(): void
    {
        Session::requireAdmin();
        $payments  = $this->model->getAll();
        $pageTitle = 'Verifikasi Pembayaran';
        $this->view('admin/payments/index', compact('payments', 'pageTitle'));
    }

    public function adminVerify(): void
    {
        Session::requireAdmin();
        $id = (int)($_POST['payment_id'] ?? 0);
        $this->model->verify($id, Session::get('user_id'));
        // Status order otomatis diubah oleh trigger tr_auto_order_status.
        if ($this->isAjax()) { $this->json(['success' => true]); return; }
        Session::flash('success', 'Pembayaran berhasil diverifikasi.');
        $this->redirect('admin/payments');
    }

    public function adminReject(): void
    {
        Session::requireAdmin();
        $id    = (int)($_POST['payment_id'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');
        $this->model->reject($id, Session::get('user_id'), $notes);
        if ($this->isAjax()) { $this->json(['success' => true]); return; }
        Session::flash('success', 'Pembayaran ditolak.');
        $this->redirect('admin/payments');
    }
}
