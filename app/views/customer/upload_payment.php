<?php
// app/views/customer/upload_payment.php
include __DIR__ . '/../layouts/header.php';
function fRpUp($n){return 'Rp '.number_format($n,0,',','.');}
?>
<div class="ss-breadcrumb"><div class="container">
  <nav><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>">Beranda</a></li>
    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/orders">Pesanan</a></li>
    <li class="breadcrumb-item active">Upload Bukti Bayar</li>
  </ol></nav>
</div></div>

<section class="ss-section-sm">
  <div class="container">
    <div style="max-width:560px;margin:0 auto;">
      <h2 class="section-title mb-4">Upload Bukti Pembayaran</h2>

      <?php if (Session::getFlash('error')): ?>
      <div class="alert" style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.2);border-radius:10px;padding:12px 16px;font-size:.88rem;color:#721c24;margin-bottom:20px;">
        <i class="fas fa-times-circle me-2"></i><?= htmlspecialchars(Session::getFlash('error')) ?>
      </div>
      <?php endif; ?>

      <!-- Order Info -->
      <div style="background:#F8F8F8;border-radius:16px;padding:20px;margin-bottom:24px;">
        <h6 style="font-weight:700;margin-bottom:12px;">Info Pesanan</h6>
        <div class="d-flex justify-content-between mb-2" style="font-size:.88rem;">
          <span style="color:#777;">Nomor Pesanan</span>
          <strong>#<?= htmlspecialchars($order['order_number']) ?></strong>
        </div>
        <div class="d-flex justify-content-between mb-2" style="font-size:.88rem;">
          <span style="color:#777;">Total Pembayaran</span>
          <strong style="color:#D4AF37;font-size:1rem;"><?= fRpUp($order['grand_total']) ?></strong>
        </div>
        <?php if ($payment && $payment['method'] === 'transfer'): ?>
        <hr>
        <p style="font-size:.82rem;color:#777;margin-bottom:6px;font-weight:600;">Transfer ke:</p>
        <?php foreach ([['BCA','123.456.7890'],['Mandiri','098.765.4321']] as [$bank,$acc]): ?>
        <div style="font-size:.85rem;margin-bottom:3px;">
          <span style="color:#777;"><?= $bank ?>:</span> <strong><?= $acc ?> a.n. ShineSync</strong>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Upload Form -->
      <?php if ($payment && $payment['proof_image']): ?>
      <div style="background:rgba(40,167,69,.06);border:1px solid rgba(40,167,69,.2);border-radius:12px;padding:20px;text-align:center;">
        <i class="fas fa-check-circle" style="font-size:2rem;color:#28A745;margin-bottom:12px;display:block;"></i>
        <p style="font-weight:600;color:#155724;">Bukti pembayaran sudah diupload!</p>
        <img src="<?= APP_URL ?>/uploads/payments/<?= $payment['proof_image'] ?>" style="max-width:100%;border-radius:8px;margin-top:12px;" alt="Bukti Bayar">
        <p style="font-size:.82rem;color:#777;margin-top:12px;">Status: <strong><?= ucfirst($payment['status']) ?></strong></p>
      </div>
      <?php else: ?>
      <form method="POST" action="<?= APP_URL ?>/orders/payment/<?= urlencode($order['order_number']) ?>" enctype="multipart/form-data">
        <div style="background:#fff;border-radius:16px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,.06);">
          <div class="form-group">
            <label class="form-label" style="font-weight:600;">File Bukti Pembayaran</label>
            <div id="drop-zone" style="border:2px dashed #D4AF37;border-radius:12px;padding:40px 20px;text-align:center;cursor:pointer;background:rgba(212,175,55,.03);transition:all .3s;" onclick="document.getElementById('proof-file').click()">
              <i class="fas fa-cloud-upload-alt" style="font-size:2.5rem;color:#D4AF37;margin-bottom:12px;display:block;"></i>
              <p style="font-weight:600;color:#1A1A1A;margin-bottom:4px;">Klik atau drag file disini</p>
              <p style="font-size:.82rem;color:#777;">Format: JPG, PNG, PDF (Max 5MB)</p>
              <img id="preview" style="max-width:100%;margin-top:16px;border-radius:8px;display:none;" alt="Preview">
            </div>
            <input type="file" id="proof-file" name="proof" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="previewFile(this)">
          </div>
          <button type="submit" class="btn-gold d-block w-100 mt-4" style="justify-content:center;padding:14px;">
            <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
          </button>
        </div>
      </form>
      <?php endif; ?>

      <div class="text-center mt-4">
        <a href="<?= APP_URL ?>/orders" class="btn-outline-gold btn-sm">
          <i class="fas fa-arrow-left me-1"></i>Kembali ke Pesanan
        </a>
      </div>
    </div>
  </div>
</section>

<script>
function previewFile(input) {
  const file = input.files[0];
  if (!file) return;
  if (file.type.startsWith('image/')) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
  }
  document.querySelector('#drop-zone p').textContent = file.name;
}
// Drag drop
const dz = document.getElementById('drop-zone');
dz?.addEventListener('dragover', e => { e.preventDefault(); dz.style.background='rgba(212,175,55,.08)'; });
dz?.addEventListener('dragleave', () => dz.style.background='rgba(212,175,55,.03)');
dz?.addEventListener('drop', e => { e.preventDefault(); const f=e.dataTransfer.files[0]; if(f){const input=document.getElementById('proof-file');const dt=new DataTransfer();dt.items.add(f);input.files=dt.files;previewFile(input);} });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
