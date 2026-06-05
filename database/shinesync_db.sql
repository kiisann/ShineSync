-- ============================================================
-- ShineSync Database - Sistem Informasi Penjualan Perhiasan
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- 1. CREATE DATABASE
DROP DATABASE IF EXISTS shinesync_db;
CREATE DATABASE shinesync_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shinesync_db;

-- ============================================================
-- 2. CREATE TABLES
-- ============================================================

-- Table: users
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    phone       VARCHAR(20),
    address     TEXT,
    avatar      VARCHAR(255) DEFAULT 'default.png',
    role        ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB;

-- Table: categories
CREATE TABLE categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    description TEXT,
    image       VARCHAR(255),
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB;

-- Table: products
CREATE TABLE products (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id  INT UNSIGNED NOT NULL,
    name         VARCHAR(200) NOT NULL,
    slug         VARCHAR(220) NOT NULL UNIQUE,
    description  TEXT,
    price        DECIMAL(15,2) NOT NULL DEFAULT 0,
    stock        INT NOT NULL DEFAULT 0,
    weight       DECIMAL(8,2) DEFAULT 0,
    material     VARCHAR(100),
    image        VARCHAR(255),
    is_featured  TINYINT(1) DEFAULT 0,
    is_active    TINYINT(1) DEFAULT 1,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_category  (category_id),
    INDEX idx_featured  (is_featured),
    INDEX idx_active    (is_active),
    INDEX idx_slug      (slug)
) ENGINE=InnoDB;

