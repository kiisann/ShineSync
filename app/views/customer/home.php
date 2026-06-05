<?php
// app/views/customer/home.php
include __DIR__ . '/../layouts/header.php';

function formatRupiah($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
function renderStars($avg) {
    $filled = round($avg);
    $out = '';
    for ($i=1;$i<=5;$i++) $out .= '<i class="' . ($i<=$filled?'fas':'far') . ' fa-star"></i>';
    return $out;
}
function productImg($img) {
    if ($img && file_exists(UPLOAD_PATH . 'products/' . $img)) return APP_URL . '/uploads/products/' . $img;
    return APP_URL . '/public/images/no-image.svg';
}
?>

<!-- Hero -->
<section class="hero-section">
  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center" style="min-height:90vh;">
      <div class="col-lg-6">
        <div class="mb-3">
          <span style="display:inline-flex;align-items:center;gap:8px;background:rgba(212,175,55,0.15);border:1px solid rgba(212,175,55,0.3);border-radius:50px;padding:6px 16px;font-size:.8rem;color:#D4AF37;font-weight:600;">
            <i class="fas fa-crown"></i> Koleksi Premium 2025
          </span>
        </div>
        <h1 class="hero-title reveal">
          Perhiasan yang <span class="highlight">Memancarkan</span> Kemewahan Anda
        </h1>
        <p class="hero-subtitle reveal mt-4">Koleksi perhiasan emas, berlian, dan mutiara pilihan terbaik. Setiap karya adalah cerminan keanggunan dan prestige.</p>
        <div class="d-flex gap-3 flex-wrap mt-4 reveal">
          <a href="<?= APP_URL ?>/products" class="btn-gold btn-lg">
            <i class="fas fa-gem"></i> Jelajahi Koleksi
          </a>
          <a href="#bestsellers" class="btn-outline-gold btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.4);">
            <i class="fas fa-star"></i> Produk Terlaris
          </a>
        </div>
        <!-- Stats -->
        <div class="d-flex gap-5 mt-5 reveal">
          <?php foreach ([['500+','Produk'],['10K+','Customer'],['4.9','Rating'],['5th','Tahun']] as [$v,$l]): ?>
          <div>
            <div style="font-size:1.6rem;font-weight:800;color:#D4AF37;"><?= $v ?></div>
            <div style="font-size:.78rem;color:rgba(255,255,255,.5);"><?= $l ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
        <!-- Decorative visual -->
        <div style="position:relative;width:460px;height:460px;">
          <div style="position:absolute;inset:0;border-radius:50%;background:radial-gradient(circle,rgba(212,175,55,0.15),transparent 70%);animation:heroGlow 6s ease-in-out infinite;"></div>
          <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:280px;height:280px;border-radius:50%;border:2px solid rgba(212,175,55,0.25);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-gem" style="font-size:7rem;color:rgba(212,175,55,0.4);"></i>
          </div>
          <!-- Floating cards -->
          <?php $floatItems = [
            ['top:15%','left:0','💍','Cincin'],
            ['top:70%','left:5%','📿','Kalung'],
            ['top:20%','right:0','✨','Gelang'],
            ['top:70%','right:5%','💎','Anting'],
          ]; foreach ($floatItems as [$t,$p,$e,$n]): ?>
          <div style="position:absolute;<?= $t ?>;<?= $p ?>;background:rgba(255,255,255,0.08);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.15);border-radius:16px;padding:12px 18px;text-align:center;animation:float 3s ease-in-out infinite;animation-delay:<?= rand(0,2) ?>s;">
            <div style="font-size:1.5rem;"><?= $e ?></div>
            <div style="font-size:.72rem;color:rgba(255,255,255,.7);margin-top:4px;"><?= $n ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="hero-scroll-indicator"><i class="fas fa-chevron-down fa-2x"></i></div>
</section>

<!-- Categories -->
<section class="ss-section bg-light-gold">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-gold fw-600 mb-1" style="font-size:.85rem;letter-spacing:2px;text-transform:uppercase;font-weight:600;">Kategori</p>
      <h2 class="section-title">Jelajahi Koleksi Kami</h2>
      <div class="gold-line center"></div>
    </div>
    <div class="row g-4 justify-content-center">
      <?php
      $catIcons = ['Cincin'=>'💍','Kalung'=>'📿','Gelang'=>'✨','Anting'=>'💎','Aksesoris'=>'👑'];
      foreach ($categories as $cat):
        $icon = $catIcons[$cat['name']] ?? '💍';
      ?>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="<?= APP_URL ?>/products?category=<?= $cat['id'] ?>" class="text-decoration-none">
          <div class="category-card reveal">
            <div class="cat-icon"><?= $icon ?></div>
            <h6><?= htmlspecialchars($cat['name']) ?></h6>
            <small><?= $cat['product_count'] ?> produk</small>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="ss-section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-5">
      <div>
        <p class="text-gold mb-1" style="font-size:.85rem;letter-spacing:2px;text-transform:uppercase;font-weight:600;">Koleksi Unggulan</p>
        <h2 class="section-title mb-0">Produk Terbaru</h2>
        <div class="gold-line" style="margin-bottom:0;"></div>
      </div>
      <a href="<?= APP_URL ?>/products" class="btn-outline-gold btn-sm">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4">
      <?php foreach ($featured as $i => $p): ?>
      <div class="col-6 col-md-4 col-xl-3 reveal" style="transition-delay:<?= $i * 0.08 ?>s;">
        <div class="product-card">
          <div class="card-img-wrap">
            <img src="<?= productImg($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            <div class="card-badges">
              <?php if ($p['is_featured']): ?><span class="badge-gold"><i class="fas fa-crown me-1"></i>Unggulan</span><?php endif; ?>
            </div>
            <?php if (Session::isLoggedIn()): ?>
            <button class="card-wishlist-btn btn-wishlist" data-id="<?= $p['id'] ?>">
              <i class="far fa-heart"></i>
            </button>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <p class="card-category"><?= htmlspecialchars($p['category_name'] ?? '') ?></p>
            <h6 class="card-title"><?= htmlspecialchars($p['name']) ?></h6>
            <div class="card-rating">
              <span class="stars"><?= renderStars($p['avg_rating']) ?></span>
              <span class="rating-count">(<?= $p['review_count'] ?? 0 ?>)</span>
            </div>
            <div class="card-price"><?= formatRupiah($p['price']) ?></div>
          </div>
          <div class="card-footer">
            <a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>" class="btn-outline-gold btn-sm flex-fill" style="justify-content:center;">
              Detail
            </a>
            <?php if ($p['stock'] > 0): ?>
            <button class="btn-gold btn-sm btn-add-cart flex-fill" data-id="<?= $p['id'] ?>" style="justify-content:center;">
              <i class="fas fa-bag-shopping"></i>
            </button>
            <?php else: ?>
            <button class="btn-sm flex-fill" disabled style="background:#F5F5F5;border:none;border-radius:50px;color:#999;">Habis</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Bestsellers -->
<section class="ss-section bg-light-gold" id="bestsellers">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Produk Terlaris</h2>
      <div class="gold-line center"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($bestsellers as $i => $p): ?>
      <div class="col-6 col-md-4 col-xl-2 reveal" style="transition-delay:<?= $i * 0.08 ?>s;">
        <div class="product-card">
          <div class="card-img-wrap">
            <img src="<?= productImg($p['image'] ?? '') ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" loading="lazy">
            <div class="card-badges">
              <span class="badge-gold">#<?= $i+1 ?> Terlaris</span>
            </div>
          </div>
          <div class="card-body">
            <p class="card-category"><?= htmlspecialchars($p['kategori'] ?? '') ?></p>
            <h6 class="card-title"><?= htmlspecialchars($p['nama_produk']) ?></h6>
            <div class="card-rating">
              <span class="stars"><?= renderStars($p['rata_rating']) ?></span>
              <span class="rating-count">(<?= $p['jumlah_review'] ?>)</span>
            </div>
            <div class="card-price"><?= formatRupiah($p['price']) ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; if (empty($bestsellers)): ?>
      <div class="col-12 text-center">
        <p class="text-muted">Belum ada data produk terlaris.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="ss-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Mengapa ShineSync?</h2>
      <div class="gold-line center"></div>
    </div>
    <div class="row g-4">
      <?php foreach ([
        ['fas fa-certificate','Keaslian Terjamin','Setiap produk disertai sertifikat keaslian dari laboratorium gemologi bersertifikat.'],
        ['fas fa-shipping-fast','Pengiriman Aman','Kemasan premium dengan asuransi pengiriman untuk setiap pesanan.'],
        ['fas fa-undo-alt','Garansi 30 Hari','Tidak puas? Kembalikan dalam 30 hari, kami jamin kepuasan Anda.'],
        ['fas fa-headset','Layanan 24/7','Tim konsultan perhiasan siap membantu Anda kapan saja.'],
      ] as [$icon,$title,$desc]): ?>
      <div class="col-md-6 col-lg-3 reveal">
        <div style="text-align:center;padding:32px 24px;">
          <div style="width:72px;height:72px;background:linear-gradient(135deg,rgba(212,175,55,0.12),rgba(212,175,55,0.05));border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:1.8rem;color:#D4AF37;">
            <i class="<?= $icon ?>"></i>
          </div>
          <h5 style="font-weight:700;font-size:1rem;margin-bottom:10px;"><?= $title ?></h5>
          <p style="color:#777;font-size:.88rem;line-height:1.7;"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Reviews -->
<section class="ss-section" style="background:#F8F8F8;">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-gold mb-1" style="font-size:.85rem;letter-spacing:2px;text-transform:uppercase;font-weight:600;">Testimoni</p>
      <h2 class="section-title">Kata Pelanggan Kami</h2>
      <div class="gold-line center"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($reviews as $r): ?>
      <div class="col-md-6 col-lg-4 reveal">
        <div class="review-card">
          <div class="review-quote">"</div>
          <p class="review-text"><?= htmlspecialchars($r['comment']) ?></p>
          <div class="d-flex align-items-center gap-3 mt-3">
            <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#B8960C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">
              <?= strtoupper(substr($r['reviewer_name'],0,1)) ?>
            </div>
            <div>
              <div class="reviewer-name"><?= htmlspecialchars($r['reviewer_name']) ?></div>
              <div class="reviewer-product"><i class="fas fa-shopping-bag me-1" style="color:#D4AF37;font-size:.7rem;"></i><?= htmlspecialchars($r['product_name']) ?></div>
              <div class="stars" style="font-size:.75rem;"><?= renderStars($r['rating']) ?></div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA Banner -->
<section style="background:linear-gradient(135deg,#1A1A1A,#2D2D2D);padding:80px 0;">
  <div class="container text-center">
    <div class="reveal">
      <div style="font-size:2.5rem;margin-bottom:16px;">💍</div>
      <h2 style="color:#fff;font-size:2rem;font-weight:800;margin-bottom:12px;">Temukan Perhiasan Impian Anda</h2>
      <p style="color:rgba(255,255,255,.6);max-width:500px;margin:0 auto 32px;font-size:.95rem;">Lebih dari 500 koleksi perhiasan premium menunggu Anda. Mulai belanja sekarang!</p>
      <a href="<?= APP_URL ?>/products" class="btn-gold btn-lg">
        <i class="fas fa-gem"></i> Belanja Sekarang
      </a>
    </div>
  </div>
</section>

<style>
@keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-12px); } }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
