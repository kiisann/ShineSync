<?php // app/views/auth/register.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Daftar — ShineSync</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>
<div class="auth-wrapper" style="padding:40px 20px;">
  <div class="auth-card" style="max-width:500px;">
    <a href="<?= APP_URL ?>" class="auth-logo d-block">Shine<span>Sync</span></a>
    <p class="auth-subtitle">Buat akun dan mulai berbelanja perhiasan mewah</p>

    <?php if ($error): ?>
    <div class="alert" style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.2);border-radius:10px;padding:12px 16px;font-size:.88rem;color:#721c24;margin-bottom:20px;">
      <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/auth/register">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="email@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password" required>
        </div>
        <div class="col-12">
          <label class="form-label">Nomor Telepon <span style="color:#999;font-size:.8rem;">(opsional)</span></label>
          <input type="tel" name="phone" class="form-control" placeholder="08xx-xxxx-xxxx" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div class="col-12">
          <div class="form-check" style="font-size:.85rem;">
            <input class="form-check-input" type="checkbox" id="agree" required style="border-color:#D4AF37;">
            <label class="form-check-label" for="agree" style="color:#555;">
              Saya setuju dengan <a href="#" style="color:#D4AF37;">Syarat & Ketentuan</a> ShineSync
            </label>
          </div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn-gold w-100" style="width:100%;justify-content:center;">
            <i class="fas fa-user-plus"></i> Buat Akun
          </button>
        </div>
      </div>
    </form>

    <p style="text-align:center;font-size:.88rem;color:#777;margin-top:20px;">
      Sudah punya akun? <a href="<?= APP_URL ?>/auth/login" style="color:#D4AF37;font-weight:600;">Login</a>
    </p>
  </div>
</div>
</body>
</html>
