# 💍✨ ShineSync (Proyek UAP)

Proyek ini merupakan sistem e-commerce perhiasan berbasis web yang dibangun menggunakan PHP dan MySQL dengan arsitektur MVC (Model-View-Controller). Tujuannya sebagai platform jual beli perhiasan yang dilengkapi fitur manajemen produk, pemesanan, pembayaran, ulasan, dan laporan penjualan, dengan memanfaatkan stored procedure, function, trigger, view database, dan sistem loyalitas poin untuk pelanggan.

<img width="1898" height="946" alt="image" src="https://github.com/user-attachments/assets/c8ddef2b-aa61-4a19-949a-772cf059a782" />

## 📌 Detail Konsep
Stored procedure digunakan sebagai lapisan utama operasi CRUD pada produk, sesuai kebutuhan PDD (Procedure-Driven Design). Procedure disimpan di database sehingga menjamin konsistensi, efisiensi, dan keamanan eksekusi di sistem multi-user.
Procedure yang diimplementasikan pada Product.php
**sp_select_produk(p_id) — Mengambil semua produk atau berdasarkan ID**
```sql
public function getAllViaSP(): array
{
    return $this->db->callProcedure('sp_select_produk', [0]);
}

public function findByIdViaSP(int $id): ?array
{
    $rows = $this->db->callProcedure('sp_select_produk', [$id]);
    return $rows[0] ?? null;
}
```
**sp_insert_produk(...) — Menambahkan produk baru**
```sql
public function createViaSP(array $d): int
{
    $rows = $this->db->callProcedure('sp_insert_produk', [
        (int)$d['category_id'], $d['name'], $d['slug'],
        $d['description'] ?? '', (float)$d['price'], (int)$d['stock'],
        (float)($d['weight'] ?? 0), $d['material'] ?? '', $d['image'] ?? '',
        (int)($d['is_featured'] ?? 0)
    ]);
    return (int)($rows[0]['new_id'] ?? 0);
}
```
## 📄 backup.php
```sql
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Jakarta');

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Session.php';

Session::start();

if (!Session::isLoggedIn() || !Session::isAdmin()) {
    header('Location: ' . APP_URL . '/admin/login');
    exit;
}

$backup_dir = ROOT_PATH . '/storage/backups/';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$date       = date('Y-m-d_H-i-s');
$backupFile = $backup_dir . 'shinesync_backup_' . $date . '.sql';

$mysqldump_path = 'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe';

$db_host = DB_HOST;
$db_user = DB_USER;
$db_pass = DB_PASS;
$db_name = DB_NAME;
$db_port = DB_PORT;

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

    if (file_exists($backupFile)) {
        unlink($backupFile);
    }
    $message .= "\nBackup gagal.";
    $status  = 'fail';
}
header('Location: ' . APP_URL . '/admin/backup-list?status=' . $status . '&message=' . urlencode($message));
exit;

```



