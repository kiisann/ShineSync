<?php
// app/controllers/CategoryController.php
class CategoryController extends Controller
{
    private Category $model;

    public function __construct() { parent::__construct(); $this->model = new Category(); }

    public function adminIndex(): void
    {
        Session::requireAdmin();
        $categories = $this->model->getAllAdmin();
        $pageTitle  = 'Kelola Kategori';
        $this->view('admin/categories/index', compact('categories', 'pageTitle'));
    }

    public function adminCreate(): void
    {
        Session::requireAdmin();
        $error     = Session::getFlash('error');
        $pageTitle = 'Tambah Kategori';
        $this->view('admin/categories/form', compact('error', 'pageTitle'));
    }

    public function adminStore(): void
    {
        Session::requireAdmin();
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            Session::flash('error', 'Nama kategori wajib diisi.');
            $this->redirect('admin/categories/create');
            return;
        }

        $slug = $this->createSlug($name);
        if ($this->model->slugExists($slug)) {
            Session::flash('error', 'Nama kategori sudah ada.');
            $this->redirect('admin/categories/create');
            return;
        }
        $this->model->create([
            'name' => $name, 'slug' => $slug,
            'description' => $_POST['description'] ?? '',
            'is_active' => (int)isset($_POST['is_active'])
        ]);
        Session::flash('success', 'Kategori berhasil ditambahkan.');
        $this->redirect('admin/categories');
    }

    public function adminEdit(int $id): void
    {
        Session::requireAdmin();
        $category  = $this->model->findById($id);
        if (!$category) { $this->redirect('admin/categories'); return; }
        $error     = Session::getFlash('error');
        $pageTitle = 'Edit Kategori';
        $this->view('admin/categories/form', compact('category', 'error', 'pageTitle'));
    }

    public function adminUpdate(int $id): void
    {
        Session::requireAdmin();
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            Session::flash('error', 'Nama kategori wajib diisi.');
            $this->redirect('admin/categories/edit/' . $id);
            return;
        }

        $slug = $this->createSlug($name);
        if ($this->model->slugExists($slug, $id)) { $slug .= '-' . $id; }
        $this->model->update($id, [
            'name' => $name, 'slug' => $slug,
            'description' => $_POST['description'] ?? '',
            'is_active' => (int)isset($_POST['is_active'])
        ]);
        Session::flash('success', 'Kategori berhasil diperbarui.');
        $this->redirect('admin/categories');
    }

    public function adminDelete(int $id): void
    {
        Session::requireAdmin();
        $this->model->delete($id);
        if ($this->isAjax()) { $this->json(['success' => true]); return; }
        Session::flash('success', 'Kategori berhasil dihapus.');
        $this->redirect('admin/categories');
    }

    private function createSlug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        return preg_replace('/[\s-]+/', '-', trim($text));
    }
}
