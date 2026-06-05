<?php
// app/views/customer/catalog.php
include __DIR__ . '/../layouts/header.php';
function formatRp($n) { return 'Rp ' . number_format($n,0,',','.'); }
function renderStar($avg) { $f=round($avg); $o=''; for($i=1;$i<=5;$i++) $o.='<i class="'.($i<=$f?'fas':'far').' fa-star"></i>'; return $o; }
function prodImg($img) { if($img&&file_exists(UPLOAD_PATH.'products/'.$img)) return APP_URL.'/uploads/products/'.$img; return APP_URL.'/public/images/no-image.svg'; }
?>

<!-- Breadcrumb -->
<div class="ss-breadcrumb">
  <div class="container">
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
      <li class="breadcrumb-item active">Produk</li>
    </ol></nav>
  </div>
</div>

<section class="ss-section-sm">
  <div class="container">
    <!-- Filter Bar -->
    <div class="filter-bar">
      <form method="GET" action="<?= APP_URL ?>/products" class="d-flex flex-wrap align-items-center gap-3 w-100">
        <div class="flex-grow-1" style="min-width:200px;">
          <div class="input-group">
            <span class="input-group-text" style="background:#F8F8F8;border:1.5px solid #E5E5E5;border-right:none;">
              <i class="fas fa-search" style="color:#D4AF37;"></i>
            </span>
            <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>" style="border-left:none;">
          </div>
        </div>
        <select name="category" class="form-select" style="width:180px;">
          <option value="0">Semua Kategori</option>
          <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $categoryId==$cat['id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="sort" class="form-select" style="width:160px;">
          <option value="newest"    <?= $sort=='newest'    ?'selected':'' ?>>Terbaru</option>
          <option value="price_asc" <?= $sort=='price_asc' ?'selected':'' ?>>Harga Termurah</option>
          <option value="price_desc"<?= $sort=='price_desc'?'selected':'' ?>>Harga Termahal</option>
          <option value="rating"    <?= $sort=='rating'    ?'selected':'' ?>>Rating Tertinggi</option>
        </select>
        <button type="submit" class="btn-gold btn-sm"><i class="fas fa-filter"></i> Filter</button>
        <?php if ($search||$categoryId): ?>
        <a href="<?= APP_URL ?>/products" class="btn-outline-gold btn-sm"><i class="fas fa-times"></i> Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <!-- Result Count -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <p style="color:#777;font-size:.88rem;margin:0;">
        Menampilkan <strong><?= count($products) ?></strong> produk
        <?php if ($search): ?> untuk "<strong><?= htmlspecialchars($search) ?></strong>"<?php endif; ?>
      </p>
    </div>

    <!-- Products Grid -->
    <?php if (empty($products)): ?>
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-search"></i></div>
      <h4>Produk Tidak Ditemukan</h4>
      <p>Coba kata kunci lain atau reset filter pencarian.</p>
      <a href="<?= APP_URL ?>/products" class="btn-gold">Lihat Semua Produk</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($products as $i => $p): ?>
      <div class="col-6 col-md-4 col-xl-3 reveal" style="transition-delay:<?= min($i,7)*0.06 ?>s;">
        <div class="product-card">
          <div class="card-img-wrap">
            <a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>">
              <img src="<?= prodImg($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            </a>
            <div class="card-badges">
              <?php if ($p['is_featured']): ?><span class="badge-gold"><i class="fas fa-star me-1"></i>Unggulan</span><?php endif; ?>
              <?php if ($p['stock'] < 5 && $p['stock'] > 0): ?><span class="badge-new" style="background:#DC3545;">Sisa <?= $p['stock'] ?></span><?php endif; ?>
            </div>
            <?php if (Session::isLoggedIn()): ?>
            <button class="card-wishlist-btn btn-wishlist" data-id="<?= $p['id'] ?>" title="Tambah Wishlist">
              <i class="far fa-heart"></i>
            </button>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <p class="card-category"><?= htmlspecialchars($p['category_name'] ?? '') ?></p>
            <h6 class="card-title"><a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>" style="color:inherit;"><?= htmlspecialchars($p['name']) ?></a></h6>
            <div class="card-rating">
              <span class="stars"><?= renderStar($p['avg_rating']) ?></span>
              <span class="rating-count">(<?= $p['review_count'] ?>)</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div class="card-price"><?= formatRp($p['price']) ?></div>
              <small style="color:<?= $p['stock']>0?'#28A745':'#DC3545' ?>;font-size:.75rem;font-weight:600;">
                <?= $p['stock']>0?"Stok: {$p['stock']}":'Habis' ?>
              </small>
            </div>
          </div>
          <div class="card-footer">
            <a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>" class="btn-outline-gold btn-sm" style="flex:1;justify-content:center;">Detail</a>
            <?php if ($p['stock'] > 0 && Session::isLoggedIn()): ?>
            <button class="btn-gold btn-sm btn-add-cart" data-id="<?= $p['id'] ?>" style="flex:1;justify-content:center;">
              <i class="fas fa-cart-plus"></i>
            </button>
            <?php elseif ($p['stock'] > 0): ?>
            <a href="<?= APP_URL ?>/auth/login" class="btn-gold btn-sm" style="flex:1;justify-content:center;"><i class="fas fa-cart-plus"></i></a>
            <?php else: ?>
            <button class="btn-sm" disabled style="flex:1;background:#F5F5F5;border:none;border-radius:50px;color:#999;">Habis</button>
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
