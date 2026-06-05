<?php
// app/views/admin/reports/index.php — PDD: VIEW + JOIN + UNION + UNION ALL
include __DIR__ . '/../../layouts/admin_header.php';
function fRpR($n){return 'Rp '.number_format($n,0,',','.');}
?>
<div class="admin-page-header">
  <h2><i class="fas fa-chart-bar me-2" style="color:#D4AF37;"></i>Laporan &amp; Implementasi PDD</h2>
</div>

<!-- PDD Summary -->
<div class="row g-3 mb-4">
  <?php foreach ([
    ['VIEW','3 Database Views','view_laporan_penjualan, view_produk_terlaris, view_customer_aktif','fas fa-eye','rgba(212,175,55,.1)','#B8960C'],
    ['JOIN','INNER + LEFT JOIN','Detail pesanan & laporan penjualan','fas fa-code-branch','rgba(23,162,184,.1)','#138496'],
    ['SET OPS','UNION & UNION ALL','Customer aktif & inventaris produk','fas fa-layer-group','rgba(40,167,69,.1)','#218838'],
    ['FUNCTION','SUM COUNT AVG + Custom','HitungDiskonMember, HitungPoinLoyalitas','fas fa-function','rgba(108,117,125,.1)','#495057'],
  ] as [$badge,$title,$desc,$icon,$bg,$color]): ?>
  <div class="col-md-6 col-xl-3">
    <div style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
        <div style="width:40px;height:40px;border-radius:10px;background:<?= $bg ?>;display:flex;align-items:center;justify-content:center;color:<?= $color ?>;font-size:1rem;">
          <i class="<?= $icon ?>"></i>
        </div>
        <span class="pdd-badge"><?= $badge ?></span>
      </div>
      <div style="font-weight:700;font-size:.9rem;margin-bottom:4px;"><?= $title ?></div>
      <div style="font-size:.78rem;color:#999;"><?= $desc ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- TAB NAV -->
<div style="display:flex;gap:4px;margin-bottom:20px;background:#F5F5F5;border-radius:10px;padding:4px;">
  <?php foreach ([['tab-laporan','Laporan Penjualan'],['tab-union','UNION & UNION ALL'],['tab-view','3 Database Views'],['tab-kategori','Per Kategori']] as [$id,$label]): ?>
  <button onclick="switchTab('<?= $id ?>')" id="btn-<?= $id ?>" style="flex:1;padding:10px;border:none;border-radius:8px;font-family:'Poppins',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer;transition:all .3s;background:<?= $id==='tab-laporan'?'#fff':'transparent' ?>;color:<?= $id==='tab-laporan'?'#1A1A1A':'#777' ?>;box-shadow:<?= $id==='tab-laporan'?'0 2px 8px rgba(0,0,0,.08)':'none' ?>;">
    <?= $label ?>
  </button>
  <?php endforeach; ?>
</div>

<!-- TAB: Laporan Penjualan (VIEW + INNER JOIN) -->
<div id="tab-laporan" class="tab-content">
  <div class="pdd-info mb-3">
    <h6>VIEW: view_laporan_penjualan</h6>
    <p>Menggunakan INNER JOIN: orders ⟶ users ⟶ order_details ⟶ products + LEFT JOIN payments. Data diambil dari VIEW yang sudah dibuat di database.</p>
  </div>

  <?php if ($summary): ?>
  <div class="row g-3 mb-3">
    <?php foreach ([['Total Transaksi',$summary['total_transaksi'],''],['Total Pendapatan',fRpR($summary['total_pendapatan']),''],[' Rata Nilai Order',fRpR($summary['rata_nilai_order']),'AVG()'],['Item Terjual',$summary['total_item_terjual'],'SUM()']] as [$l,$v,$fn]): ?>
    <div class="col-md-3">
      <div style="background:#fff;border-radius:12px;padding:16px 20px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div style="font-size:1.4rem;font-weight:800;color:#1A1A1A;"><?= $v ?></div>
        <div style="font-size:.8rem;color:#777;"><?= $l ?> <?= $fn?'<span style="font-family:monospace;font-size:.7rem;color:#D4AF37;">'.$fn.'</span>':'' ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="admin-card">
    <div class="card-head"><h5>Data Laporan Penjualan</h5><span class="pdd-badge"><i class="fas fa-eye me-1"></i>SELECT * FROM view_laporan_penjualan</span></div>
    <div style="overflow-x:auto;">
      <table class="admin-table">
        <thead><tr><th>#</th><th>No. Order</th><th>Customer</th><th>Tanggal</th><th>Total</th><th>Diskon</th><th>Grand Total</th><th>Status</th><th>Pembayaran</th></tr></thead>
        <tbody>
          <?php if (empty($laporan)): ?>
          <tr><td colspan="9" style="text-align:center;padding:32px;color:#999;">Belum ada data laporan.</td></tr>
          <?php else: foreach ($laporan as $i => $r): ?>
          <tr>
            <td style="color:#999;"><?= $i+1 ?></td>
            <td style="font-weight:600;font-size:.85rem;">#<?= htmlspecialchars($r['order_number']) ?></td>
            <td><?= htmlspecialchars($r['nama_customer']) ?></td>
            <td style="font-size:.82rem;color:#777;"><?= htmlspecialchars($r['tanggal_pesanan']) ?></td>
            <td>Rp <?= number_format($r['total_amount'],0,',','.') ?></td>
            <td style="color:#28A745;">Rp <?= number_format($r['discount'],0,',','.') ?></td>
            <td style="font-weight:700;color:#D4AF37;">Rp <?= number_format($r['grand_total'],0,',','.') ?></td>
            <td><span class="status-badge status-<?= $r['status_pesanan'] ?>"><?= ucfirst($r['status_pesanan']) ?></span></td>
            <td><?php if($r['status_pembayaran']): ?><span class="status-badge status-<?= $r['status_pembayaran'] ?>"><?= ucfirst($r['status_pembayaran']) ?></span><?php endif; ?></td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- TAB: UNION & UNION ALL -->
