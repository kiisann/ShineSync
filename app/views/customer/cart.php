<?php
include __DIR__ . '/../layouts/header.php';
function fRp($n){return 'Rp '.number_format($n,0,',','.');}
?>
<div class="ss-breadcrumb"><div class="container">
  <nav><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
    <li class="breadcrumb-item active">Keranjang</li>
  </ol></nav>
</div></div>

<section class="ss-section-sm">
  <div class="container">
    <h2 class="section-title mb-4">Keranjang Belanja</h2>
    <?php if (empty($cartItems)): ?>
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-shopping-bag"></i></div>
      <h4>Keranjang Kosong</h4>
      <p>Anda belum menambahkan produk. Yuk mulai belanja!</p>
      <a href="<?= APP_URL ?>/products" class="btn-gold"><i class="fas fa-gem me-2"></i>Mulai Belanja</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <div class="col-lg-8">
        <?php foreach ($cartItems as $item):
          $img = ($item['image']&&file_exists(UPLOAD_PATH.'products/'.$item['image'])) ? APP_URL.'/uploads/products/'.$item['image'] : APP_URL.'/public/images/no-image.svg';
        ?>
        <div class="cart-item" data-price="<?= $item['price'] ?>">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img">
          <div class="flex-grow-1">
            <h6 style="font-weight:600;margin-bottom:4px;"><?= htmlspecialchars($item['name']) ?></h6>
            <p style="color:#D4AF37;font-weight:600;font-size:.9rem;margin-bottom:8px;"><?= fRp($item['price']) ?></p>
            <div class="d-flex align-items-center gap-3 flex-wrap">
              <div class="qty-control">
                <button class="qty-dec" type="button">-</button>
                <input type="number" class="cart-qty-input qty-input" data-id="<?= $item['id'] ?>" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>">
                <button class="qty-inc" type="button">+</button>
              </div>
              <span class="item-subtotal" style="font-weight:700;color:#1A1A1A;"><?= fRp($item['quantity']*$item['price']) ?></span>
            </div>
          </div>
          <button class="btn-remove-cart" data-id="<?= $item['id'] ?>" style="background:rgba(220,53,69,.08);border:none;border-radius:8px;padding:8px 12px;color:#DC3545;cursor:pointer;transition:all .3s;">
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-4">
        <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,.08);position:sticky;top:80px;">
          <h5 style="font-weight:700;margin-bottom:20px;">Ringkasan Pesanan</h5>
          <div style="border-top:1px solid #F0F0F0;padding-top:16px;">
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
              <span style="color:#777;">Subtotal</span>
              <span id="cart-total" style="font-weight:600;"><?= fRp($total) ?></span>
            </div>
            <?php if ($total >= 1000000): ?>
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
              <span style="color:#28A745;">Diskon Member (10%)</span>
              <span style="color:#28A745;font-weight:600;">−<?= fRp($total*0.1) ?></span>
            </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
              <span style="color:#777;">Ongkir</span>
              <span style="color:#28A745;font-weight:600;">Gratis</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-4">
              <span style="font-weight:700;">Total</span>
              <span style="font-weight:800;font-size:1.2rem;color:#D4AF37;"><?= fRp($total >= 1000000 ? $total*0.9 : $total) ?></span>
            </div>
            <a href="<?= APP_URL ?>/checkout" class="btn-gold d-block text-center" style="border-radius:12px;padding:14px;">
              <i class="fas fa-lock me-2"></i>Lanjut Checkout
            </a>
            <a href="<?= APP_URL ?>/products" class="btn-outline-gold d-block text-center mt-3" style="border-radius:12px;padding:12px;">
              <i class="fas fa-gem me-2"></i>Lanjut Belanja
            </a>
          </div>
          <!-- Benefits -->
          <div class="mt-4" style="font-size:.8rem;color:#777;">
            <?php foreach ([['fas fa-shield-alt','Pembayaran Aman & Terenkripsi'],['fas fa-truck','Pengiriman ke Seluruh Indonesia'],['fas fa-undo','Garansi Uang Kembali 30 Hari']] as [$ic,$lb]): ?>
            <div class="d-flex align-items-center gap-2 mb-2">
              <i class="<?= $ic ?>" style="color:#D4AF37;width:14px;"></i> <?= $lb ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
