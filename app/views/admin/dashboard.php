<?php
// app/views/admin/dashboard.php
include __DIR__ . '/../layouts/admin_header.php';
function fmtK($n){if($n>=1000000)return 'Rp '.round($n/1000000,1).'jt';if($n>=1000)return 'Rp '.round($n/1000).'rb';return 'Rp '.$n;}
?>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
  <?php
  $stats = [
    ['Total Produk',  $totalProducts,  'fas fa-gem',          'gold',    'Produk aktif (COUNT)'],
    ['Total Customer',$totalCustomers, 'fas fa-users',        'success', 'Pengguna terdaftar (COUNT)'],
    ['Total Pesanan', $totalOrders,    'fas fa-shopping-bag', 'info',    'Semua pesanan (COUNT)'],
    ['Total Pendapatan', 'Rp ' . number_format($totalRevenue,0,',','.'), 'fas fa-coins', 'danger', 'Pembayaran verified (SUM)'],
  ];
  foreach ($stats as [$label,$val,$icon,$type,$hint]):
  ?>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon <?= $type ?>"><i class="<?= $icon ?>"></i></div>
      <div class="stat-value"><?= is_string($val) ? $val : number_format($val) ?></div>
      <div class="stat-label"><?= $label ?></div>
      <div style="font-size:.72rem;color:#BBB;margin-top:6px;font-family:monospace;"><?= $hint ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php if ($pendingPayments > 0): ?>
<div class="alert-gold mb-4">
  <i class="fas fa-bell"></i>
  <div>
    <strong><?= $pendingPayments ?> pembayaran menunggu verifikasi!</strong>
    <a href="<?= APP_URL ?>/admin/payments" style="color:#B8960C;font-weight:600;margin-left:8px;">Verifikasi Sekarang →</a>
  </div>
</div>
<?php endif; ?>

