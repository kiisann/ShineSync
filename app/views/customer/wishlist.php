<?php
// app/views/customer/wishlist.php
include __DIR__ . '/../layouts/header.php';
function fRpW($n){return 'Rp '.number_format($n,0,',','.');}
function renderStarW($avg){$f=round($avg);$o='';for($i=1;$i<=5;$i++)$o.='<i class="'.($i<=$f?'fas':'far').' fa-star"></i>';return $o;}
function wImg($img){if($img&&file_exists(UPLOAD_PATH.'products/'.$img))return APP_URL.'/uploads/products/'.$img;return APP_URL.'/public/images/no-image.svg';}
?>
<div class="ss-breadcrumb"><div class="container">
  <nav><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
    <li class="breadcrumb-item active">Wishlist</li>
  </ol></nav>
</div></div>
<section class="ss-section-sm">
  <div class="container">
    <h2 class="section-title mb-4">Wishlist Saya</h2>
    <?php if (empty($items)): ?>
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-heart"></i></div>
      <h4>Wishlist Kosong</h4>
      <p>Tambahkan produk favorit ke wishlist!</p>
      <a href="<?= APP_URL ?>/products" class="btn-gold">Jelajahi Produk</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($items as $p): ?>
      <div class="col-6 col-md-4 col-xl-3">
        <div class="product-card">
          <div class="card-img-wrap">
            <a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>">
              <img src="<?= wImg($p['image']??'') ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            </a>
            <button class="card-wishlist-btn btn-wishlist active" data-id="<?= $p['product_id'] ?>">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="card-body">
            <p class="card-category"><?= htmlspecialchars($p['category_name']??'') ?></p>
            <h6 class="card-title"><?= htmlspecialchars($p['name']) ?></h6>
            <div class="card-rating">
              <span class="stars"><?= renderStarW($p['avg_rating']) ?></span>
            </div>
            <div class="card-price"><?= fRpW($p['price']) ?></div>
          </div>
          <div class="card-footer">
            <a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>" class="btn-outline-gold btn-sm" style="flex:1;justify-content:center;">Detail</a>
            <?php if ($p['stock']>0): ?>
            <button class="btn-gold btn-sm btn-add-cart" data-id="<?= $p['product_id'] ?>" style="flex:1;justify-content:center;"><i class="fas fa-cart-plus"></i></button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
