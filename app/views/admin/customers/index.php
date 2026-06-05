<?php
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="admin-page-header"><h2>Data Customer</h2></div>
<div class="admin-card">
  <div class="card-head"><h5><i class="fas fa-users me-2" style="color:#D4AF37;"></i>Semua Customer</h5></div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Bergabung</th><th>Total Order</th><th>Total Belanja</th><th>Status</th></tr></thead>
      <tbody>
        <?php if(empty($customers)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#999;">Belum ada customer.</td></tr>
        <?php else: foreach($customers as $i=>$c): ?>
        <tr>
          <td style="color:#999;"><?= $i+1 ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#B8960C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.82rem;flex-shrink:0;">
                <?= strtoupper(substr($c['name'],0,1)) ?>
              </div>
              <span style="font-weight:600;font-size:.88rem;"><?= htmlspecialchars($c['name']) ?></span>
            </div>
          </td>
          <td style="font-size:.82rem;color:#777;"><?= htmlspecialchars($c['email']) ?></td>
          <td style="font-size:.82rem;"><?= htmlspecialchars($c['phone']??'-') ?></td>
          <td style="font-size:.82rem;color:#777;"><?= date('d M Y',strtotime($c['created_at'])) ?></td>
          <td style="font-weight:700;"><?= $c['total_orders'] ?></td>
          <td style="font-weight:700;color:#D4AF37;">Rp <?= number_format($c['total_spent'],0,',','.') ?></td>
          <td><span class="status-badge" style="<?= $c['is_active']?'background:rgba(40,167,69,.1);color:#155724;':'background:rgba(220,53,69,.1);color:#721c24;' ?>"><?= $c['is_active']?'Aktif':'Nonaktif' ?></span></td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