-- Table: product_images
CREATE TABLE product_images (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id  INT UNSIGNED NOT NULL,
    image       VARCHAR(255) NOT NULL,
    is_primary  TINYINT(1) DEFAULT 0,
    sort_order  INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB;

-- Table: carts
CREATE TABLE carts (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_cart (user_id)
) ENGINE=InnoDB;

-- Table: cart_details
CREATE TABLE cart_details (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id    INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity   INT NOT NULL DEFAULT 1,
    price      DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id)    REFERENCES carts(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uq_cart_product (cart_id, product_id)
) ENGINE=InnoDB;

-- Table: orders
CREATE TABLE orders (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    order_number    VARCHAR(50) NOT NULL UNIQUE,
    total_amount    DECIMAL(15,2) NOT NULL DEFAULT 0,
    discount        DECIMAL(15,2) DEFAULT 0,
    grand_total     DECIMAL(15,2) NOT NULL DEFAULT 0,
    loyalty_points  INT DEFAULT 0,
    shipping_name   VARCHAR(100),
    shipping_phone  VARCHAR(20),
    shipping_address TEXT,
    notes           TEXT,
    status          ENUM('pending','confirmed','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_user   (user_id),
    INDEX idx_status (status),
    INDEX idx_order_number (order_number)
) ENGINE=InnoDB;

-- Table: order_details
CREATE TABLE order_details (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id    INT UNSIGNED NOT NULL,
    product_id  INT UNSIGNED NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    quantity    INT NOT NULL DEFAULT 1,
    price       DECIMAL(15,2) NOT NULL,
    subtotal    DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order   (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB;

-- Table: payments
CREATE TABLE payments (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id        INT UNSIGNED NOT NULL UNIQUE,
    amount          DECIMAL(15,2) NOT NULL,
    method          ENUM('transfer','qris','cod') DEFAULT 'transfer',
    bank_name       VARCHAR(50),
    account_number  VARCHAR(50),
    proof_image     VARCHAR(255),
    status          ENUM('pending','verified','rejected') DEFAULT 'pending',
    verified_at     TIMESTAMP NULL DEFAULT NULL,
    verified_by     INT UNSIGNED NULL,
    notes           TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id)    REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id)  ON DELETE SET NULL,
    INDEX idx_order  (order_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Table: reviews
CREATE TABLE reviews (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    product_id  INT UNSIGNED NOT NULL,
    order_id    INT UNSIGNED NOT NULL,
    rating      TINYINT NOT NULL DEFAULT 5,
    comment     TEXT,
    is_approved TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id)  REFERENCES orders(id)   ON DELETE CASCADE,
    UNIQUE KEY uq_user_product_order (user_id, product_id, order_id),
    INDEX idx_product (product_id),
    INDEX idx_rating  (rating)
) ENGINE=InnoDB;

-- Table: wishlist
CREATE TABLE wishlist (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    product_id  INT UNSIGNED NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uq_wishlist (user_id, product_id)
) ENGINE=InnoDB;

-- Table: activity_logs
CREATE TABLE activity_logs (
    id_log     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user    INT UNSIGNED NOT NULL,
    aktivitas  VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_log (id_user),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ============================================================
-- 3. VIEWS (WAJIB PDD)
-- ============================================================

-- VIEW 1: Laporan Penjualan (digunakan di admin/reports)
CREATE OR REPLACE VIEW view_laporan_penjualan AS
SELECT
    o.id                                        AS id_order,
    o.order_number,
    u.name                                      AS nama_customer,
    u.email,
    DATE_FORMAT(o.created_at, '%d %M %Y')      AS tanggal_pesanan,
    o.total_amount,
    o.discount,
    o.grand_total,
    o.status                                    AS status_pesanan,
    p.status                                    AS status_pembayaran,
    p.method                                    AS metode_pembayaran,
    COUNT(od.id)                                AS jumlah_item
FROM orders o
INNER JOIN users u         ON o.user_id   = u.id
INNER JOIN order_details od ON o.id       = od.order_id
LEFT  JOIN payments p       ON o.id       = p.order_id
GROUP BY o.id, o.order_number, u.name, u.email,
         o.created_at, o.total_amount, o.discount, o.grand_total,
         o.status, p.status, p.method;

-- VIEW 2: Produk Terlaris (digunakan di dashboard admin)
CREATE OR REPLACE VIEW view_produk_terlaris AS
SELECT
    p.id,
    p.name                                  AS nama_produk,
    p.image,
    c.name                                  AS kategori,
    p.price,
    p.stock,
    SUM(od.quantity)                        AS total_terjual,
    SUM(od.subtotal)                        AS total_pendapatan,
    COALESCE(AVG(r.rating), 0)             AS rata_rating,
    COUNT(DISTINCT r.id)                    AS jumlah_review
FROM products p
INNER JOIN categories c     ON p.category_id = c.id
LEFT  JOIN order_details od ON p.id          = od.product_id
LEFT  JOIN orders o         ON od.order_id   = o.id AND o.status != 'cancelled'
LEFT  JOIN reviews r        ON p.id          = r.product_id AND r.is_approved = 1
WHERE p.is_active = 1
GROUP BY p.id, p.name, p.image, c.name, p.price, p.stock
ORDER BY total_terjual DESC;

-- VIEW 3: Customer Aktif (digunakan di dashboard admin)
CREATE OR REPLACE VIEW view_customer_aktif AS
SELECT
    u.id,
    u.name,
    u.email,
    u.phone,
    u.created_at                            AS bergabung,
    COUNT(DISTINCT o.id)                    AS jumlah_transaksi,
    COALESCE(SUM(o.grand_total), 0)        AS total_belanja,
    COALESCE(MAX(o.created_at), NULL)      AS transaksi_terakhir,
    COUNT(DISTINCT r.id)                    AS jumlah_review
FROM users u
LEFT JOIN orders  o ON u.id = o.user_id  AND o.status != 'cancelled'
LEFT JOIN reviews r ON u.id = r.user_id
WHERE u.role = 'customer' AND u.is_active = 1
GROUP BY u.id, u.name, u.email, u.phone, u.created_at
ORDER BY total_belanja DESC;

-- ============================================================
-- 4. CUSTOM FUNCTIONS (WAJIB PDD)
-- ============================================================

DELIMITER $$

-- FUNCTION 1: HitungDiskonMember
-- Diskon 10% jika total >= 1.000.000, selain itu 0%
CREATE FUNCTION HitungDiskonMember(total DECIMAL(15,2))
RETURNS DECIMAL(15,2)
DETERMINISTIC
BEGIN
    DECLARE diskon DECIMAL(15,2);
    IF total >= 1000000 THEN
        SET diskon = total * 0.10;
    ELSE
        SET diskon = 0;
    END IF;
    RETURN diskon;
END$$

-- FUNCTION 2: HitungPoinLoyalitas
-- 1 poin per Rp 10.000 transaksi
CREATE FUNCTION HitungPoinLoyalitas(total DECIMAL(15,2))
RETURNS INT
DETERMINISTIC
BEGIN
    RETURN FLOOR(total / 10000);
END$$

DELIMITER ;

-- ============================================================
-- 5. STORED PROCEDURES (WAJIB PDD)
-- ============================================================

DELIMITER $$

-- SP 1: Tambah Produk
CREATE PROCEDURE sp_insert_produk(
    IN p_category_id INT,
    IN p_name        VARCHAR(200),
    IN p_slug        VARCHAR(220),
    IN p_description TEXT,
    IN p_price       DECIMAL(15,2),
    IN p_stock       INT,
    IN p_weight      DECIMAL(8,2),
    IN p_material    VARCHAR(100),
    IN p_image       VARCHAR(255),
    IN p_is_featured TINYINT
)
BEGIN
    INSERT INTO products (category_id, name, slug, description, price, stock, weight, material, image, is_featured, is_active)
    VALUES (p_category_id, p_name, p_slug, p_description, p_price, p_stock, p_weight, p_material, p_image, p_is_featured, 1);
    SELECT LAST_INSERT_ID() AS new_id;
END$$

-- SP 2: Ambil Produk (0 = semua, >0 = by ID)
CREATE PROCEDURE sp_select_produk(IN p_id INT)
BEGIN
    IF p_id = 0 THEN
        SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC;
    ELSE
        SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id = p_id
        LIMIT 1;
    END IF;
END$$

-- SP 3: Update Produk
CREATE PROCEDURE sp_update_produk(
    IN p_id          INT,
    IN p_category_id INT,
    IN p_name        VARCHAR(200),
    IN p_slug        VARCHAR(220),
    IN p_description TEXT,
    IN p_price       DECIMAL(15,2),
    IN p_stock       INT,
    IN p_weight      DECIMAL(8,2),
    IN p_material    VARCHAR(100),
    IN p_image       VARCHAR(255),
    IN p_is_featured TINYINT
)
BEGIN
    UPDATE products SET
        category_id = p_category_id,
        name        = p_name,
        slug        = p_slug,
        description = p_description,
        price       = p_price,
        stock       = p_stock,
        weight      = p_weight,
        material    = p_material,
        image       = IF(p_image != '', p_image, image),
        is_featured = p_is_featured,
        updated_at  = CURRENT_TIMESTAMP
    WHERE id = p_id;
END$$

-- SP 4: Hapus Produk (soft delete)
CREATE PROCEDURE sp_delete_produk(IN p_id INT)
BEGIN
    UPDATE products SET is_active = 0 WHERE id = p_id;
END$$

DELIMITER ;

-- ============================================================
-- 6. TRIGGERS (WAJIB PDD)
-- ============================================================

DELIMITER $$

-- TRIGGER: Kurangi stok otomatis saat order_details diinsert
CREATE TRIGGER tr_kurangi_stok
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    UPDATE products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END$$

-- TRIGGER: Hitung ulang total order otomatis saat order_details diinsert
-- Catatan: pada schema ShineSync, total_harga disimpan sebagai orders.total_amount.
CREATE TRIGGER tr_update_total_order
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    UPDATE orders
    SET total_amount = (
        SELECT COALESCE(SUM(subtotal), 0)
        FROM order_details
        WHERE order_id = NEW.order_id
    )
    WHERE id = NEW.order_id;
END$$

-- TRIGGER: Catat aktivitas customer saat membuat review
CREATE TRIGGER tr_log_review
AFTER INSERT ON reviews
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (id_user, aktivitas)
    VALUES (
        NEW.user_id,
        CONCAT('Customer memberikan review untuk produk ID ', NEW.product_id, ' pada order ID ', NEW.order_id)
    );
END$$

-- TRIGGER: Ubah status order otomatis saat pembayaran disetujui
-- Catatan: pada schema ShineSync, status_verifikasi = 'Disetujui' disimpan sebagai payments.status = 'verified',
-- dan status_order = 'Diproses' disimpan sebagai orders.status = 'processing'.
CREATE TRIGGER tr_auto_order_status
AFTER UPDATE ON payments
FOR EACH ROW
BEGIN
    IF NEW.status = 'verified' AND OLD.status <> 'verified' THEN
        UPDATE orders
        SET status = 'processing',
            updated_at = CURRENT_TIMESTAMP
        WHERE id = NEW.order_id
          AND status NOT IN ('delivered', 'cancelled');
    END IF;
END$$

DELIMITER ;

-- ============================================================
-- 7. DATA DUMMY
-- ============================================================

-- Admin & Customer Accounts
-- Password: password (hashed dengan PHP password_hash)
INSERT INTO users (name, email, password, phone, address, role, is_active) VALUES
('Administrator', 'admin@shinesync.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '081234567890', 'Jakarta Pusat', 'admin', 1),
('Siti Rahayu', 'siti@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '081298765432', 'Jl. Merdeka No. 12, Bandung', 'customer', 1),
('Budi Santoso', 'budi@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '085611223344', 'Jl. Sudirman No. 45, Surabaya', 'customer', 1),
('Dewi Lestari', 'dewi@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '087733445566', 'Jl. Diponegoro No. 8, Yogyakarta', 'customer', 1),
('Rina Anjani', 'rina@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '082255667788', 'Jl. Ahmad Yani No. 20, Medan', 'customer', 1);

-- Categories
INSERT INTO categories (name, slug, description, is_active) VALUES
('Cincin',    'cincin',    'Koleksi cincin mewah dari emas, perak, dan platinum', 1),
('Kalung',    'kalung',    'Kalung elegan untuk berbagai kesempatan', 1),
('Gelang',    'gelang',    'Gelang cantik untuk melengkapi penampilan', 1),
('Anting',    'anting',    'Anting eksklusif dengan desain modern', 1),
('Aksesoris', 'aksesoris', 'Aksesoris perhiasan lainnya', 1);

-- Products (via direct INSERT — SP akan digunakan dari PHP)
INSERT INTO products (category_id, name, slug, description, price, stock, weight, material, image, is_featured, is_active) VALUES
(1, 'Cincin Diamond Solitaire 18K', 'cincin-diamond-solitaire-18k', 'Cincin elegan berlapis emas 18 karat dengan berlian asli. Cocok untuk lamaran dan pernikahan.', 4500000, 15, 5.2, 'Emas 18K + Berlian', 'cincin-diamond.jpg', 1, 1),
(1, 'Cincin Rose Gold Couple', 'cincin-rose-gold-couple', 'Pasangan cincin rose gold yang romantis. Tersedia dalam berbagai ukuran.', 2800000, 20, 4.8, 'Rose Gold 14K', 'cincin-rosegold.jpg', 1, 1),
(1, 'Cincin Perak Minimalis', 'cincin-perak-minimalis', 'Cincin perak sterling dengan desain minimalis modern. Sempurna untuk sehari-hari.', 350000, 50, 3.5, 'Perak 925', 'cincin-perak.jpg', 0, 1),
(2, 'Kalung Berlian Choker', 'kalung-berlian-choker', 'Kalung choker mewah dengan berlian berkilau. Tampil glamor di setiap kesempatan.', 6800000, 8, 12.5, 'Emas 18K + Berlian', 'kalung-choker.jpg', 1, 1),
(2, 'Kalung Liontin Hati Gold', 'kalung-liontin-hati-gold', 'Kalung dengan liontin berbentuk hati dari emas kuning. Hadiah sempurna untuk orang terkasih.', 1850000, 25, 8.2, 'Emas 14K', 'kalung-hati.jpg', 1, 1),
(2, 'Kalung Pearl Clasic', 'kalung-pearl-classic', 'Kalung mutiara klasik yang timeless. Cocok untuk acara formal maupun kasual.', 1200000, 30, 15.0, 'Mutiara Asli + Emas Putih', 'kalung-mutiara.jpg', 0, 1),
(3, 'Gelang Charm Gold', 'gelang-charm-gold', 'Gelang emas dengan berbagai charm lucu. Dapat disesuaikan dengan gaya Anda.', 2200000, 18, 6.8, 'Emas 14K', 'gelang-charm.jpg', 1, 1),
(3, 'Gelang Berlian Tennis', 'gelang-berlian-tennis', 'Gelang tennis mewah dengan deretan berlian. Tampilan premium dan elegan.', 8500000, 5, 9.5, 'Emas 18K + Berlian', 'gelang-tennis.jpg', 1, 1),
(3, 'Gelang Silver Adjustable', 'gelang-silver-adjustable', 'Gelang perak yang dapat disesuaikan ukurannya. Desain simpel dan modern.', 280000, 60, 4.2, 'Perak 925', 'gelang-silver.jpg', 0, 1),
(4, 'Anting Diamond Drop', 'anting-diamond-drop', 'Anting mewah dengan berlian bergelantungan. Sempurna untuk acara spesial.', 3600000, 12, 3.8, 'Emas 18K + Berlian', 'anting-diamond.jpg', 1, 1),
(4, 'Anting Mutiara Klasik', 'anting-mutiara-klasik', 'Anting mutiara klasik yang tidak pernah ketinggalan zaman.', 950000, 35, 2.9, 'Mutiara + Emas Putih', 'anting-mutiara.jpg', 0, 1),
(4, 'Anting Hoop Gold', 'anting-hoop-gold', 'Anting hoop emas yang trendy. Tersedia dalam ukuran S, M, L.', 1100000, 40, 3.2, 'Emas 14K', 'anting-hoop.jpg', 0, 1),
(5, 'Brosco Bunga Emas', 'brosco-bunga-emas', 'Bros cantik berbentuk bunga dari emas. Aksesori sempurna untuk hijab maupun baju.', 750000, 22, 7.5, 'Emas 14K', 'bros-bunga.jpg', 0, 1),
(5, 'Jepit Rambut Crystal', 'jepit-rambut-crystal', 'Jepit rambut mewah dihiasi kristal Swarovski. Tampil elegan di setiap acara.', 420000, 45, 2.5, 'Stainless + Crystal', 'jepit-crystal.jpg', 0, 1);

-- Orders & Order Details (data dummy untuk laporan)
INSERT INTO orders (user_id, order_number, total_amount, discount, grand_total, loyalty_points, shipping_name, shipping_phone, shipping_address, status) VALUES
(2, 'SS-2025-0001', 4500000, 450000, 4050000, 405, 'Siti Rahayu', '081298765432', 'Jl. Merdeka No. 12, Bandung', 'delivered'),
(3, 'SS-2025-0002', 2800000, 0, 2800000, 280, 'Budi Santoso', '085611223344', 'Jl. Sudirman No. 45, Surabaya', 'delivered'),
(4, 'SS-2025-0003', 6800000, 680000, 6120000, 612, 'Dewi Lestari', '087733445566', 'Jl. Diponegoro No. 8, Yogyakarta', 'processing'),
(2, 'SS-2025-0004', 1850000, 0, 1850000, 185, 'Siti Rahayu', '081298765432', 'Jl. Merdeka No. 12, Bandung', 'delivered'),
(5, 'SS-2025-0005', 8500000, 850000, 7650000, 765, 'Rina Anjani', '082255667788', 'Jl. Ahmad Yani No. 20, Medan', 'shipped'),
(3, 'SS-2025-0006', 350000, 0, 350000, 35, 'Budi Santoso', '085611223344', 'Jl. Sudirman No. 45, Surabaya', 'pending');

-- Disable trigger temporarily for dummy data
SET @OLD_SQL_SAFE_UPDATES = @@SQL_SAFE_UPDATES;
SET SQL_SAFE_UPDATES = 0;

INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal) VALUES
(1, 1, 'Cincin Diamond Solitaire 18K', 1, 4500000, 4500000),
(2, 2, 'Cincin Rose Gold Couple', 1, 2800000, 2800000),
(3, 4, 'Kalung Berlian Choker', 1, 6800000, 6800000),
(4, 5, 'Kalung Liontin Hati Gold', 1, 1850000, 1850000),
(5, 8, 'Gelang Berlian Tennis', 1, 8500000, 8500000),
(6, 3, 'Cincin Perak Minimalis', 1, 350000, 350000);

-- Revert stock changes from trigger (since these are dummy orders)
UPDATE products SET stock = 15 WHERE id = 1;
UPDATE products SET stock = 20 WHERE id = 2;
UPDATE products SET stock = 50 WHERE id = 3;
UPDATE products SET stock = 8  WHERE id = 4;
UPDATE products SET stock = 25 WHERE id = 5;
UPDATE products SET stock = 5  WHERE id = 8;

SET SQL_SAFE_UPDATES = @OLD_SQL_SAFE_UPDATES;

-- Payments
INSERT INTO payments (order_id, amount, method, bank_name, account_number, proof_image, status, verified_at, verified_by) VALUES
(1, 4050000, 'transfer', 'BCA', '1234567890', 'proof_001.jpg', 'verified', '2025-01-15 10:30:00', 1),
(2, 2800000, 'transfer', 'Mandiri', '0987654321', 'proof_002.jpg', 'verified', '2025-01-18 14:20:00', 1),
(3, 6120000, 'qris', NULL, NULL, 'proof_003.jpg', 'verified', '2025-02-01 09:15:00', 1),
(4, 1850000, 'transfer', 'BNI', '1122334455', 'proof_004.jpg', 'verified', '2025-02-10 16:45:00', 1),
(5, 7650000, 'transfer', 'BCA', '5566778899', 'proof_005.jpg', 'pending', NULL, NULL),
(6, 350000, 'transfer', 'BRI', '9988776655', NULL, 'pending', NULL, NULL);

-- Reviews
INSERT INTO reviews (user_id, product_id, order_id, rating, comment, is_approved) VALUES
(2, 1, 1, 5, 'Cincin yang sangat cantik! Berkualitas tinggi dan sesuai ekspektasi. Pengiriman cepat. Sangat recommended!', 1),
(3, 2, 2, 4, 'Kualitas bagus, rose gold-nya cantik. Sedikit lebih kecil dari ekspektasi tapi overall memuaskan.', 1),
(4, 4, 3, 5, 'Kalung berliannya luar biasa! Kilauannya sangat indah. Worth every penny!', 1),
(2, 5, 4, 5, 'Kalung liontin hatinya sangat cantik, saya sangat suka! Akan beli lagi.', 1);

-- Wishlist
INSERT INTO wishlist (user_id, product_id) VALUES
(2, 7), (2, 8), (2, 10),
(3, 4), (3, 5), (3, 11),
(4, 1), (4, 8),
(5, 2), (5, 6);

SET FOREIGN_KEY_CHECKS = 1;
