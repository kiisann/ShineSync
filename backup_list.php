<?php
date_default_timezone_set('Asia/Jakarta');

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

// DOWNLOAD
if (isset($_GET['download'])) {
    $filename = basename($_GET['download']);
    $filepath = $backup_dir . $filename;

    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }

    header('Location: ' . APP_URL . '/admin/backup-list?status=fail&message=' . urlencode('File tidak ditemukan.'));
    exit;
}

// DELETE
if (isset($_GET['delete'])) {
    $filename = basename($_GET['delete']);
    $filepath = $backup_dir . $filename;

    if (file_exists($filepath)) {
        unlink($filepath);

        header(
            'Location: ' . APP_URL .
            '/admin/backup-list?status=success&message=' .
            urlencode('File backup berhasil dihapus.')
        );
    } else {
        header(
            'Location: ' . APP_URL .
            '/admin/backup-list?status=fail&message=' .
            urlencode('File tidak ditemukan.')
        );
    }

    exit;
}

// Ambil daftar backup
$files = glob($backup_dir . 'shinesync_backup_*.sql');

$backups = [];

if ($files) {
    foreach ($files as $f) {
        $backups[] = [
            'name'     => basename($f),
            'size'     => filesize($f),
            'modified' => filemtime($f)
        ];
    }

    usort($backups, fn($a, $b) => $b['modified'] - $a['modified']);
}

$status  = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;

function formatSize(int $bytes): string
{
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    }

    if ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }

    return $bytes . ' B';
}

include ROOT_PATH . '/app/views/layouts/admin_header.php';
?>

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">
                <i class="fas fa-database text-warning me-2"></i>
                Backup Database
            </h4>
            <small class="text-muted">
                Kelola file backup database ShineSync
            </small>
        </div>

        <a href="<?= APP_URL ?>/admin/backup"
           class="btn btn-warning"
           onclick="return confirm('Buat backup database sekarang?')">
            <i class="fas fa-plus-circle me-1"></i>
            Backup Sekarang
        </a>
    </div>

    <?php if ($status === 'success'): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($status === 'fail'): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <?php if (empty($backups)): ?>

                <div class="text-center py-5">
                    <i class="fas fa-database fa-3x text-muted mb-3"></i>
                    <p>Belum ada file backup.</p>
                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Tanggal</th>
                                <th>Ukuran</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($backups as $i => $b): ?>

                            <tr>
                                <td><?= $i + 1 ?></td>

                                <td>
                                    <?= htmlspecialchars($b['name']) ?>
                                </td>

                                <td>
                                    <?= date('d M Y H:i:s', $b['modified']) ?>
                                </td>

                                <td>
                                    <?= formatSize($b['size']) ?>
                                </td>

                                <td class="text-end">

                                    <a href="<?= APP_URL ?>/admin/backup-list?download=<?= urlencode($b['name']) ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        Download
                                    </a>

                                    <a href="<?= APP_URL ?>/admin/backup-list?delete=<?= urlencode($b['name']) ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Hapus backup ini?')">
                                        Hapus
                                    </a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<?php include ROOT_PATH . '/app/views/layouts/admin_footer.php'; ?>