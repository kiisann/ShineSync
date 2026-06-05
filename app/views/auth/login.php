<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="app-url" content="<?= APP_URL ?>">
  <title>Login — ShineSync</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <a href="<?= APP_URL ?>" class="auth-logo d-block">Shine<span>Sync</span></a>
    <p class="auth-subtitle">Masuk ke akun Anda untuk berbelanja</p>

    <?php if ($error): ?>
    <div class="alert" style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.2);border-radius:10px;padding:12px 16px;font-size:.88rem;color:#721c24;margin-bottom:20px;">
      <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
    </div>
    <?php endif; if ($success): ?>
    <div class="alert" style="background:rgba(40,167,69,.08);border:1px solid rgba(40,167,69,.2);border-radius:10px;padding:12px 16px;font-size:.88rem;color:#155724;margin-bottom:20px;">
      <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/auth/login">
      <div class="form-group">
        <label class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text" style="background:#F8F8F8;border:1.5px solid #E5E5E5;border-right:none;border-radius:8px 0 0 8px;">
            <i class="fas fa-envelope" style="color:#D4AF37;"></i>
          </span>
          <input type="email" name="email" class="form-control" placeholder="email@example.com" required
                 style="border-left:none;border-radius:0 8px 8px 0;" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text" style="background:#F8F8F8;border:1.5px solid #E5E5E5;border-right:none;border-radius:8px 0 0 8px;">
            <i class="fas fa-lock" style="color:#D4AF37;"></i>
          </span>
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" required
                 style="border-left:none;border-radius:0 8px 8px 0;">
          <button type="button" class="input-group-text" style="background:#F8F8F8;border:1.5px solid #E5E5E5;border-left:none;border-radius:0 8px 8px 0;cursor:pointer;" onclick="togglePass('password',this)">
            <i class="fas fa-eye" style="color:#999;"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn-gold w-100" style="width:100%;justify-content:center;margin-top:8px;">
        <i class="fas fa-sign-in-alt"></i> Masuk
      </button>
    </form>

    <div class="auth-divider">atau</div>

    <p style="text-align:center;font-size:.88rem;color:#777;">
      Belum punya akun? <a href="<?= APP_URL ?>/auth/register" style="color:#D4AF37;font-weight:600;">Daftar sekarang</a>
    </p>
    <p style="text-align:center;font-size:.82rem;color:#999;margin-top:8px;">
      Admin? <a href="<?= APP_URL ?>/admin/login" style="color:#555;">Login disini</a>
    </p>
  </div>
</div>
<script>
function togglePass(id, btn) {
  const inp = document.getElementById(id);
  const icon = btn.querySelector('i');
  if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fas fa-eye-slash'; }
  else { inp.type = 'password'; icon.className = 'fas fa-eye'; }
}
</script>
</body>
</html>
