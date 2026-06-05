<?php
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="admin-page-header">
  <div>
    <h2>Tambah Produk</h2>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= APP_URL ?>/admin/products">Produk</a></li><li class="breadcrumb-item active">Tambah</li></ol></nav>
  </div>
  <a href="<?= APP_URL ?>/admin/products" class="btn-admin-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<form method="POST" action="<?= APP_URL ?>/admin/products/store" enctype="multipart/form-data">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="admin-form-card">
        <h6 style="font-weight:700;margin-bottom:20px;">Informasi Produk</h6>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Nama Produk <span style="color:#DC3545;">*</span></label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Cincin Diamond Solitaire 18K">
          </div>
          <div class="col-md-6">
            <label class="form-label">Kategori <span style="color:#DC3545;">*</span></label>
            <select name="category_id" class="form-select" required>
              <option value="">Pilih Kategori</option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Material</label>
            <input type="text" name="material" class="form-control" placeholder="Contoh: Emas 18K + Berlian">
          </div>
          <div class="col-md-4">
            <label class="form-label">Harga (Rp) <span style="color:#DC3545;">*</span></label>
            <input type="number" name="price" class="form-control" min="0" step="1000" required placeholder="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Stok <span style="color:#DC3545;">*</span></label>
            <input type="number" name="stock" class="form-control" min="0" required placeholder="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Berat (gram)</label>
            <input type="number" name="weight" class="form-control" min="0" step="0.1" placeholder="0">
          </div>
          <div class="col-12">
            <label class="form-label">Deskripsi Produk</label>
            <textarea name="description" class="form-control" rows="5" placeholder="Deskripsi lengkap produk..."></textarea>
          </div>
          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" style="border-color:#D4AF37;">
              <label for="is_featured" class="form-check-label" style="font-size:.88rem;">Tampilkan sebagai produk unggulan di homepage</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="admin-form-card">
        <h6 style="font-weight:700;margin-bottom:20px;">Foto Produk</h6>
        <div id="img-drop" style="border:2px dashed #D4AF37;border-radius:12px;padding:40px 16px;text-align:center;cursor:pointer;background:rgba(212,175,55,.03);" onclick="document.getElementById('product-image-input').click()">
          <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#D4AF37;margin-bottom:8px;display:block;"></i>
          <p style="font-size:.85rem;color:#777;margin:0;">Klik untuk upload foto</p>
          <p style="font-size:.75rem;color:#999;">JPG, PNG, WEBP</p>
        </div>
        <img id="image-preview" style="width:100%;border-radius:8px;margin-top:12px;display:none;" alt="Preview">
        <input type="file" id="product-image-input" name="image" accept="image/*" style="display:none;">
      </div>
    </div>
    <div class="col-12">
      <div style="display:flex;gap:12px;">
        <button type="submit" class="btn-admin-gold"><i class="fas fa-save me-2"></i>Simpan Produk (via SP)</button>
        <a href="<?= APP_URL ?>/admin/products" class="btn-admin-secondary">Batal</a>
      </div>
    </div>
  </div>
</form>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
