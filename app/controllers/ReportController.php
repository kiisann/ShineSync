<?php
// app/controllers/ReportController.php
class ReportController extends Controller
{
    private Report $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Report();
    }

    public function index(): void
    {
        Session::requireAdmin();

        // VIEW: Laporan penjualan
        $laporan  = $this->model->getLaporanPenjualan();
        $summary  = $this->model->getLaporanSummary();

        // VIEW: Produk terlaris
        $terlaris = $this->model->getProdukTerlaris(10);

        // VIEW: Customer aktif
        $customerAktif = $this->model->getCustomerAktif(10);

        // SET OPERATIONS: UNION (customer aktif)
        $customerUnion = $this->model->getCustomerAktifUnion();

        // SET OPERATIONS: UNION ALL (inventaris cincin + kalung)
        $inventarisUnionAll = $this->model->getInventarisUnionAll();

        // Per kategori
        $byCategory = $this->model->getSalesByCategory();

        $pageTitle = 'Laporan Penjualan — ShineSync';
        $this->view('admin/reports/index', compact(
            'laporan', 'summary', 'terlaris', 'customerAktif',
            'customerUnion', 'inventarisUnionAll', 'byCategory', 'pageTitle'
        ));
    }
}
