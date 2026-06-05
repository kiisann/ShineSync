<?php
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="admin-page-header"><h2>Verifikasi Pembayaran</h2></div>

<div class="admin-card">
  <div class="card-head"><h5><i class="fas fa-credit-card me-2" style="color:#D4AF37;"></i>Semua Pembayaran</h5></div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead><tr><th>#</th><th>No. Order</th><th>Customer</th><th>Jumlah</th><th>Metode</th><th>Bukti</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if(empty($payments)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#999;">Belum ada data pembayaran.</td></tr>
        <?php else: foreach($payments as $i=>$p): ?>
        <tr>
          <td style="color:#999;"><?= $i+1 ?></td>
          <td style="font-weight:600;font-size:.85rem;">#<?= htmlspecialchars($p['order_number']) ?></td>
          <td>
            <div style="font-weight:600;font-size:.88rem;"><?= htmlspecialchars($p['customer_name']) ?></div>
          </td>
          <td style="font-weight:700;color:#D4AF37;">Rp <?= number_format($p['amount'],0,',','.') ?></td>
          <td style="text-transform:uppercase;font-size:.78rem;"><?= $p['method'] ?></td>
          <td>
            <?php if($p['proof_image']): ?>
            <a href="<?= APP_URL ?>/uploads/payments/<?= $p['proof_image'] ?>" target="_blank" style="color:#D4AF37;font-size:.82rem;">
              <i class="fas fa-image me-1"></i>Lihat Bukti
            </a>
            <?php else: ?>
            <span style="color:#999;font-size:.8rem;">Belum upload</span>
            <?php endif; ?>
          </td>
          <td><span class="status-badge status-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
          <td>
            <?php if($p['status']==='pending' && $p['proof_image']): ?>
            <div class="action-btns">
              <button onclick="verifyPayment(<?= $p['id'] ?>)" class="btn-action btn-verify" title="Verifikasi"><i class="fas fa-check"></i></button>
              <button onclick="rejectPayment(<?= $p['id'] ?>)" class="btn-action btn-delete" title="Tolak"><i class="fas fa-times"></i></button>
            </div>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function verifyPayment(id) {
  if (!confirm('Verifikasi pembayaran ini?')) return;
  const form = new FormData();
  form.append('payment_id', id);
  fetch('<?= APP_URL ?>/admin/payments/verify', { method:'POST', body:form, headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r=>r.json()).then(d => { if(d.success){showToast('Pembayaran diverifikasi!','success');setTimeout(()=>location.reload(),1000);} });
}
function rejectPayment(id) {
  const notes = prompt('Alasan penolakan (opsional):') || '';
  const form = new FormData();
  form.append('payment_id', id);
  form.append('notes', notes);
  fetch('<?= APP_URL ?>/admin/payments/reject', { method:'POST', body:form, headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r=>r.json()).then(d => { if(d.success){showToast('Pembayaran ditolak.','error');setTimeout(()=>location.reload(),1000);} });
}
</script>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
