<?php
// app/controllers/DashboardController.php
class DashboardController extends Controller
{
    public function index(): void
    {
        Session::requireAdmin();

        $productModel  = new Product();
        $userModel     = new User();
        $orderModel    = new Order();
        $paymentModel  = new Payment();
        $reportModel   = new Report();

        // Built-in SQL Functions: COUNT, SUM, AVG
        $totalProducts = $productModel->getTotalActive();
        $totalCustomers= $userModel->getTotalCustomers();
        $totalOrders   = $orderModel->getTotalOrders();
        $totalRevenue  = $orderModel->getTotalRevenue();
        $pendingPayments = $paymentModel->getPendingCount();

        // Grafik (DATE_FORMAT)
        $monthlySales  = $orderModel->getMonthlySales();

        // VIEW: Produk terlaris
        $bestsellers   = $reportModel->getProdukTerlaris(5);

        // VIEW: Customer aktif
        $activeCustomers = $reportModel->getCustomerAktif(5);

        $pageTitle = 'Dashboard Admin — ShineSync';
        $this->view('admin/dashboard', compact(
            'totalProducts','totalCustomers','totalOrders','totalRevenue',
            'pendingPayments','monthlySales','bestsellers','activeCustomers','pageTitle'
        ));
    }

    public function customers(): void
    {
        Session::requireAdmin();
        $customers = (new User())->getAllCustomers();
        $pageTitle = 'Data Customer';
        $this->view('admin/customers/index', compact('customers', 'pageTitle'));
    }
}
