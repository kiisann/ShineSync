<?php
// app/views/admin/categories/index.php
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="admin-page-header">
  <div><h2>Kelola Kategori</h2></div>
  <a href="<?= APP_URL ?>/admin/categories/create" class="btn-admin-gold"><i class="fas fa-plus"></i> Tambah Kategori</a>
</div>
<div class="admin-card">
  <div class="card-head"><h5><i class="fas fa-tags me-2" style="color:#D4AF37;"></i>Daftar Kategori</h5></div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead><tr><th>#</th><th>Nama</th><th>Slug</th><th>Deskripsi</th><th>Produk</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if(empty($categories)): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:#999;">Belum ada kategori.</td></tr>
        <?php else: foreach($categories as $i=>$c): ?>
        <tr>
          <td style="color:#999;"><?= $i+1 ?></td>
          <td style="font-weight:600;"><?= htmlspecialchars($c['name']) ?></td>
          <td><code style="font-size:.78rem;background:#F5F5F5;padding:3px 8px;border-radius:4px;"><?= htmlspecialchars($c['slug']) ?></code></td>
          <td style="font-size:.82rem;color:#777;"><?= htmlspecialchars(substr($c['description']??'',0,50)) ?></td>
          <td><span style="font-weight:700;"><?= $c['product_count'] ?></span></td>
          <td><span class="status-badge" style="<?= $c['is_active']?'background:rgba(40,167,69,.1);color:#155724;':'background:rgba(220,53,69,.1);color:#721c24;' ?>"><?= $c['is_active']?'Aktif':'Nonaktif' ?></span></td>
          <td>
            <div class="action-btns">
              <a href="<?= APP_URL ?>/admin/categories/edit/<?= $c['id'] ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
              <button class="btn-action btn-delete" onclick="if(confirm('Hapus kategori ini?'))window.location='<?= APP_URL ?>/admin/categories/delete/<?= $c['id'] ?>'"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
