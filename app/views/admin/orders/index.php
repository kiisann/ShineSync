<?php
// app/views/admin/orders/index.php
include __DIR__ . '/../../layouts/admin_header.php';
$statusLabels=['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Terkirim','cancelled'=>'Dibatalkan'];
?>
<div class="admin-page-header">
  <h2>Kelola Pesanan</h2>
</div>
<div class="admin-card">
  <div class="card-head"><h5><i class="fas fa-shopping-bag me-2" style="color:#D4AF37;"></i>Semua Pesanan</h5></div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead><tr><th>#</th><th>No. Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Pembayaran</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if(empty($orders)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#999;">Belum ada pesanan.</td></tr>
        <?php else: foreach($orders as $i=>$o): ?>
        <tr>
          <td style="color:#999;"><?= $i+1 ?></td>
          <td style="font-weight:600;font-size:.85rem;">#<?= htmlspecialchars($o['order_number']) ?></td>
          <td>
            <div style="font-weight:600;font-size:.88rem;"><?= htmlspecialchars($o['customer_name']) ?></div>
            <div style="font-size:.75rem;color:#999;"><?= htmlspecialchars($o['email']) ?></div>
          </td>
          <td style="font-weight:700;color:#D4AF37;">Rp <?= number_format($o['grand_total'],0,',','.') ?></td>
          <td>
            <select onchange="updateStatus(<?= $o['id'] ?>,this.value)" class="form-select" style="width:130px;font-size:.8rem;border-radius:8px;padding:6px 8px;">
              <?php foreach($statusLabels as $v=>$l): ?>
              <option value="<?= $v ?>" <?= $o['status']===$v?'selected':'' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td>
            <?php if($o['payment_status']): ?>
            <span class="status-badge status-<?= $o['payment_status'] ?>"><?= ucfirst($o['payment_status']) ?></span>
            <?php else: ?>
            <span style="color:#999;font-size:.8rem;">-</span>
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

<script>
function updateStatus(orderId, status) {
  const form = new FormData();
  form.append('order_id', orderId);
  form.append('status', status);
  fetch('<?= APP_URL ?>/admin/orders/update-status', {
    method: 'POST', body: form,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  }).then(r=>r.json()).then(d => {
    if (d.success) showToast('Status pesanan diperbarui.', 'success');
  });
}
</script>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
