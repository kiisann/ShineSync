<?php
// config/database.php — Konfigurasi koneksi MySQL (mysqli)
define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_NAME',     'shinesync_db');
define('DB_PORT',     3306);
define('DB_CHARSET',  'utf8mb4');

// App Config
define('APP_NAME',    'ShineSync');
define('APP_URL',     'http://localhost/ShineSyncNew');
define('APP_VERSION', '1.0.0');

// Upload Paths
define('UPLOAD_PATH',         __DIR__ . '/../uploads/');
define('UPLOAD_PRODUCTS',     UPLOAD_PATH . 'products/');
define('UPLOAD_PAYMENTS',     UPLOAD_PATH . 'payments/');
define('UPLOAD_URL',          APP_URL . '/uploads/');
define('DEFAULT_PRODUCT_IMG', APP_URL . '/public/images/no-image.svg');
define('DEFAULT_AVATAR',      APP_URL . '/public/images/default-avatar.svg');