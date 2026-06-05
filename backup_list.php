<?php
date_default_timezone_set('Asia/Jakarta');

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Session.php';

Session::start();

// Hanya admin
if (!Session::isLoggedIn() || !Session::isAdmin()) {
    header('Location: ' . APP_URL . '/admin/login');
    exit;
}

$backup_dir = ROOT_PATH . '/storage/backups/';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

// --- Aksi: Download ---
if (isset($_GET['download'])) {
    $filename = basename($_GET['download']);
    $filepath = $backup_dir . $filename;
    // Pastikan file ada dan berada di dalam direktori backup (keamanan)
    if (file_exists($filepath) && realpath($filepath) === realpath($backup_dir . $filename)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
    header('Location: ' . APP_URL . '/backup_list.php?status=fail&message=' . urlencode('File tidak ditemukan.'));
    exit;
}

// --- Aksi: Hapus ---
if (isset($_GET['delete'])) {
    $filename = basename($_GET['delete']);
    $filepath = $backup_dir . $filename;
    if (file_exists($filepath) && realpath($filepath) === realpath($backup_dir . $filename)) {
        unlink($filepath);
        header('Location: ' . APP_URL . '/backup_list.php?status=success&message=' . urlencode('File backup berhasil dihapus.'));
    } else {
        header('Location: ' . APP_URL . '/backup_list.php?status=fail&message=' . urlencode('File tidak ditemukan.'));
    }
    exit;
}

// --- Ambil daftar file backup ---
$files = glob($backup_dir . 'shinesync_backup_*.sql');
$backups = [];
if ($files) {
    foreach ($files as $f) {
        $backups[] = [
            'name'     => basename($f),
            'size'     => filesize($f),
            'modified' => filemtime($f),
        ];
    }
    // Urutkan terbaru di atas
    usort($backups, fn($a, $b) => $b['modified'] - $a['modified']);
}

// --- Status notifikasi ---
$status  = $_GET['status']  ?? null;
$message = $_GET['message'] ?? null;

// Fungsi format ukuran file
function formatSize(int $bytes): string
{
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)    return number_format($bytes / 1024, 2)    . ' KB';
    return $bytes . ' B';
}

include ROOT_PATH . '/app/views/layouts/admin_header.php';
?>

<div class="container-fluid px-4">

  <!-- Page Header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="fas fa-database me-2 text-warning"></i>Backup Database</h4>
      <small class="text-muted">Kelola backup otomatis database ShineSync</small>
    </div>
    <a href="<?= APP_URL ?>/backup.php"
       class="btn btn-warning fw-semibold"
       onclick="return confirm('Buat backup database sekarang?')">
      <i class="fas fa-plus-circle me-1"></i> Backup Sekarang
    </a>
  </div>

  <!-- Notifikasi -->
  <?php if ($status === 'success'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <?= htmlspecialchars($message ?? 'Operasi berhasil.') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif ($status === 'fail'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-times-circle me-2"></i>
      <strong>Gagal!</strong>
      <pre class="mb-0 mt-1" style="font-size:.8rem;white-space:pre-wrap;"><?= htmlspecialchars($message ?? 'Terjadi kesalahan.') ?></pre>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Tabel Backup -->
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <?php if (empty($backups)): ?>
        <div class="text-center py-5 text-muted">
          <i class="fas fa-database fa-3x mb-3 opacity-25"></i>
          <p class="mb-0">Belum ada file backup tersedia.</p>
          <small>Klik <strong>Backup Sekarang</strong> untuk membuat backup pertama.</small>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Nama File</th>
                <th>Tanggal &amp; Waktu</th>
                <th>Ukuran</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($backups as $i => $b): ?>
              <tr>
                <td class="text-muted"><?= $i + 1 ?></td>
                <td>
                  <i class="fas fa-file-alt me-2 text-warning"></i>
                  <span class="fw-medium"><?= htmlspecialchars($b['name']) ?></span>
                  <?php if ($i === 0): ?>
                    <span class="badge bg-success ms-1">Terbaru</span>
                  <?php endif; ?>
                </td>
                <td><?= date('d M Y, H:i:s', $b['modified']) ?></td>
                <td><?= formatSize($b['size']) ?></td>
                <td class="text-end">
                  <a href="<?= APP_URL ?>/backup_list.php?download=<?= urlencode($b['name']) ?>"
                     class="btn btn-sm btn-outline-primary me-1">
                    <i class="fas fa-download"></i> Download
                  </a>
                  <a href="<?= APP_URL ?>/backup_list.php?delete=<?= urlencode($b['name']) ?>"
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Hapus file backup ini?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="px-3 py-2 text-muted small border-top">
          Total: <strong><?= count($backups) ?></strong> file backup
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php include ROOT_PATH . '/app/views/layouts/admin_footer.php'; ?>
