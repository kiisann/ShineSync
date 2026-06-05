<?php
// app/controllers/ReviewController.php
class ReviewController extends Controller
{
    private Review $model;

    public function __construct() { parent::__construct(); $this->model = new Review(); }

    public function index(): void { $this->redirect(''); }

    public function store(): void
    {
        Session::requireCustomer();
        $userId    = Session::get('user_id');
        $productId = (int)($_POST['product_id'] ?? 0);
        $orderId   = (int)($_POST['order_id'] ?? 0);
        $rating    = max(1, min(5, (int)($_POST['rating'] ?? 5)));
        $comment   = trim($_POST['comment'] ?? '');

        if ($this->model->hasReviewed($userId, $productId, $orderId)) {
            Session::flash('error', 'Anda sudah memberikan review untuk produk ini.');
            $this->redirect('orders');
            return;
        }

        $this->model->create([
            'user_id'    => $userId,
            'product_id' => $productId,
            'order_id'   => $orderId,
            'rating'     => $rating,
            'comment'    => $comment
        ]);
        Session::flash('success', 'Review berhasil dikirim. Terima kasih!');
        $this->redirect('orders/detail/' . $_POST['order_number'] ?? '');
    }

    public function adminIndex(): void
    {
        Session::requireAdmin();
        $reviews   = $this->model->getAll();
        $pageTitle = 'Kelola Review';
        $this->view('admin/reviews/index', compact('reviews', 'pageTitle'));
    }

    public function adminToggle(int $id): void
    {
        Session::requireAdmin();
        $this->model->toggle($id);
        if ($this->isAjax()) { $this->json(['success' => true]); return; }
        Session::flash('success', 'Status review diperbarui.');
        $this->redirect('admin/reviews');
    }
}
