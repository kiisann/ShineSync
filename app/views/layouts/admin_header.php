<?php
// app/views/layouts/admin_header.php
$currentUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$baseUri = trim(parse_url(APP_URL, PHP_URL_PATH), '/');
$currentUri = $baseUri ? ltrim(substr($currentUri, strlen($baseUri)), '/') : $currentUri;
$parts = explode('/', $currentUri);
$segment1 = $parts[1] ?? '';

function adminNavActive(string $segment, string $current): string {
    return str_starts_with($current, $segment) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="app-url" content="<?= APP_URL ?>">
  <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — ShineSync</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= APP_URL ?>/public/css/admin.css">
</head>
<body>

<!-- Sidebar -->
<aside class="admin-sidebar" id="admin-sidebar">
  <div class="sidebar-logo">
    <div class="brand">Shine<span>Sync</span></div>
    <small>Admin Panel</small>
  </div>
  <nav class="sidebar-menu">
    <div class="sidebar-label">Utama</div>
    <a href="<?= APP_URL ?>/admin/dashboard" class="sidebar-link <?= adminNavActive('dashboard', $segment1) ?>">
      <i class="fas fa-th-large"></i> Dashboard
    </a>

    <div class="sidebar-label">Katalog</div>
    <a href="<?= APP_URL ?>/admin/products" class="sidebar-link <?= adminNavActive('products', $segment1) ?>">
      <i class="fas fa-gem"></i> Produk <span style="font-size:.7rem;color:rgba(255,255,255,.3);margin-left:4px;">(SP)</span>
    </a>
    <a href="<?= APP_URL ?>/admin/categories" class="sidebar-link <?= adminNavActive('categories', $segment1) ?>">
      <i class="fas fa-tags"></i> Kategori
    </a>

    <div class="sidebar-label">Transaksi</div>
    <a href="<?= APP_URL ?>/admin/orders" class="sidebar-link <?= adminNavActive('orders', $segment1) ?>">
      <i class="fas fa-shopping-bag"></i> Pesanan
    </a>
    <a href="<?= APP_URL ?>/admin/payments" class="sidebar-link <?= adminNavActive('payments', $segment1) ?>">
      <i class="fas fa-credit-card"></i> Pembayaran
      <?php
      $pendingCount = (new Payment())->getPendingCount();
      if ($pendingCount > 0): ?>
      <span class="badge-pill"><?= $pendingCount ?></span>
      <?php endif; ?>
    </a>

    <div class="sidebar-label">Pengguna</div>
    <a href="<?= APP_URL ?>/admin/customers" class="sidebar-link <?= adminNavActive('customers', $segment1) ?>">
      <i class="fas fa-users"></i> Customer
    </a>
    <a href="<?= APP_URL ?>/admin/reviews" class="sidebar-link <?= adminNavActive('reviews', $segment1) ?>">
      <i class="fas fa-star"></i> Review
    </a>

    <div class="sidebar-label">Laporan (PDD)</div>
    <a href="<?= APP_URL ?>/admin/reports" class="sidebar-link <?= adminNavActive('reports', $segment1) ?>">
      <i class="fas fa-chart-bar"></i> Laporan &amp; Analitik
    </a>

    <div class="sidebar-label" style="margin-top:20px;"></div>
    <a href="<?= APP_URL ?>/auth/logout" class="sidebar-link" style="color:rgba(220,53,69,.7);">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </nav>
</aside>

<!-- Main -->
<div class="admin-main">
  <!-- Topbar -->
  <header class="admin-topbar">
    <div class="d-flex align-items-center gap-12">
      <button id="sidebar-toggle" class="btn" style="background:none;border:none;font-size:1.2rem;color:#333;padding:0;margin-right:12px;">
        <i class="fas fa-bars"></i>
      </button>
      <span class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></span>
    </div>
    <div class="topbar-actions">
      <a href="<?= APP_URL ?>" target="_blank" class="btn btn-sm" style="border:1.5px solid #E5E5E5;border-radius:8px;font-size:.82rem;color:#555;gap:6px;display:flex;align-items:center;">
        <i class="fas fa-external-link-alt"></i> Lihat Toko
      </a>
      <div class="d-flex align-items-center gap-2">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#B8960C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.88rem;">
          <?= strtoupper(substr(Session::get('user_name','A'),0,1)) ?>
        </div>
        <span class="topbar-user d-none d-md-block"><?= htmlspecialchars(Session::get('user_name')) ?></span>
      </div>
    </div>
  </header>
  <!-- Content -->
  <div class="admin-content">

<?php
$successMsg = Session::getFlash('success');
$errorMsg   = Session::getFlash('error');
if ($successMsg): ?>
<div class="alert alert-success alert-dismissible fade show auto-dismiss" style="border-radius:12px;border:none;background:rgba(40,167,69,.1);color:#155724;">
  <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($successMsg) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; if ($errorMsg): ?>
<div class="alert alert-danger alert-dismissible fade show auto-dismiss" style="border-radius:12px;border:none;background:rgba(220,53,69,.1);color:#721c24;">
  <i class="fas fa-times-circle me-2"></i><?= htmlspecialchars($errorMsg) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
