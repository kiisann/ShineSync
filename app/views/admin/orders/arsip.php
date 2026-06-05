<?php
// app/views/admin/orders/arsip.php
include __DIR__ . '/../../layouts/admin_header.php';
$statusLabels=['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Terkirim','cancelled'=>'Dibatalkan'];
?>
<div class="admin-page-header">
  <h2>Kelola Pesanan</h2>
</div>

<nav class="order-tabs">
  <a href="<?= APP_URL ?>/admin/orders" class="order-tab"><i class="fas fa-list me-1"></i> Semua Pesanan</a>
  <a href="<?= APP_URL ?>/admin/orders/aktif" class="order-tab"><i class="fas fa-fire me-1"></i> Order Aktif</a>
  <a href="<?= APP_URL ?>/admin/orders/arsip" class="order-tab active"><i class="fas fa-archive me-1"></i> Order Arsip</a>
</nav>

<div class="admin-card">
  <div class="card-head">
    <h5>
      <i class="fas fa-archive me-2" style="color:#D4AF37;"></i>Order Arsip
      <span class="tab-count muted"><?= count($orders) ?> pesanan</span>
    </h5>
    <p style="font-size:.82rem;color:#999;margin:4px 0 0;">Pesanan yang sudah selesai (delivered) atau dibatalkan (cancelled).</p>
  </div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead><tr><th>#</th><th>No. Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Pembayaran</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if(empty($orders)): ?>
        <tr><td colspan="8"><div class="empty-state"><i class="fas fa-box-open"></i>Belum ada order yang diarsipkan.</div></td></tr>
        <?php else: foreach($orders as $i=>$o): ?>
        <tr class="row-arsip">
          <td style="color:#999;"><?= $i+1 ?></td>
          <td style="font-weight:600;font-size:.85rem;">#<?= htmlspecialchars($o['order_number']) ?></td>
          <td>
            <div style="font-weight:600;font-size:.88rem;"><?= htmlspecialchars($o['customer_name']) ?></div>
            <div style="font-size:.75rem;color:#999;"><?= htmlspecialchars($o['email']) ?></div>
          </td>
          <td style="font-weight:700;color:#D4AF37;">Rp <?= number_format($o['grand_total'],0,',','.') ?></td>
          <td><span class="order-status-badge <?= $o['status'] ?>"><?= $statusLabels[$o['status']] ?? ucfirst($o['status']) ?></span></td>
          <td>
            <?php if($o['payment_status']): ?>
            <span class="status-badge status-<?= $o['payment_status'] ?>"><?= ucfirst($o['payment_status']) ?></span>
            <?php else: ?><span style="color:#999;font-size:.8rem;">-</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.82rem;color:#777;"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
          <td>
            <a href="<?= APP_URL ?>/admin/orders/detail/<?= $o['id'] ?>" class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
