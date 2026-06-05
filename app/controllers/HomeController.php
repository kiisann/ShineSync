<?php
// app/controllers/HomeController.php
class HomeController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;
    private Review $reviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel  = new Product();
        $this->categoryModel = new Category();
        $this->reviewModel   = new Review();
    }

    public function index(): void
    {
        $categories  = $this->categoryModel->getAll();
        $featured    = $this->productModel->getFeatured(8);
        $bestsellers = $this->productModel->getBestsellers(6);
        $reviews     = $this->reviewModel->getApprovedReviews(6);
        $pageTitle   = 'ShineSync — Perhiasan Mewah Indonesia';

        $this->view('customer/home', compact(
            'categories', 'featured', 'bestsellers', 'reviews', 'pageTitle'
        ));
    }

    public function notFound(): void
    {
        http_response_code(404);
        $pageTitle = '404 — Halaman Tidak Ditemukan';
        $this->view('errors/404', compact('pageTitle'));
    }
}
