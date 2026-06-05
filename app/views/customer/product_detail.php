<?php
// app/views/customer/product_detail.php
include __DIR__ . '/../layouts/header.php';
function formatRp2($n) { return 'Rp ' . number_format($n,0,',','.'); }
function renderStar2($avg) { $f=round($avg); $o=''; for($i=1;$i<=5;$i++) $o.='<i class="'.($i<=$f?'fas':'far').' fa-star"></i>'; return $o; }
$img = ($product['image']&&file_exists(UPLOAD_PATH.'products/'.$product['image'])) ? APP_URL.'/uploads/products/'.$product['image'] : APP_URL.'/public/images/no-image.svg';
?>

<div class="ss-breadcrumb">
  <div class="container">
    <nav><ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
      <li class="breadcrumb-item"><a href="<?= APP_URL ?>/products">Produk</a></li>
      <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
    </ol></nav>
  </div>
</div>

<section class="ss-section">
  <div class="container">
    <div class="row g-5">
      <!-- Product Image -->
      <div class="col-lg-5">
        <div style="position:sticky;top:80px;">
          <div style="border-radius:20px;overflow:hidden;background:#F8F8F8;aspect-ratio:1;">
            <img id="main-img" src="<?= $img ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                 style="width:100%;height:100%;object-fit:cover;transition:all 0.3s ease;">
          </div>
        </div>
      </div>

      <!-- Product Info -->
      <div class="col-lg-7">
        <p class="text-gold fw-600 mb-2" style="font-size:.82rem;letter-spacing:1.5px;text-transform:uppercase;">
          <?= htmlspecialchars($product['category_name'] ?? '') ?>
        </p>
        <h1 style="font-size:1.8rem;font-weight:700;color:#1A1A1A;margin-bottom:12px;"><?= htmlspecialchars($product['name']) ?></h1>

        <!-- Rating -->
        <div class="d-flex align-items-center gap-3 mb-4">
          <div class="stars" style="font-size:1rem;"><?= renderStar2($product['avg_rating']) ?></div>
          <span style="font-size:.88rem;color:#777;"><?= number_format($product['avg_rating'],1) ?> (<?= $product['review_count'] ?> ulasan)</span>
          <?php if ($product['stock'] > 0): ?>
          <span style="background:rgba(40,167,69,.1);color:#155724;font-size:.75rem;font-weight:600;padding:4px 12px;border-radius:20px;">Tersedia</span>
          <?php else: ?>
          <span style="background:rgba(220,53,69,.1);color:#721c24;font-size:.75rem;font-weight:600;padding:4px 12px;border-radius:20px;">Habis</span>
          <?php endif; ?>
        </div>

        <!-- Price -->
        <div style="margin-bottom:24px;">
          <span style="font-size:2.2rem;font-weight:800;color:#D4AF37;"><?= formatRp2($product['price']) ?></span>
          <?php if ($product['price'] >= 1000000): ?>
          <span style="margin-left:12px;font-size:.82rem;color:#28A745;font-weight:600;">
            <i class="fas fa-tag me-1"></i>Diskon 10% untuk member (≥ Rp 1jt)
          </span>
          <?php endif; ?>
        </div>

        <!-- Specs -->
        <div style="background:#F8F8F8;border-radius:12px;padding:20px;margin-bottom:24px;">
          <div class="row g-3" style="font-size:.88rem;">
            <?php foreach ([['Material',$product['material']??'-'],['Berat',($product['weight']??0).' gram'],['Stok',$product['stock'].' pcs'],['SKU','SS-'.str_pad($product['id'],4,'0',STR_PAD_LEFT)]] as [$k,$v]): ?>
            <div class="col-6">
              <span style="color:#777;display:block;font-size:.78rem;"><?= $k ?></span>
              <strong style="color:#1A1A1A;"><?= htmlspecialchars((string)$v) ?></strong>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Description -->
        <div style="margin-bottom:28px;">
          <h6 style="font-weight:600;margin-bottom:8px;font-size:.9rem;">Deskripsi Produk</h6>
          <p style="color:#555;font-size:.9rem;line-height:1.8;"><?= nl2br(htmlspecialchars($product['description']??'')) ?></p>
        </div>

        <!-- Actions -->
        <?php if ($product['stock'] > 0): ?>
        <div class="d-flex gap-3 align-items-center flex-wrap">
          <div class="qty-control">
            <button type="button" class="qty-dec" onclick="changeQty(-1)">−</button>
            <input type="number" id="qty-input" class="qty-input" value="1" min="1" max="<?= $product['stock'] ?>">
            <button type="button" class="qty-inc" onclick="changeQty(1)">+</button>
          </div>
          <?php if (Session::isLoggedIn()): ?>
          <button class="btn-gold btn-add-cart flex-grow-1" data-id="<?= $product['id'] ?>" style="justify-content:center;max-width:220px;">
            <i class="fas fa-shopping-bag"></i> Tambah ke Keranjang
          </button>
          <button class="btn-outline-gold btn-wishlist <?= $inWishlist?'active':'' ?>" data-id="<?= $product['id'] ?>">
            <i class="<?= $inWishlist?'fas':'far' ?> fa-heart"></i>
          </button>
          <?php else: ?>
          <a href="<?= APP_URL ?>/auth/login" class="btn-gold flex-grow-1" style="justify-content:center;max-width:220px;">
            <i class="fas fa-sign-in-alt"></i> Login untuk Beli
          </a>
          <?php endif; ?>
        </div>
        <?php else: ?>
        <div style="background:rgba(220,53,69,.08);border:1px dashed rgba(220,53,69,.3);border-radius:12px;padding:16px;text-align:center;color:#721c24;">
          <i class="fas fa-times-circle me-2"></i> Produk sedang habis stok
        </div>
        <?php endif; ?>

        <!-- Features -->
        <div class="d-flex gap-4 mt-4 flex-wrap">
          <?php foreach ([['fas fa-shield-alt','Asli & Tersertifikasi'],['fas fa-truck','Gratis Ongkir'],['fas fa-undo','Garansi 30 Hari']] as [$ic,$lb]): ?>
          <div class="d-flex align-items-center gap-2" style="font-size:.8rem;color:#555;">
            <i class="<?= $ic ?>" style="color:#D4AF37;"></i> <?= $lb ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
      <div class="col-12">
        <h3 style="font-weight:700;margin-bottom:8px;">Ulasan Pelanggan</h3>
        <div class="gold-line"></div>
      </div>

      <?php if (!empty($product['reviews'])): ?>
      <?php foreach ($product['reviews'] as $r): ?>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="review-card">
          <div class="review-quote">"</div>
          <p class="review-text"><?= htmlspecialchars($r['comment']) ?></p>
          <div class="d-flex align-items-center gap-3 mt-3">
            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#B8960C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;">
              <?= strtoupper(substr($r['reviewer_name'],0,1)) ?>
            </div>
            <div>
              <div class="reviewer-name"><?= htmlspecialchars($r['reviewer_name']) ?></div>
              <div class="stars" style="font-size:.8rem;"><?= renderStar2($r['rating']) ?></div>
              <div style="font-size:.75rem;color:#999;"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <div class="col-12">
        <div style="text-align:center;padding:40px;color:#999;">
          <i class="far fa-comment-dots" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
          Belum ada ulasan untuk produk ini.
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
function changeQty(delta) {
  const inp = document.getElementById('qty-input');
  const max = parseInt(inp.max), min = parseInt(inp.min) || 1;
  let val = parseInt(inp.value) + delta;
  inp.value = Math.max(min, Math.min(max, val));
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
