<?php
/**
 * ShineSync — Backup Database
 * Letakkan file ini di root project: ShineSyncNew/backup.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Jakarta');

// Bootstrap ShineSync
define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Session.php';

Session::start();

// Hanya admin yang boleh akses
if (!Session::isLoggedIn() || !Session::isAdmin()) {
    header('Location: ' . APP_URL . '/admin/login');
    exit;
}

// Siapkan direktori backup
$backup_dir = ROOT_PATH . '/storage/backups/';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

// Nama file backup dengan timestamp
$date       = date('Y-m-d_H-i-s');
$backupFile = $backup_dir . 'shinesync_backup_' . $date . '.sql';

// Path mysqldump — sesuaikan jika perlu
// Laragon (Windows):
$mysqldump_path = 'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe';
// Jika pakai XAMPP ganti dengan:
// $mysqldump_path = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
// Jika di Linux/server, cukup pakai 'mysqldump' (tanpa path penuh)
// $mysqldump_path = 'mysqldump';

// Ambil konfigurasi dari config/database.php
$db_host = DB_HOST;
$db_user = DB_USER;
$db_pass = DB_PASS;
$db_name = DB_NAME;
$db_port = DB_PORT;

// Bangun perintah mysqldump
$command = '"' . $mysqldump_path . '"'
    . ' -h ' . escapeshellarg($db_host)
    . ' -P ' . (int)$db_port
    . ' -u ' . escapeshellarg($db_user)
    . ($db_pass !== '' ? ' -p' . escapeshellarg($db_pass) : '')
    . ' ' . escapeshellarg($db_name)
    . ' --result-file=' . escapeshellarg($backupFile)
    . ' 2>&1';

exec($command, $output, $return_var);

$message = 'Command return code: ' . $return_var . "\n" . implode("\n", $output);

if ($return_var === 0 && file_exists($backupFile) && filesize($backupFile) > 0) {
    $message .= "\nBackup berhasil disimpan: " . $backupFile;
    $status  = 'success';
} else {
    // Hapus file kosong jika ada
    if (file_exists($backupFile)) {
        unlink($backupFile);
    }
    $message .= "\nBackup gagal.";
    $status  = 'fail';
}
header('Location: ' . APP_URL . '/admin/backup-list?status=' . $status . '&message=' . urlencode($message));
exit;
