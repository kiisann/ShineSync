<?php
// app/views/customer/order_history.php
include __DIR__ . '/../layouts/header.php';
$statusLabels = ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Terkirim','cancelled'=>'Dibatalkan'];
?>
<div class="ss-breadcrumb"><div class="container">
  <nav><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
    <li class="breadcrumb-item active">Riwayat Pesanan</li>
  </ol></nav>
</div></div>

<section class="ss-section-sm">
  <div class="container">
    <h2 class="section-title mb-4">Riwayat Pesanan</h2>
    <?php if (empty($orders)): ?>
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-box-open"></i></div>
      <h4>Belum Ada Pesanan</h4>
      <p>Anda belum pernah melakukan pemesanan.</p>
      <a href="<?= APP_URL ?>/products" class="btn-gold">Mulai Belanja</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($orders as $o): ?>
      <div class="col-12">
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 16px rgba(0,0,0,.06);transition:all .3s;" onmouseenter="this.style.boxShadow='0 8px 32px rgba(212,175,55,.15)'" onmouseleave="this.style.boxShadow='0 2px 16px rgba(0,0,0,.06)'">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
              <span style="font-weight:700;font-size:.95rem;color:#1A1A1A;">#<?= htmlspecialchars($o['order_number']) ?></span>
              <span style="font-size:.8rem;color:#999;margin-left:12px;"><i class="fas fa-calendar-alt me-1"></i><?= date('d M Y', strtotime($o['created_at'])) ?></span>
            </div>
            <div class="d-flex gap-2">
              <span class="status-badge status-<?= $o['status'] ?>"><?= $statusLabels[$o['status']]??$o['status'] ?></span>
              <?php if ($o['payment_status']): ?>
              <span class="status-badge status-<?= $o['payment_status'] ?>"><?= ucfirst($o['payment_status']) ?></span>
              <?php endif; ?>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div style="font-size:.88rem;color:#777;">
              <i class="fas fa-box me-1"></i><?= $o['item_count'] ?> item •
              <strong style="color:#D4AF37;font-size:1rem;">Rp <?= number_format($o['grand_total'],0,',','.') ?></strong>
            </div>
            <div class="d-flex gap-2">
              <a href="<?= APP_URL ?>/orders/detail/<?= urlencode($o['order_number']) ?>" class="btn-outline-gold btn-sm">
                <i class="fas fa-eye me-1"></i>Detail
              </a>
              <?php if (!$o['payment_status'] || $o['payment_status'] === 'pending'): ?>
              <a href="<?= APP_URL ?>/orders/payment/<?= urlencode($o['order_number']) ?>" class="btn-gold btn-sm">
                <i class="fas fa-upload me-1"></i>Upload Bukti
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
