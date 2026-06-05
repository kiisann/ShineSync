<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Login — ShineSync</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= APP_URL ?>/public/css/admin.css">
  <style>
    body { background: #1A1A1A; display:flex; align-items:center; justify-content:center; min-height:100vh; }
    .admin-login-card { background:#fff; border-radius:20px; padding:48px 40px; width:420px; box-shadow:0 30px 80px rgba(0,0,0,0.4); }
  </style>
</head>
<body>
<div class="admin-login-card">
  <div style="text-align:center;margin-bottom:32px;">
    <div style="width:60px;height:60px;background:linear-gradient(135deg,#D4AF37,#B8960C);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;color:#fff;">
      <i class="fas fa-shield-alt"></i>
    </div>
    <h4 style="font-weight:700;color:#1A1A1A;margin-bottom:4px;">Admin Panel</h4>
    <p style="color:#888;font-size:.88rem;">ShineSync Administration</p>
  </div>

  <?php if ($error): ?>
  <div style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.2);border-radius:10px;padding:12px 16px;font-size:.88rem;color:#721c24;margin-bottom:20px;">
    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="<?= APP_URL ?>/admin/login">
    <div style="margin-bottom:16px;">
      <label style="font-size:.85rem;font-weight:500;color:#333;display:block;margin-bottom:6px;">Email Admin</label>
      <input type="email" name="email" class="form-control" placeholder="admin@shinesync.com" required
             style="border:1.5px solid #E5E5E5;border-radius:10px;padding:12px 16px;">
    </div>
    <div style="margin-bottom:24px;">
      <label style="font-size:.85rem;font-weight:500;color:#333;display:block;margin-bottom:6px;">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Password" required
             style="border:1.5px solid #E5E5E5;border-radius:10px;padding:12px 16px;">
    </div>
    <button type="submit" class="btn-admin-gold w-100" style="width:100%;justify-content:center;padding:14px;">
      <i class="fas fa-sign-in-alt"></i> Masuk
    </button>
  </form>

  <p style="text-align:center;font-size:.82rem;color:#999;margin-top:20px;">
    <a href="<?= APP_URL ?>/auth/login" style="color:#D4AF37;">← Kembali ke halaman customer</a>
  </p>

</div>
</body>
</html>
