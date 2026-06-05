<?php
// app/views/customer/profile.php
include __DIR__ . '/../layouts/header.php';
?>
<div class="ss-breadcrumb"><div class="container">
  <nav><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
    <li class="breadcrumb-item active">Profil Saya</li>
  </ol></nav>
</div></div>

<section class="ss-section-sm">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <div class="col-lg-3 col-md-4">
        <!-- Sidebar Profile -->
        <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 2px 16px rgba(0,0,0,.06);text-align:center;">
          <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#B8960C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:2rem;margin:0 auto 16px;">
            <?= strtoupper(substr($user['name'],0,1)) ?>
          </div>
          <h6 style="font-weight:700;"><?= htmlspecialchars($user['name']) ?></h6>
          <p style="font-size:.82rem;color:#999;"><?= htmlspecialchars($user['email']) ?></p>
          <hr>
          <div class="d-flex flex-column gap-2">
            <a href="<?= APP_URL ?>/orders" style="font-size:.85rem;color:#555;text-decoration:none;padding:8px;border-radius:8px;transition:all .3s;" onmouseenter="this.style.background='rgba(212,175,55,.08)'" onmouseleave="this.style.background=''">
              <i class="fas fa-box me-2" style="color:#D4AF37;"></i>Pesanan Saya
            </a>
            <a href="<?= APP_URL ?>/wishlist" style="font-size:.85rem;color:#555;text-decoration:none;padding:8px;border-radius:8px;transition:all .3s;" onmouseenter="this.style.background='rgba(212,175,55,.08)'" onmouseleave="this.style.background=''">
              <i class="fas fa-heart me-2" style="color:#D4AF37;"></i>Wishlist
            </a>
            <a href="<?= APP_URL ?>/auth/logout" style="font-size:.85rem;color:#DC3545;text-decoration:none;padding:8px;border-radius:8px;">
              <i class="fas fa-sign-out-alt me-2;"></i>Logout
            </a>
          </div>
        </div>
      </div>

      <div class="col-lg-9 col-md-8">
        <!-- Edit Profile -->
        <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 2px 16px rgba(0,0,0,.06);margin-bottom:20px;">
          <h5 style="font-weight:700;margin-bottom:20px;"><i class="fas fa-user-edit me-2" style="color:#D4AF37;"></i>Edit Profil</h5>
          <form method="POST" action="<?= APP_URL ?>/profile/update" enctype="multipart/form-data">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email <span style="color:#999;font-size:.78rem;">(tidak dapat diubah)</span></label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background:#F8F8F8;">
              </div>
              <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']??'') ?>">
              </div>
              <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($user['address']??'') ?></textarea>
              </div>
            </div>
            <button type="submit" class="btn-gold mt-4"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
          </form>
        </div>

        <!-- Change Password -->
        <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 2px 16px rgba(0,0,0,.06);">
          <h5 style="font-weight:700;margin-bottom:20px;"><i class="fas fa-lock me-2" style="color:#D4AF37;"></i>Ganti Password</h5>
          <form method="POST" action="<?= APP_URL ?>/profile/change-password">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Password Lama</label>
                <input type="password" name="current_password" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Password Baru</label>
                <input type="password" name="new_password" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" class="form-control" required>
              </div>
            </div>
            <button type="submit" class="btn-outline-gold mt-4"><i class="fas fa-key me-2"></i>Ganti Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
