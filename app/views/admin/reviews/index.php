<?php
// app/views/admin/reviews/index.php
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="admin-page-header"><h2>Kelola Review</h2></div>
<div class="admin-card">
  <div class="card-head"><h5><i class="fas fa-star me-2" style="color:#D4AF37;"></i>Semua Review</h5></div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead><tr><th>#</th><th>Customer</th><th>Produk</th><th>Rating</th><th>Komentar</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if(empty($reviews)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#999;">Belum ada review.</td></tr>
        <?php else: foreach($reviews as $i=>$r): ?>
        <tr>
          <td style="color:#999;"><?= $i+1 ?></td>
          <td style="font-weight:600;font-size:.88rem;"><?= htmlspecialchars($r['customer_name']) ?></td>
          <td style="font-size:.85rem;"><?= htmlspecialchars($r['product_name']) ?></td>
          <td>
            <div style="color:#D4AF37;font-size:.9rem;">
              <?php for($s=1;$s<=5;$s++) echo '<i class="'.($s<=$r['rating']?'fas':'far').' fa-star"></i>'; ?>
            </div>
          </td>
          <td style="font-size:.82rem;color:#555;"><?= htmlspecialchars(substr($r['comment'],0,60)) ?>...</td>
          <td style="font-size:.78rem;color:#999;"><?= date('d M Y',strtotime($r['created_at'])) ?></td>
          <td>
            <span class="status-badge" style="<?= $r['is_approved']?'background:rgba(40,167,69,.1);color:#155724;':'background:rgba(220,53,69,.1);color:#721c24;' ?>">
              <?= $r['is_approved']?'Approved':'Hidden' ?>
            </span>
          </td>
          <td>
            <button onclick="toggleReview(<?= $r['id'] ?>,this)" class="btn-action <?= $r['is_approved']?'btn-delete':'btn-verify' ?>" title="Toggle">
              <i class="fas <?= $r['is_approved']?'fa-eye-slash':'fa-eye' ?>"></i>
            </button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function toggleReview(id, btn) {
  fetch('<?= APP_URL ?>/admin/reviews/toggle/'+id, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r=>r.json()).then(d => { if(d.success) { showToast('Status review diperbarui.','success'); setTimeout(()=>location.reload(),800); } });
}
</script>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
