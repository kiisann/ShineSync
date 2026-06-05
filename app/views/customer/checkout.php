<?php
// app/views/customer/checkout.php
include __DIR__ . '/../layouts/header.php';
function fmtRp($n){return 'Rp '.number_format($n,0,',','.');}
$discount = $total >= 1000000 ? $total * 0.1 : 0;
$grand = $total - $discount;
?>
<div class="ss-breadcrumb"><div class="container">
  <nav><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/cart">Keranjang</a></li>
    <li class="breadcrumb-item active">Checkout</li>
  </ol></nav>
</div></div>

<section class="ss-section-sm">
  <div class="container">
    <h2 class="section-title mb-4">Checkout</h2>

    <form method="POST" action="<?= APP_URL ?>/checkout/process">
      <div class="row g-4">
        <!-- Shipping Info -->
        <div class="col-lg-7">
          <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,.06);margin-bottom:24px;">
            <h5 style="font-weight:700;margin-bottom:20px;"><i class="fas fa-map-marker-alt me-2" style="color:#D4AF37;"></i>Informasi Pengiriman</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Penerima</label>
                <input type="text" name="shipping_name" class="form-control" required value="<?= htmlspecialchars($user['name']??'') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="tel" name="shipping_phone" class="form-control" required value="<?= htmlspecialchars($user['phone']??'') ?>">
              </div>
              <div class="col-12">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="shipping_address" class="form-control" rows="3" required placeholder="Jl. nama jalan, No. rumah, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"><?= htmlspecialchars($user['address']??'') ?></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Catatan Pesanan <span style="color:#999;font-size:.8rem;">(opsional)</span></label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Instruksi khusus untuk pengiriman..."></textarea>
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,.06);">
            <h5 style="font-weight:700;margin-bottom:20px;"><i class="fas fa-credit-card me-2" style="color:#D4AF37;"></i>Metode Pembayaran</h5>
            <div class="row g-3">
              <?php foreach ([['transfer','fa-university','Transfer Bank','BCA, Mandiri, BNI, BRI'],['qris','fa-qrcode','QRIS','Bayar via QR Code'],['cod','fa-hand-holding-usd','COD','Bayar di Tempat']] as [$val,$ic,$label,$desc]): ?>
              <div class="col-md-4">
                <label style="cursor:pointer;display:block;">
                  <input type="radio" name="payment_method" value="<?= $val ?>" <?= $val=='transfer'?'checked':'' ?> style="display:none;" onchange="toggleBank(this.value)">
                  <div class="payment-method-card" data-method="<?= $val ?>" style="border:2px solid #E5E5E5;border-radius:12px;padding:16px;text-align:center;transition:all .3s;<?= $val=='transfer'?'border-color:#D4AF37;background:rgba(212,175,55,.05);':'' ?>">
                    <i class="fas <?= $ic ?>" style="font-size:1.4rem;color:#D4AF37;margin-bottom:8px;display:block;"></i>
                    <strong style="font-size:.88rem;display:block;"><?= $label ?></strong>
                    <small style="color:#999;font-size:.75rem;"><?= $desc ?></small>
                  </div>
                </label>
              </div>
              <?php endforeach; ?>
            </div>

            <div id="bank-info" style="margin-top:16px;background:#F8F8F8;border-radius:12px;padding:16px;">
              <p style="font-size:.85rem;font-weight:600;margin-bottom:8px;">Rekening Tujuan:</p>
              <?php foreach ([['BCA','123.456.7890'],['Mandiri','098.765.4321'],['BNI','112.233.4455']] as [$bank,$acc]): ?>
              <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:4px;">
                <span style="color:#777;"><?= $bank ?></span>
                <strong><?= $acc ?> a.n. ShineSync</strong>
              </div>
              <?php endforeach; ?>
              <div class="row g-2 mt-2">
                <div class="col-md-6">
                  <label class="form-label" style="font-size:.8rem;">Bank Anda</label>
                  <input type="text" name="bank_name" class="form-control" placeholder="Nama bank" style="font-size:.88rem;">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-5">
          <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,.06);position:sticky;top:80px;">
            <h5 style="font-weight:700;margin-bottom:20px;">Ringkasan Pesanan</h5>
            <?php foreach ($cartItems as $item):
              $img = ($item['image']&&file_exists(UPLOAD_PATH.'products/'.$item['image'])) ? APP_URL.'/uploads/products/'.$item['image'] : APP_URL.'/public/images/no-image.svg';
            ?>
            <div class="d-flex gap-3 align-items-center mb-3">
              <img src="<?= $img ?>" style="width:52px;height:52px;border-radius:8px;object-fit:cover;" alt="">
              <div class="flex-grow-1">
                <p style="font-size:.85rem;font-weight:600;margin:0;color:#1A1A1A;"><?= htmlspecialchars($item['name']) ?></p>
                <p style="font-size:.8rem;color:#777;margin:0;">x<?= $item['quantity'] ?></p>
              </div>
              <span style="font-weight:700;font-size:.9rem;color:#1A1A1A;"><?= fmtRp($item['quantity']*$item['price']) ?></span>
            </div>
            <?php endforeach; ?>
            <hr>
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;"><span style="color:#777;">Subtotal</span><span style="font-weight:600;"><?= fmtRp($total) ?></span></div>
            <?php if ($discount > 0): ?>
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;"><span style="color:#28A745;">Diskon Member (10%)</span><span style="color:#28A745;font-weight:600;">−<?= fmtRp($discount) ?></span></div>
            <?php endif; ?>
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;"><span style="color:#777;">Ongkir</span><span style="color:#28A745;font-weight:600;">Gratis</span></div>
            <?php $poin = floor($grand / 10000); ?>
            <div class="d-flex justify-content-between mb-3" style="font-size:.85rem;background:rgba(212,175,55,.06);padding:8px 12px;border-radius:8px;">
              <span style="color:#777;"><i class="fas fa-star me-1" style="color:#D4AF37;"></i>Poin Loyalitas</span>
              <span style="color:#D4AF37;font-weight:600;">+<?= $poin ?> poin</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-4">
              <span style="font-weight:700;">Grand Total</span>
              <span style="font-weight:800;font-size:1.3rem;color:#D4AF37;"><?= fmtRp($grand) ?></span>
            </div>
            <button type="submit" class="btn-gold d-block w-100" style="justify-content:center;padding:15px;border-radius:12px;font-size:.95rem;">
              <i class="fas fa-shield-alt me-2"></i>Konfirmasi Pesanan
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>

<script>
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
  radio.addEventListener('change', function() {
    document.querySelectorAll('.payment-method-card').forEach(c => {
      c.style.borderColor = '#E5E5E5';
      c.style.background = '';
    });
    const card = document.querySelector(`.payment-method-card[data-method="${this.value}"]`);
    if (card) { card.style.borderColor = '#D4AF37'; card.style.background = 'rgba(212,175,55,.05)'; }
    document.getElementById('bank-info').style.display = this.value === 'transfer' ? 'block' : 'none';
  });
});
function toggleBank(v) { document.getElementById('bank-info').style.display = v==='transfer'?'block':'none'; }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
