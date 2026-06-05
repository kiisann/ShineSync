<?php
// app/views/customer/checkout_success.php
include __DIR__ . '/../layouts/header.php';
function fRp3($n){return 'Rp '.number_format($n,0,',','.');}
?>
<section class="ss-section">
  <div class="container">
    <div style="max-width:600px;margin:0 auto;text-align:center;">
      <div style="width:80px;height:80px;background:linear-gradient(135deg,rgba(40,167,69,.15),rgba(40,167,69,.05));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:2rem;color:#28A745;">
        <i class="fas fa-check-circle"></i>
      </div>
      <h2 style="font-weight:800;color:#1A1A1A;margin-bottom:8px;">Pesanan Berhasil Dibuat!</h2>
      <p style="color:#777;margin-bottom:32px;">
        COMMIT berhasil. Semua data pesanan telah tersimpan ke database.
      </p>

      <div style="background:#F8F8F8;border-radius:16px;padding:24px;text-align:left;margin-bottom:24px;">
        <h6 style="font-weight:700;margin-bottom:16px;">Detail Pesanan</h6>
        <?php foreach ([
          ['Nomor Pesanan',$order['order_number']],
          ['Total'         ,fRp3($order['grand_total'])],
          ['Status'        ,ucfirst($order['status'])],
          ['Poin Loyalitas','+'.$order['loyalty_points'].' poin'],
        ] as [$k,$v]): ?>
        <div class="d-flex justify-content-between mb-2" style="font-size:.88rem;">
          <span style="color:#777;"><?= $k ?></span>
          <strong style="color:#1A1A1A;"><?= htmlspecialchars($v) ?></strong>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="alert-gold mb-4" style="text-align:left;">
        <i class="fas fa-info-circle"></i>
        <div style="font-size:.85rem;">
          Silakan upload <strong>bukti pembayaran</strong> agar pesanan segera diproses admin.
        </div>
      </div>

      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= APP_URL ?>/orders/payment/<?= urlencode($order['order_number']) ?>" class="btn-gold">
          <i class="fas fa-upload"></i> Upload Bukti Bayar
        </a>
        <a href="<?= APP_URL ?>/orders" class="btn-outline-gold">
          <i class="fas fa-box"></i> Lihat Pesanan
        </a>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
