<?php
include __DIR__ . '/../../layouts/admin_header.php';
$img = ($product['image']&&file_exists(UPLOAD_PATH.'products/'.$product['image'])) ? APP_URL.'/uploads/products/'.$product['image'] : APP_URL.'/public/images/no-image.svg';
?>
<div class="admin-page-header">
  <div>
    <h2>Edit Produk</h2>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= APP_URL ?>/admin/products">Produk</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
  </div>
  <a href="<?= APP_URL ?>/admin/products" class="btn-admin-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="pdd-info mb-4">
  <h6><i class="fas fa-database me-2"></i>PDD: Stored Procedure — sp_update_produk</h6>
  <p>Form ini memanggil <code>CALL sp_update_produk(id, category_id, name, slug, description, price, stock, weight, material, image, is_featured)</code></p>
</div>

<form method="POST" action="<?= APP_URL ?>/admin/products/update/<?= $product['id'] ?>" enctype="multipart/form-data">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="admin-form-card">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= $cat['id']==$product['category_id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Material</label>
            <input type="text" name="material" class="form-control" value="<?= htmlspecialchars($product['material']??'') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" name="price" class="form-control" required value="<?= $product['price'] ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" class="form-control" required value="<?= $product['stock'] ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Berat (gram)</label>
            <input type="number" name="weight" class="form-control" step="0.1" value="<?= $product['weight']??0 ?>">
          </div>
          <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description']??'') ?></textarea>
          </div>
          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" <?= $product['is_featured']?'checked':'' ?>>
              <label for="is_featured" class="form-check-label" style="font-size:.88rem;">Produk Unggulan</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="admin-form-card">
        <h6 style="font-weight:700;margin-bottom:12px;">Foto Produk</h6>
        <img id="image-preview" src="<?= $img ?>" style="width:100%;border-radius:8px;margin-bottom:12px;" alt="">
        <div style="border:2px dashed #D4AF37;border-radius:12px;padding:20px;text-align:center;cursor:pointer;" onclick="document.getElementById('product-image-input').click()">
          <i class="fas fa-camera" style="color:#D4AF37;margin-bottom:6px;display:block;font-size:1.4rem;"></i>
          <p style="font-size:.82rem;color:#777;margin:0;">Klik untuk ganti foto</p>
        </div>
        <input type="file" id="product-image-input" name="image" accept="image/*" style="display:none;">
      </div>
    </div>
    <div class="col-12">
      <button type="submit" class="btn-admin-gold"><i class="fas fa-save me-2"></i>Update Produk (via SP)</button>
      <a href="<?= APP_URL ?>/admin/products" class="btn-admin-secondary ms-2">Batal</a>
    </div>
  </div>
</form>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
