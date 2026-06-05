<?php
// app/controllers/WishlistController.php
class WishlistController extends Controller
{
    private Wishlist $model;

    public function __construct() { parent::__construct(); $this->model = new Wishlist(); }

    public function index(): void
    {
        Session::requireCustomer();
        $items     = $this->model->getByUser(Session::get('user_id'));
        $pageTitle = 'Wishlist Saya — ShineSync';
        $this->view('customer/wishlist', compact('items', 'pageTitle'));
    }

    public function add(): void
    {
        Session::requireCustomer();
        $productId = (int)($_POST['product_id'] ?? 0);

        $product = (new Product())->findById($productId);
        if (!$product) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
                return;
            }
            Session::flash('error', 'Produk tidak ditemukan.');
            $this->redirect('products');
            return;
        }

        $this->model->add(Session::get('user_id'), $productId);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => 'Ditambahkan ke wishlist!']);
            return;
        }
        Session::flash('success', 'Produk ditambahkan ke wishlist.');
        $this->redirect('wishlist');
    }

    public function remove(): void
    {
        Session::requireCustomer();
        $productId = (int)($_POST['product_id'] ?? 0);
        $this->model->remove(Session::get('user_id'), $productId);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => 'Dihapus dari wishlist.']);
            return;
        }
        Session::flash('success', 'Produk dihapus dari wishlist.');
        $this->redirect('wishlist');
    }
}
