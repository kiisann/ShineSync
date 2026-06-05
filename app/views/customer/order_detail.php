<?php
// app/views/customer/order_detail.php
include __DIR__ . '/../layouts/header.php';
function fRpOd($n){return 'Rp '.number_format($n,0,',','.');}
$statusLabels=['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Terkirim','cancelled'=>'Dibatalkan'];
?>
<div class="ss-breadcrumb"><div class="container">
  <nav><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/orders">Pesanan</a></li>
    <li class="breadcrumb-item active">#<?= htmlspecialchars($order['order_number']) ?></li>
  </ol></nav>
</div></div>

<section class="ss-section-sm">
  <div class="container">
    <div class="d-flex align-items-center gap-3 mb-4">
      <h2 class="section-title mb-0">Detail Pesanan</h2>
      <span class="status-badge status-<?= $order['status'] ?>"><?= $statusLabels[$order['status']]??$order['status'] ?></span>
    </div>
    <div class="row g-4">
      <div class="col-lg-8">
        <!-- Items -->
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 16px rgba(0,0,0,.06);margin-bottom:20px;">
          <h6 style="font-weight:700;margin-bottom:16px;"><i class="fas fa-box me-2" style="color:#D4AF37;"></i>Item Pesanan</h6>
          <?php foreach ($order['details'] as $item):
            $img = ($item['image']&&file_exists(UPLOAD_PATH.'products/'.$item['image'])) ? APP_URL.'/uploads/products/'.$item['image'] : APP_URL.'/public/images/no-image.svg';
          ?>
          <div class="d-flex gap-3 align-items-center mb-3 pb-3" style="border-bottom:1px solid #F0F0F0;">
            <img src="<?= $img ?>" style="width:60px;height:60px;border-radius:10px;object-fit:cover;" alt="">
            <div class="flex-grow-1">
              <p style="font-weight:600;margin:0;font-size:.92rem;"><?= htmlspecialchars($item['product_name']) ?></p>
              <p style="color:#777;margin:0;font-size:.82rem;">x<?= $item['quantity'] ?> × <?= fRpOd($item['price']) ?></p>
            </div>
            <strong style="color:#D4AF37;"><?= fRpOd($item['subtotal']) ?></strong>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Shipping -->
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
          <h6 style="font-weight:700;margin-bottom:16px;"><i class="fas fa-truck me-2" style="color:#D4AF37;"></i>Informasi Pengiriman</h6>
          <?php foreach ([['Penerima',$order['shipping_name']],['Telepon',$order['shipping_phone']],['Alamat',$order['shipping_address']],['Catatan',$order['notes']??'-']] as [$k,$v]): ?>
          <div class="row mb-2" style="font-size:.88rem;">
            <div class="col-4 text-muted"><?= $k ?></div>
            <div class="col-8"><strong><?= htmlspecialchars($v) ?></strong></div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Review Form (if delivered & no review yet) -->
        <?php if ($order['status'] === 'delivered' && !empty($order['details'])): ?>
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 16px rgba(0,0,0,.06);margin-top:20px;">
          <h6 style="font-weight:700;margin-bottom:16px;"><i class="fas fa-star me-2" style="color:#D4AF37;"></i>Beri Ulasan</h6>
          <?php foreach ($order['details'] as $item): ?>
          <form method="POST" action="<?= APP_URL ?>/reviews/store" class="mb-3 pb-3" style="border-bottom:1px solid #F0F0F0;">
            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <input type="hidden" name="order_number" value="<?= $order['order_number'] ?>">
            <input type="hidden" name="rating" id="rating-value" value="5">
            <p style="font-weight:600;font-size:.88rem;margin-bottom:8px;"><?= htmlspecialchars($item['product_name']) ?></p>
            <div class="star-rating-input d-flex gap-1 mb-2">
              <?php for ($s=1;$s<=5;$s++): ?>
              <button type="button" class="star-btn" style="background:none;border:none;font-size:1.4rem;color:#D4AF37;cursor:pointer;">★</button>
              <?php endfor; ?>
            </div>
            <textarea name="comment" class="form-control" rows="2" placeholder="Ceritakan pengalaman Anda..." style="font-size:.88rem;"></textarea>
            <button type="submit" class="btn-gold btn-sm mt-2"><i class="fas fa-paper-plane me-1"></i>Kirim Ulasan</button>
          </form>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Summary -->
      <div class="col-lg-4">
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 16px rgba(0,0,0,.06);margin-bottom:16px;">
          <h6 style="font-weight:700;margin-bottom:16px;">Ringkasan Pembayaran</h6>
          <?php foreach ([['Subtotal',fRpOd($order['total_amount'])],['Diskon','−'.fRpOd($order['discount'])],['Ongkir','Gratis']] as [$k,$v]): ?>
          <div class="d-flex justify-content-between mb-2" style="font-size:.88rem;">
            <span style="color:#777;"><?= $k ?></span><span><?= $v ?></span>
          </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between">
            <strong>Grand Total</strong>
            <strong style="color:#D4AF37;font-size:1.1rem;"><?= fRpOd($order['grand_total']) ?></strong>
          </div>
          <?php if ($order['loyalty_points']>0): ?>
          <div style="background:rgba(212,175,55,.06);border-radius:8px;padding:8px 12px;margin-top:12px;font-size:.82rem;">
            <i class="fas fa-star me-1" style="color:#D4AF37;"></i>
            Kamu mendapat <strong><?= $order['loyalty_points'] ?> poin</strong> dari pesanan ini!
          </div>
          <?php endif; ?>
        </div>

        <?php if ($order['payment']): ?>
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
          <h6 style="font-weight:700;margin-bottom:12px;">Status Pembayaran</h6>
          <span class="status-badge status-<?= $order['payment']['status'] ?>">
            <?= ucfirst($order['payment']['status']) ?>
          </span>
          <?php if ($order['payment']['proof_image']): ?>
          <div class="mt-3">
            <p style="font-size:.8rem;color:#777;margin-bottom:6px;">Bukti Transfer:</p>
            <img src="<?= APP_URL ?>/uploads/payments/<?= $order['payment']['proof_image'] ?>" style="width:100%;border-radius:8px;" alt="Bukti Bayar">
          </div>
          <?php elseif ($order['payment']['status'] === 'pending'): ?>
          <div class="mt-3">
            <a href="<?= APP_URL ?>/orders/payment/<?= urlencode($order['order_number']) ?>" class="btn-gold btn-sm d-block text-center">
              <i class="fas fa-upload me-1"></i> Upload Bukti Bayar
            </a>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