<div id="tab-union" class="tab-content" style="display:none;">
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="pdd-info">
        <h6>SET OPERATION: UNION</h6>
        <p>Customer yang pernah ORDER atau pernah memberikan REVIEW. UNION menghapus duplikat.</p>
      </div>
      <div class="admin-card">
        <div class="card-head"><h5>Customer Aktif (UNION)</h5><span class="pdd-badge">UNION</span></div>
        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead><tr><th>Nama</th><th>Email</th><th>Aktivitas</th></tr></thead>
            <tbody>
              <?php foreach ($customerUnion as $r): ?>
              <tr>
                <td style="font-weight:600;"><?= htmlspecialchars($r['name']) ?></td>
                <td style="font-size:.82rem;color:#777;"><?= htmlspecialchars($r['email']) ?></td>
                <td><span style="background:rgba(212,175,55,.1);color:#B8960C;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;"><?= $r['aktivitas'] ?></span></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($customerUnion)): ?><tr><td colspan="3" style="text-align:center;padding:24px;color:#999;">Belum ada data.</td></tr><?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="pdd-info">
        <h6>SET OPERATION: UNION ALL</h6>
        <p>Produk Cincin + Kalung digabung. UNION ALL mempertahankan semua baris termasuk duplikat.</p>
      </div>
      <div class="admin-card">
        <div class="card-head"><h5>Inventaris Cincin &amp; Kalung (UNION ALL)</h5><span class="pdd-badge">UNION ALL</span></div>
        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead><tr><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Sumber</th></tr></thead>
            <tbody>
              <?php foreach ($inventarisUnionAll as $r): ?>
              <tr>
                <td style="font-weight:600;font-size:.85rem;"><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['kategori']) ?></td>
                <td>Rp <?= number_format($r['price'],0,',','.') ?></td>
                <td><?= $r['stock'] ?></td>
                <td><span style="background:rgba(23,162,184,.1);color:#138496;padding:3px 10px;border-radius:20px;font-size:.75rem;"><?= $r['sumber'] ?></span></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($inventarisUnionAll)): ?><tr><td colspan="5" style="text-align:center;padding:24px;color:#999;">Belum ada data produk cincin/kalung.</td></tr><?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TAB: 3 Database Views -->
<div id="tab-view" class="tab-content" style="display:none;">
  <div class="row g-4">
    <?php foreach ([
      ['view_laporan_penjualan','Laporan Penjualan','JOIN orders, users, order_details, payments'],
      ['view_produk_terlaris','Produk Terlaris','JOIN products, categories, order_details, reviews'],
      ['view_customer_aktif','Customer Aktif','JOIN users, orders, reviews'],
    ] as [$vname,$vtitle,$vjoin]): ?>
    <div class="col-12">
      <div class="admin-card">
        <div class="card-head">
          <h5><?= $vtitle ?></h5>
          <code style="font-size:.78rem;background:#F5F5F5;padding:4px 10px;border-radius:6px;">SELECT * FROM <?= $vname ?></code>
        </div>
        <div class="card-body">
          <p style="font-size:.82rem;color:#777;margin:0;"><strong>JOIN:</strong> <?= $vjoin ?></p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- TAB: Per Kategori -->
<div id="tab-kategori" class="tab-content" style="display:none;">
  <div class="admin-card">
    <div class="card-head"><h5>Penjualan per Kategori</h5><span class="pdd-badge">GROUP BY + SUM + COUNT</span></div>
    <div style="overflow-x:auto;">
      <table class="admin-table">
        <thead><tr><th>Kategori</th><th>Jumlah Order</th><th>Total Qty</th><th>Total Pendapatan</th></tr></thead>
        <tbody>
          <?php foreach ($byCategory as $r): ?>
          <tr>
            <td style="font-weight:600;"><?= htmlspecialchars($r['kategori']) ?></td>
            <td><?= $r['jumlah_order']??0 ?></td>
            <td><?= $r['total_qty']??0 ?></td>
            <td style="font-weight:700;color:#D4AF37;"><?= fRpR($r['total_pendapatan']??0) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function switchTab(activeId) {
  const tabs = ['tab-laporan','tab-union','tab-view','tab-kategori'];
  tabs.forEach(id => {
    document.getElementById(id).style.display = id===activeId ? 'block' : 'none';
    const btn = document.getElementById('btn-'+id);
    if (btn) {
      btn.style.background = id===activeId ? '#fff' : 'transparent';
      btn.style.color = id===activeId ? '#1A1A1A' : '#777';
      btn.style.boxShadow = id===activeId ? '0 2px 8px rgba(0,0,0,.08)' : 'none';
    }
  });
}
</script>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