<div class="row g-4 mb-4">
  <!-- Sales Chart (DATE_FORMAT) -->
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="card-head">
        <h5><i class="fas fa-chart-line me-2" style="color:#D4AF37;"></i>Grafik Penjualan (12 Bulan)</h5>
        <span class="pdd-badge"><i class="fas fa-database me-1"></i>DATE_FORMAT + SUM</span>
      </div>
      <div class="card-body">
        <div class="pdd-info">
          <h6>Implementasi: Built-in Function</h6>
          <p><code>SELECT DATE_FORMAT(created_at,'%b %Y') AS label, SUM(grand_total), COUNT(id) FROM orders GROUP BY bulan</code></p>
        </div>
        <div class="chart-wrap">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Pending Payments -->
  <div class="col-lg-4">
    <div class="admin-card" style="height:100%;">
      <div class="card-head">
        <h5><i class="fas fa-clock me-2" style="color:#D4AF37;"></i>Pembayaran Pending</h5>
        <a href="<?= APP_URL ?>/admin/payments" style="font-size:.82rem;color:#D4AF37;">Lihat Semua</a>
      </div>
      <div class="card-body" style="padding:0;">
        <?php
        $pendingList = (new Order())->getPendingPayments();
        if (empty($pendingList)): ?>
        <div style="text-align:center;padding:40px;color:#999;">
          <i class="fas fa-check-circle" style="font-size:2rem;color:#28A745;margin-bottom:8px;display:block;"></i>
          Tidak ada pembayaran pending
        </div>
        <?php else: foreach (array_slice($pendingList,0,5) as $p): ?>
        <div style="padding:14px 20px;border-bottom:1px solid #F0F0F0;display:flex;align-items:center;gap:12px;">
          <div style="width:36px;height:36px;border-radius:50%;background:rgba(212,175,55,.1);display:flex;align-items:center;justify-content:center;color:#D4AF37;font-weight:700;font-size:.88rem;flex-shrink:0;">
            <?= strtoupper(substr($p['customer_name'],0,1)) ?>
          </div>
          <div class="flex-grow-1">
            <div style="font-size:.85rem;font-weight:600;"><?= htmlspecialchars($p['customer_name']) ?></div>
            <div style="font-size:.75rem;color:#999;">#<?= htmlspecialchars($p['order_number']) ?></div>
          </div>
          <div style="font-size:.82rem;font-weight:700;color:#D4AF37;">Rp<?= number_format($p['grand_total'],0,',','.') ?></div>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Produk Terlaris (VIEW) -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="card-head">
        <h5><i class="fas fa-trophy me-2" style="color:#D4AF37;"></i>Produk Terlaris</h5>
        <span class="pdd-badge"><i class="fas fa-eye me-1"></i>VIEW: view_produk_terlaris</span>
      </div>
      <div class="card-body" style="padding:0;">
        <?php if (empty($bestsellers)): ?>
        <div style="padding:32px;text-align:center;color:#999;font-size:.88rem;">Belum ada data penjualan.</div>
        <?php else: foreach ($bestsellers as $i => $p): ?>
        <div style="padding:12px 20px;border-bottom:1px solid #F0F0F0;display:flex;align-items:center;gap:12px;">
          <span style="width:28px;height:28px;border-radius:50%;background:<?= $i<3?'linear-gradient(135deg,#D4AF37,#B8960C)':'#F0F0F0' ?>;color:<?= $i<3?'#fff':'#777' ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0;"><?= $i+1 ?></span>
          <div class="flex-grow-1">
            <div style="font-size:.85rem;font-weight:600;color:#1A1A1A;"><?= htmlspecialchars($p['nama_produk']) ?></div>
            <div style="font-size:.75rem;color:#999;"><?= htmlspecialchars($p['kategori']) ?> • Terjual: <?= $p['total_terjual']??0 ?></div>
          </div>
          <div style="font-size:.82rem;font-weight:700;color:#D4AF37;">Rp<?= number_format($p['price'],0,',','.') ?></div>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>

  <!-- Customer Aktif (VIEW) -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="card-head">
        <h5><i class="fas fa-users me-2" style="color:#D4AF37;"></i>Customer Aktif</h5>
        <span class="pdd-badge"><i class="fas fa-eye me-1"></i>VIEW: view_customer_aktif</span>
      </div>
      <div class="card-body" style="padding:0;">
        <?php if (empty($activeCustomers)): ?>
        <div style="padding:32px;text-align:center;color:#999;font-size:.88rem;">Belum ada data customer.</div>
        <?php else: foreach ($activeCustomers as $c): ?>
        <div style="padding:12px 20px;border-bottom:1px solid #F0F0F0;display:flex;align-items:center;gap:12px;">
          <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#B8960C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.88rem;flex-shrink:0;">
            <?= strtoupper(substr($c['name'],0,1)) ?>
          </div>
          <div class="flex-grow-1">
            <div style="font-size:.85rem;font-weight:600;"><?= htmlspecialchars($c['name']) ?></div>
            <div style="font-size:.75rem;color:#999;"><?= $c['jumlah_transaksi'] ?> transaksi</div>
          </div>
          <div style="font-size:.82rem;font-weight:700;color:#28A745;">Rp<?= number_format($c['total_belanja'],0,',','.') ?></div>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
// Sales Chart
const labels = <?= json_encode(array_column($monthlySales,'label')) ?>;
const revenue = <?= json_encode(array_map(fn($m)=>(float)$m['total_pendapatan'],$monthlySales)) ?>;
const orders  = <?= json_encode(array_map(fn($m)=>(int)$m['jumlah_order'],$monthlySales)) ?>;

if (document.getElementById('salesChart')) {
  new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Pendapatan (Rp)',
        data: revenue,
        backgroundColor: 'rgba(212,175,55,0.2)',
        borderColor: '#D4AF37',
        borderWidth: 2,
        borderRadius: 6,
        yAxisID: 'y',
      },{
        label: 'Jumlah Order',
        data: orders,
        type: 'line',
        borderColor: '#1A1A1A',
        pointBackgroundColor: '#1A1A1A',
        tension: 0.4,
        yAxisID: 'y1',
        borderWidth: 2,
        pointRadius: 4,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: true } },
      scales: {
        y:  { position: 'left',  ticks: { callback: v => 'Rp'+(v>=1000000?v/1000000+'jt':v/1000+'rb') } },
        y1: { position: 'right', grid: { drawOnChartArea: false } }
      }
    }
  });
}
</script>

<?php include __DIR__ . '/../layouts/admin_footer.php'; ?>
