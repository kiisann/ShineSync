<?php
// app/controllers/ProductController.php
// CRUD admin menggunakan Stored Procedure
class ProductController extends Controller
{
    private Product $model;
    private Category $catModel;

    public function __construct()
    {
        parent::__construct();
        $this->model    = new Product();
        $this->catModel = new Category();
    }

    // ── Customer ─────────────────────────────────────────────

    public function index(): void
    {
        $categoryId = (int)($_GET['category'] ?? 0);
        $search     = trim($_GET['q'] ?? '');
        $sort       = $_GET['sort'] ?? 'newest';
        $categories = $this->catModel->getAll();
        $products   = $this->model->getActiveWithRating($categoryId, $search, $sort);
        $pageTitle  = 'Katalog Produk — ShineSync';

        $this->view('customer/catalog', compact('products', 'categories', 'categoryId', 'search', 'sort', 'pageTitle'));
    }

    public function detail(string $slug): void
    {
        $product = $this->model->findBySlugWithReviews($slug);
        if (!$product) {
            $this->redirect('products');
            return;
        }
        $wishlistModel = new Wishlist();
        $inWishlist    = Session::isLoggedIn()
            ? $wishlistModel->exists(Session::get('user_id'), (int)$product['id'])
            : false;
        $pageTitle = $product['name'] . ' — ShineSync';

        $this->view('customer/product_detail', compact('product', 'inWishlist', 'pageTitle'));
    }

    public function search(): void
    {
        $this->redirect('products?q=' . urlencode($_GET['q'] ?? ''));
    }

    // ── Admin (menggunakan Stored Procedure) ─────────────────

    public function adminIndex(): void
    {
        Session::requireAdmin();
        $products  = $this->model->getAllViaSP();
        $pageTitle = 'Kelola Produk';
        $this->view('admin/products/index', compact('products', 'pageTitle'));
    }

    public function adminCreate(): void
    {
        Session::requireAdmin();
        $categories = $this->catModel->getAllAdmin();
        $error      = Session::getFlash('error');
        $pageTitle  = 'Tambah Produk';
        $this->view('admin/products/create', compact('categories', 'error', 'pageTitle'));
    }

    public function adminStore(): void
    {
        Session::requireAdmin();
        if (!$this->isPost()) { $this->redirect('admin/products'); return; }

        $name     = trim($_POST['name'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);

        if ($name === '' || $categoryId <= 0 || $price < 0 || $stock < 0) {
            Session::flash('error', 'Nama, kategori, harga, dan stok produk wajib valid.');
            $this->redirect('admin/products/create');
            return;
        }

        $slug     = $this->createSlug($name);
        $image    = $this->uploadImage($_FILES['image'] ?? null, 'products');

        if ($this->model->slugExists($slug)) {
            $slug .= '-' . time();
        }

        // Panggil Stored Procedure
        $this->model->createViaSP([
            'category_id' => $categoryId,
            'name'        => $name,
            'slug'        => $slug,
            'description' => $_POST['description'] ?? '',
            'price'       => $price,
            'stock'       => $stock,
            'weight'      => (float)($_POST['weight'] ?? 0),
            'material'    => $_POST['material'] ?? '',
            'image'       => $image,
            'is_featured' => (int)isset($_POST['is_featured'])
        ]);

        Session::flash('success', 'Produk berhasil ditambahkan via Stored Procedure.');
        $this->redirect('admin/products');
    }

    public function adminEdit(int $id): void
    {
        Session::requireAdmin();
        $product    = $this->model->findByIdViaSP($id);
        if (!$product) { $this->redirect('admin/products'); return; }
        $categories = $this->catModel->getAllAdmin();
        $error      = Session::getFlash('error');
        $pageTitle  = 'Edit Produk';
        $this->view('admin/products/edit', compact('product', 'categories', 'error', 'pageTitle'));
    }

    public function adminUpdate(int $id): void
    {
        Session::requireAdmin();
        if (!$this->isPost()) { $this->redirect('admin/products'); return; }

        $name  = trim($_POST['name'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);

        if ($name === '' || $categoryId <= 0 || $price < 0 || $stock < 0) {
            Session::flash('error', 'Nama, kategori, harga, dan stok produk wajib valid.');
            $this->redirect('admin/products/edit/' . $id);
            return;
        }

        $slug  = $this->createSlug($name);
        $image = $this->uploadImage($_FILES['image'] ?? null, 'products');

        if ($this->model->slugExists($slug, $id)) {
            $slug .= '-' . $id;
        }

        // Panggil Stored Procedure
        $this->model->updateViaSP($id, [
            'category_id' => $categoryId,
            'name'        => $name,
            'slug'        => $slug,
            'description' => $_POST['description'] ?? '',
            'price'       => $price,
            'stock'       => $stock,
            'weight'      => (float)($_POST['weight'] ?? 0),
            'material'    => $_POST['material'] ?? '',
            'image'       => $image,
            'is_featured' => (int)isset($_POST['is_featured'])
        ]);

        Session::flash('success', 'Produk berhasil diperbarui via Stored Procedure.');
        $this->redirect('admin/products');
    }

    public function adminDelete(int $id): void
    {
        Session::requireAdmin();
        $this->model->deleteViaSP($id);
        if ($this->isAjax()) {
            $this->json(['success' => true]);
            return;
        }
        Session::flash('success', 'Produk berhasil dihapus.');
        $this->redirect('admin/products');
    }

    // ── Helpers ───────────────────────────────────────────────

    private function createSlug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', trim($text));
        return $text;
    }

    private function uploadImage(?array $file, string $folder): string
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) return '';
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed)) return '';
        $filename = uniqid() . '.' . $ext;
        $dest     = UPLOAD_PATH . $folder . '/' . $filename;
        if (!is_dir(UPLOAD_PATH . $folder)) mkdir(UPLOAD_PATH . $folder, 0777, true);
        if (move_uploaded_file($file['tmp_name'], $dest)) return $filename;
        return '';
    }
}
