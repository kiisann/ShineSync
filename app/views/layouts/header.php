<?php
// app/views/layouts/header.php
$cartCount = 0;
if (Session::isLoggedIn() && Session::isCustomer()) {
    $cartModel = new Cart();
    $cartCount = $cartModel->getCartCount(Session::get('user_id'));
}
$currentUrl = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="ShineSync — Perhiasan Mewah Indonesia. Cincin, Kalung, Gelang, Anting berkualitas premium.">
  <meta name="app-url" content="<?= APP_URL ?>">
  <title><?= htmlspecialchars($pageTitle ?? 'ShineSync') ?></title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
  <link rel="icon" href="<?= APP_URL ?>/public/images/favicon.svg" type="image/svg+xml">
</head>
<body>

<!-- Loading Spinner -->
<div class="ss-loader" id="ss-loader">
  <div class="ss-spinner"></div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toast-container"></div>

<?php
// Flash messages
$success = Session::getFlash('success');
$error   = Session::getFlash('error');
if ($success): ?>
<div class="alert-flash success auto-dismiss" style="position:fixed;top:20px;right:20px;z-index:9999;background:#fff;border-left:4px solid #28A745;border-radius:12px;padding:14px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.15);min-width:280px;display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:500;">
  <i class="fas fa-check-circle" style="color:#28A745;font-size:1.1rem;"></i> <?= htmlspecialchars($success) ?>
</div>
<?php endif; if ($error): ?>
<div class="alert-flash error auto-dismiss" style="position:fixed;top:20px;right:20px;z-index:9999;background:#fff;border-left:4px solid #DC3545;border-radius:12px;padding:14px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.15);min-width:280px;display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:500;">
  <i class="fas fa-times-circle" style="color:#DC3545;font-size:1.1rem;"></i> <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<!-- Navbar -->
<nav class="ss-navbar navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="<?= APP_URL ?>">
      Shine<span>Sync</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <i class="fas fa-bars" style="color:#1A1A1A;"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/products">Produk</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kategori</a>
          <ul class="dropdown-menu" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,0,0,0.12);padding:8px;">
            <?php $cats = (new Category())->getAll(); foreach ($cats as $c): ?>
            <li><a class="dropdown-item" style="border-radius:8px;font-size:.88rem;padding:8px 16px;"
                   href="<?= APP_URL ?>/products?category=<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <!-- Search -->
        <form action="<?= APP_URL ?>/products/search" method="GET" class="d-none d-lg-flex">
          <div class="input-group" style="width:220px;">
            <input type="text" name="q" class="form-control" placeholder="Cari perhiasan..." style="border-radius:50px 0 0 50px;border:1.5px solid #E5E5E5;font-size:.85rem;">
            <button class="btn" style="background:#D4AF37;color:#fff;border-radius:0 50px 50px 0;padding:0 16px;border:none;">
              <i class="fas fa-search" style="font-size:.85rem;"></i>
            </button>
          </div>
        </form>
        <!-- Wishlist -->
        <?php if (Session::isLoggedIn()): ?>
        <a href="<?= APP_URL ?>/wishlist" class="nav-icon-btn">
          <i class="far fa-heart"></i>
        </a>
        <!-- Cart -->
        <a href="<?= APP_URL ?>/cart" class="nav-icon-btn">
          <i class="fas fa-shopping-bag"></i>
          <span class="cart-badge cart-count" style="<?= $cartCount == 0 ? 'display:none' : '' ?>"><?= $cartCount ?></span>
        </a>
        <!-- User Dropdown -->
        <div class="dropdown">
          <button class="btn d-flex align-items-center gap-2" style="background:rgba(212,175,55,0.08);border:none;border-radius:50px;padding:6px 16px 6px 8px;" data-bs-toggle="dropdown">
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#B8960C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;">
              <?= strtoupper(substr(Session::get('user_name','U'),0,1)) ?>
            </div>
            <span style="font-size:.85rem;font-weight:500;"><?= htmlspecialchars(Session::get('user_name')) ?></span>
            <i class="fas fa-chevron-down" style="font-size:.7rem;color:#777;"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,0,0,0.12);padding:8px;min-width:180px;">
            <li><a class="dropdown-item" style="border-radius:8px;font-size:.88rem;" href="<?= APP_URL ?>/profile"><i class="fas fa-user me-2" style="color:#D4AF37;width:16px;"></i>Profil</a></li>
            <li><a class="dropdown-item" style="border-radius:8px;font-size:.88rem;" href="<?= APP_URL ?>/orders"><i class="fas fa-box me-2" style="color:#D4AF37;width:16px;"></i>Pesanan</a></li>
            <li><hr class="dropdown-divider" style="margin:6px;"></li>
            <li><a class="dropdown-item text-danger" style="border-radius:8px;font-size:.88rem;" href="<?= APP_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2;width:16px;"></i>Logout</a></li>
          </ul>
        </div>
        <?php else: ?>
        <a href="<?= APP_URL ?>/auth/login" class="btn-outline-gold btn-sm">Login</a>
        <a href="<?= APP_URL ?>/auth/register" class="btn-gold btn-sm">Daftar</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
