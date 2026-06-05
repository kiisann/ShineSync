<?php
/**
 * ShineSync — Front Controller & Router
 * Semua request masuk ke sini melalui .htaccess
 */

// ── Bootstrap ─────────────────────────────────────────────────
define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Session.php';

// Start session
Session::start();

// ── Models ────────────────────────────────────────────────────
$models = ['User','Product','Category','Cart','Order','Payment','Review','Wishlist','Report'];
foreach ($models as $m) {
    require_once ROOT_PATH . "/app/models/{$m}.php";
}

// ── Controllers ───────────────────────────────────────────────
$controllers = [
    'Auth','Home','Product','Category','Cart',
    'Checkout','Order','Payment','Review',
    'Wishlist','Profile','Dashboard','Report'
];
foreach ($controllers as $c) {
    require_once ROOT_PATH . "/app/controllers/{$c}Controller.php";
}

// ── Routing ───────────────────────────────────────────────────
$uri    = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$base   = trim(parse_url(APP_URL, PHP_URL_PATH), '/');
$uri    = $base ? preg_replace('#^' . preg_quote($base, '#') . '#', '', $uri) : $uri;
$uri    = trim($uri, '/');
$parts  = explode('/', $uri);

$segment0 = $parts[0] ?? '';
$segment1 = $parts[1] ?? '';
$segment2 = $parts[2] ?? '';

// ── Route Table ───────────────────────────────────────────────
try {
    // ── AUTH ─────────────────────────────────────────────────
    if ($segment0 === 'auth') {
        $ctrl = new AuthController();
        match ($segment1) {
            'login'    => $ctrl->login(),
            'register' => $ctrl->register(),
            'logout'   => $ctrl->logout(),
            default    => $ctrl->login()
        };
    }

    // ── ADMIN LOGIN ──────────────────────────────────────────
    elseif ($segment0 === 'admin' && $segment1 === 'login') {
        $ctrl = new AuthController();
        $ctrl->adminLogin();
    }

    // ── ADMIN ROUTES ─────────────────────────────────────────
    elseif ($segment0 === 'admin') {
        Session::requireAdmin();

        match ($segment1) {
            // Dashboard
            '', 'dashboard' => (new DashboardController())->index(),

            // Products
            'products' => match ($segment2) {
                'create' => (new ProductController())->adminCreate(),
                'store'  => (new ProductController())->adminStore(),
                'edit'   => (new ProductController())->adminEdit($parts[3] ?? 0),
                'update' => (new ProductController())->adminUpdate($parts[3] ?? 0),
                'delete' => (new ProductController())->adminDelete($parts[3] ?? 0),
                default  => (new ProductController())->adminIndex()
            },

            // Categories
            'categories' => match ($segment2) {
                'create' => (new CategoryController())->adminCreate(),
                'store'  => (new CategoryController())->adminStore(),
                'edit'   => (new CategoryController())->adminEdit($parts[3] ?? 0),
                'update' => (new CategoryController())->adminUpdate($parts[3] ?? 0),
                'delete' => (new CategoryController())->adminDelete($parts[3] ?? 0),
                default  => (new CategoryController())->adminIndex()
            },

            // Orders
            'orders' => match ($segment2) {
                'detail'       => (new OrderController())->adminDetail($parts[3] ?? 0),
                'update-status'=> (new OrderController())->adminUpdateStatus(),
                'aktif'        => (new OrderController())->adminAktif(),
                'arsip'        => (new OrderController())->adminArsip(),
                default        => (new OrderController())->adminIndex()
            },

            // Payments
            'payments' => match ($segment2) {
                'verify' => (new PaymentController())->adminVerify(),
                'reject' => (new PaymentController())->adminReject(),
                default  => (new PaymentController())->adminIndex()
            },

            // Customers
            'customers' => (new DashboardController())->customers(),

            // Reviews
            'reviews' => match ($segment2) {
                'toggle' => (new ReviewController())->adminToggle($parts[3] ?? 0),
                default  => (new ReviewController())->adminIndex()
            },

            // Reports
            // Reports
            'reports' => (new ReportController())->index(),

            // Backup Database
            'backup' => require ROOT_PATH . '/backup.php',
            'backup-list' => require ROOT_PATH . '/backup_list.php',

            default => (new DashboardController())->index()
        };
    }

    // ── CUSTOMER / PUBLIC ROUTES ─────────────────────────────
    else {
        match ($segment0) {
            // Home
            '' => (new HomeController())->index(),

            // Products
            'products' => match ($segment1) {
                ''       => (new ProductController())->index(),
                'search' => (new ProductController())->search(),
                default  => (new ProductController())->detail($segment1)
            },

            // Cart
            'cart' => match ($segment1) {
                'add'    => (new CartController())->add(),
                'update' => (new CartController())->update(),
                'remove' => (new CartController())->remove(),
                'count'  => (new CartController())->count(),
                default  => (new CartController())->index()
            },

            // Checkout
            'checkout' => match ($segment1) {
                'process' => (new CheckoutController())->process(),
                'success' => (new CheckoutController())->success($parts[2] ?? ''),
                default   => (new CheckoutController())->index()
            },

            // Orders
            'orders' => match ($segment1) {
                'detail' => (new OrderController())->detail($segment2),
                'payment'=> (new PaymentController())->upload($segment2),
                default  => (new OrderController())->history()
            },

            // Wishlist
            'wishlist' => match ($segment1) {
                'add'    => (new WishlistController())->add(),
                'remove' => (new WishlistController())->remove(),
                default  => (new WishlistController())->index()
            },

            // Reviews
            'reviews' => match ($segment1) {
                'store' => (new ReviewController())->store(),
                default => (new ReviewController())->index()
            },

            // Profile
            'profile' => match ($segment1) {
                'update'          => (new ProfileController())->update(),
                'change-password' => (new ProfileController())->changePassword(),
                default           => (new ProfileController())->index()
            },

            default => (new HomeController())->notFound()
        };
    }

} catch (Throwable $e) {
    if (Session::isAdmin()) {
        echo '<div style="font-family:monospace;padding:20px;background:#1a1a1a;color:#f00">';
        echo '<h3>Error: ' . htmlspecialchars($e->getMessage()) . '</h3>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</div>';
    } else {
        Session::flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        header('Location: ' . APP_URL);
        exit;
    }
}
