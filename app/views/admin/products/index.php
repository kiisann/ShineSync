<?php
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="admin-page-header">
  <div>
    <h2>Kelola Produk</h2>
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= APP_URL ?>/admin/dashboard">Dashboard</a></li><li class="breadcrumb-item active">Produk</li></ol></nav>
  </div>
  <a href="<?= APP_URL ?>/admin/products/create" class="btn-admin-gold"><i class="fas fa-plus"></i> Tambah Produk</a>
</div>

<div class="admin-card">
  <div class="card-head">
    <h5><i class="fas fa-gem me-2" style="color:#D4AF37;"></i>Daftar Produk <span style="color:#999;font-weight:400;font-size:.82rem;">(<?= count($products) ?> produk)</span></h5>
    <div style="display:flex;gap:8px;">
      <input type="text" id="search-products" placeholder="Cari produk..." class="form-control" style="width:220px;font-size:.85rem;" onkeyup="filterTable(this,'products-table')">
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="admin-table" id="products-table">
      <thead>
        <tr>
          <th>#</th><th>Foto</th><th>Nama Produk</th><th>Kategori</th>
          <th>Harga</th><th>Stok</th><th>Featured</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($products)): ?>
        <tr><td colspan="9" style="text-align:center;padding:40px;color:#999;">Belum ada produk.</td></tr>
        <?php else: foreach ($products as $i => $p):
          $img = ($p['image']&&file_exists(UPLOAD_PATH.'products/'.$p['image'])) ? APP_URL.'/uploads/products/'.$p['image'] : APP_URL.'/public/images/no-image.svg';
        ?>
        <tr>
          <td style="color:#999;"><?= $i+1 ?></td>
          <td><img src="<?= $img ?>" class="product-thumb" alt=""></td>
          <td>
            <div style="font-weight:600;font-size:.88rem;"><?= htmlspecialchars($p['name']) ?></div>
            <div style="font-size:.75rem;color:#999;"><?= htmlspecialchars(substr($p['description']??'',0,60)) ?>...</div>
          </td>
          <td><span style="background:rgba(212,175,55,.1);color:#B8960C;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:600;"><?= htmlspecialchars($p['category_name']??'') ?></span></td>
          <td style="font-weight:700;color:#D4AF37;">Rp <?= number_format($p['price'],0,',','.') ?></td>
          <td>
            <span style="color:<?= $p['stock']<5?'#DC3545':($p['stock']<20?'#FFC107':'#28A745') ?>;font-weight:600;">
              <?= $p['stock'] ?>
            </span>
          </td>
          <td><?= $p['is_featured'] ? '<i class="fas fa-star" style="color:#D4AF37;"></i>' : '<i class="far fa-star" style="color:#DDD;"></i>' ?></td>
          <td>
            <span class="status-badge" style="<?= $p['is_active'] ? 'background:rgba(40,167,69,.1);color:#155724;' : 'background:rgba(220,53,69,.1);color:#721c24;' ?>">
              <?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?>
            </span>
          </td>
          <td>
            <div class="action-btns">
              <a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>" target="_blank" class="btn-action btn-view" title="Lihat di toko"><i class="fas fa-external-link-alt"></i></a>
              <a href="<?= APP_URL ?>/admin/products/edit/<?= $p['id'] ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
              <button class="btn-action btn-delete btn-confirm-delete" onclick="deleteProduct(<?= $p['id'] ?>, this)" title="Hapus"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function filterTable(input, tableId) {
  const filter = input.value.toLowerCase();
  document.querySelectorAll(`#${tableId} tbody tr`).forEach(tr => {
    tr.style.display = tr.textContent.toLowerCase().includes(filter) ? '' : 'none';
  });
}
function deleteProduct(id, btn) {
  if (!confirm('Yakin ingin menghapus produk ini? (Soft delete)')) return;
  fetch('<?= APP_URL ?>/admin/products/delete/' + id, {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  }).then(r => r.json()).then(d => {
    if (d.success) {
      btn.closest('tr').style.opacity = '0.3';
      btn.disabled = true;
      showToast('Produk berhasil dihapus (CALL sp_delete_produk).', 'success');
    }
  });
}
</script>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
