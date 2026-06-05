<?php
include __DIR__ . '/../../layouts/admin_header.php';
$isEdit = isset($category);
?>
<div class="admin-page-header">
  <div>
    <h2><?= $isEdit?'Edit':'Tambah' ?> Kategori</h2>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= APP_URL ?>/admin/categories">Kategori</a></li><li class="breadcrumb-item active"><?= $isEdit?'Edit':'Tambah' ?></li></ol></nav>
  </div>
  <a href="<?= APP_URL ?>/admin/categories" class="btn-admin-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="max-width:600px;">
  <div class="admin-form-card">
    <form method="POST" action="<?= APP_URL ?>/admin/categories/<?= $isEdit?'update/'.$category['id']:'store' ?>">
      <div class="form-group">
        <label class="form-label">Nama Kategori <span style="color:#DC3545;">*</span></label>
        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($category['name']??'') ?>" placeholder="Contoh: Cincin">
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description']??'') ?></textarea>
      </div>
      <div class="form-group">
        <div class="form-check">
          <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?= (!$isEdit||$category['is_active'])?'checked':'' ?>>
          <label for="is_active" class="form-check-label" style="font-size:.88rem;">Kategori Aktif</label>
        </div>
      </div>
      <div class="d-flex gap-3">
        <button type="submit" class="btn-admin-gold"><i class="fas fa-save me-2"></i><?= $isEdit?'Update':'Simpan' ?> Kategori</button>
        <a href="<?= APP_URL ?>/admin/categories" class="btn-admin-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
