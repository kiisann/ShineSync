-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2026 at 12:13 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shinesync_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_produk` (IN `p_id` INT)   BEGIN
    UPDATE products SET is_active = 0 WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_produk` (IN `p_category_id` INT, IN `p_name` VARCHAR(200), IN `p_slug` VARCHAR(220), IN `p_description` TEXT, IN `p_price` DECIMAL(15,2), IN `p_stock` INT, IN `p_weight` DECIMAL(8,2), IN `p_material` VARCHAR(100), IN `p_image` VARCHAR(255), IN `p_is_featured` TINYINT)   BEGIN
    INSERT INTO products (category_id, name, slug, description, price, stock, weight, material, image, is_featured, is_active)
    VALUES (p_category_id, p_name, p_slug, p_description, p_price, p_stock, p_weight, p_material, p_image, p_is_featured, 1);
    SELECT LAST_INSERT_ID() AS new_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_select_produk` (IN `p_id` INT)   BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_produk` (IN `p_id` INT, IN `p_category_id` INT, IN `p_name` VARCHAR(200), IN `p_slug` VARCHAR(220), IN `p_description` TEXT, IN `p_price` DECIMAL(15,2), IN `p_stock` INT, IN `p_weight` DECIMAL(8,2), IN `p_material` VARCHAR(100), IN `p_image` VARCHAR(255), IN `p_is_featured` TINYINT)   BEGIN
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

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `HitungDiskonMember` (`total` DECIMAL(15,2)) RETURNS DECIMAL(15,2) DETERMINISTIC BEGIN
    DECLARE diskon DECIMAL(15,2);
    IF total >= 1000000 THEN
        SET diskon = total * 0.10;
    ELSE
        SET diskon = 0;
    END IF;
    RETURN diskon;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `HitungPoinLoyalitas` (`total` DECIMAL(15,2)) RETURNS INT DETERMINISTIC BEGIN
    RETURN FLOOR(total / 10000);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id_log` int UNSIGNED NOT NULL,
  `id_user` int UNSIGNED NOT NULL,
  `aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id_log`, `id_user`, `aktivitas`, `created_at`) VALUES
(1, 2, 'Customer memberikan review untuk produk ID 1 pada order ID 1', '2026-06-05 10:30:38'),
(2, 3, 'Customer memberikan review untuk produk ID 2 pada order ID 2', '2026-06-05 10:30:38'),
(3, 4, 'Customer memberikan review untuk produk ID 4 pada order ID 3', '2026-06-05 10:30:38'),
(4, 2, 'Customer memberikan review untuk produk ID 5 pada order ID 4', '2026-06-05 10:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 6, '2026-06-05 11:01:00', '2026-06-05 11:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE `cart_details` (
  `id` int UNSIGNED NOT NULL,
  `cart_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_details`
--

INSERT INTO `cart_details` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(1, 1, 1, 1, '4500000.00', '2026-06-05 11:01:00'),
(2, 1, 2, 1, '2800000.00', '2026-06-05 11:01:02');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`) VALUES
(1, 'Cincin', 'cincin', 'Koleksi cincin mewah dari emas, perak, dan platinum', NULL, 1, '2026-06-05 10:30:37'),
(2, 'Kalung', 'kalung', 'Kalung elegan untuk berbagai kesempatan', NULL, 1, '2026-06-05 10:30:37'),
(3, 'Gelang', 'gelang', 'Gelang cantik untuk melengkapi penampilan', NULL, 1, '2026-06-05 10:30:37'),
(4, 'Anting', 'anting', 'Anting eksklusif dengan desain modern', NULL, 1, '2026-06-05 10:30:37'),
(5, 'Aksesoris', 'aksesoris', 'Aksesoris perhiasan lainnya', NULL, 1, '2026-06-05 10:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `discount`, `grand_total`, `loyalty_points`, `shipping_name`, `shipping_phone`, `shipping_address`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'SS-2025-0001', '4500000.00', '450000.00', '4050000.00', 405, 'Siti Rahayu', '081298765432', 'Jl. Merdeka No. 12, Bandung', NULL, 'delivered', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(2, 3, 'SS-2025-0002', '2800000.00', '0.00', '2800000.00', 280, 'Budi Santoso', '085611223344', 'Jl. Sudirman No. 45, Surabaya', NULL, 'delivered', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(3, 4, 'SS-2025-0003', '6800000.00', '680000.00', '6120000.00', 612, 'Dewi Lestari', '087733445566', 'Jl. Diponegoro No. 8, Yogyakarta', NULL, 'processing', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(4, 2, 'SS-2025-0004', '1850000.00', '0.00', '1850000.00', 185, 'Siti Rahayu', '081298765432', 'Jl. Merdeka No. 12, Bandung', NULL, 'delivered', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(5, 5, 'SS-2025-0005', '8500000.00', '850000.00', '7650000.00', 765, 'Rina Anjani', '082255667788', 'Jl. Ahmad Yani No. 20, Medan', NULL, 'shipped', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(6, 3, 'SS-2025-0006', '350000.00', '0.00', '350000.00', 35, 'Budi Santoso', '085611223344', 'Jl. Sudirman No. 45, Surabaya', NULL, 'pending', '2026-06-05 10:30:37', '2026-06-05 10:30:37');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `tr_frag_order_insert` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    IF NEW.status = 'pending' THEN
        INSERT INTO orders_pending
            (id, user_id, order_number, total_amount, discount, grand_total,
             loyalty_points, shipping_name, shipping_phone, shipping_address,
             notes, status, created_at, updated_at)
        VALUES
            (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
             NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
             NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);

    ELSEIF NEW.status IN ('confirmed', 'processing', 'shipped') THEN
        INSERT INTO orders_processing
            (id, user_id, order_number, total_amount, discount, grand_total,
             loyalty_points, shipping_name, shipping_phone, shipping_address,
             notes, status, created_at, updated_at)
        VALUES
            (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
             NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
             NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);

    ELSEIF NEW.status = 'delivered' THEN
        INSERT INTO orders_completed
            (id, user_id, order_number, total_amount, discount, grand_total,
             loyalty_points, shipping_name, shipping_phone, shipping_address,
             notes, status, created_at, updated_at)
        VALUES
            (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
             NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
             NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);

    ELSEIF NEW.status = 'cancelled' THEN
        INSERT INTO orders_cancelled
            (id, user_id, order_number, total_amount, discount, grand_total,
             loyalty_points, shipping_name, shipping_phone, shipping_address,
             notes, status, created_at, updated_at)
        VALUES
            (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
             NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
             NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_frag_order_update` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    -- Hanya proses jika status berubah
    IF NEW.status <> OLD.status THEN

        -- Hapus dari tabel fragmentasi lama
        IF OLD.status = 'pending' THEN
            DELETE FROM orders_pending WHERE id = OLD.id;

        ELSEIF OLD.status IN ('confirmed', 'processing', 'shipped') THEN
            DELETE FROM orders_processing WHERE id = OLD.id;

        ELSEIF OLD.status = 'delivered' THEN
            DELETE FROM orders_completed WHERE id = OLD.id;

        ELSEIF OLD.status = 'cancelled' THEN
            DELETE FROM orders_cancelled WHERE id = OLD.id;
        END IF;

        -- Insert ke tabel fragmentasi baru
        IF NEW.status = 'pending' THEN
            INSERT INTO orders_pending
                (id, user_id, order_number, total_amount, discount, grand_total,
                 loyalty_points, shipping_name, shipping_phone, shipping_address,
                 notes, status, created_at, updated_at)
            VALUES
                (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
                 NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
                 NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);

        ELSEIF NEW.status IN ('confirmed', 'processing', 'shipped') THEN
            INSERT INTO orders_processing
                (id, user_id, order_number, total_amount, discount, grand_total,
                 loyalty_points, shipping_name, shipping_phone, shipping_address,
                 notes, status, created_at, updated_at)
            VALUES
                (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
                 NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
                 NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);

        ELSEIF NEW.status = 'delivered' THEN
            INSERT INTO orders_completed
                (id, user_id, order_number, total_amount, discount, grand_total,
                 loyalty_points, shipping_name, shipping_phone, shipping_address,
                 notes, status, created_at, updated_at)
            VALUES
                (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
                 NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
                 NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);

        ELSEIF NEW.status = 'cancelled' THEN
            INSERT INTO orders_cancelled
                (id, user_id, order_number, total_amount, discount, grand_total,
                 loyalty_points, shipping_name, shipping_phone, shipping_address,
                 notes, status, created_at, updated_at)
            VALUES
                (NEW.id, NEW.user_id, NEW.order_number, NEW.total_amount, NEW.discount,
                 NEW.grand_total, NEW.loyalty_points, NEW.shipping_name, NEW.shipping_phone,
                 NEW.shipping_address, NEW.notes, NEW.status, NEW.created_at, NEW.updated_at);
        END IF;

    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders_aktif`
--

CREATE TABLE `orders_aktif` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders_aktif`
--

INSERT INTO `orders_aktif` (`id`, `user_id`, `order_number`, `total_amount`, `discount`, `grand_total`, `loyalty_points`, `shipping_name`, `shipping_phone`, `shipping_address`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(6, 3, 'SS-2025-0006', '350000.00', '0.00', '350000.00', 35, 'Budi Santoso', '085611223344', 'Jl. Sudirman No. 45, Surabaya', NULL, 'pending', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(3, 4, 'SS-2025-0003', '6800000.00', '680000.00', '6120000.00', 612, 'Dewi Lestari', '087733445566', 'Jl. Diponegoro No. 8, Yogyakarta', NULL, 'processing', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(5, 5, 'SS-2025-0005', '8500000.00', '850000.00', '7650000.00', 765, 'Rina Anjani', '082255667788', 'Jl. Ahmad Yani No. 20, Medan', NULL, 'shipped', '2026-06-05 10:30:37', '2026-06-05 10:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `orders_arsip`
--

CREATE TABLE `orders_arsip` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders_arsip`
--

INSERT INTO `orders_arsip` (`id`, `user_id`, `order_number`, `total_amount`, `discount`, `grand_total`, `loyalty_points`, `shipping_name`, `shipping_phone`, `shipping_address`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'SS-2025-0001', '4500000.00', '450000.00', '4050000.00', 405, 'Siti Rahayu', '081298765432', 'Jl. Merdeka No. 12, Bandung', NULL, 'delivered', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(2, 3, 'SS-2025-0002', '2800000.00', '0.00', '2800000.00', 280, 'Budi Santoso', '085611223344', 'Jl. Sudirman No. 45, Surabaya', NULL, 'delivered', '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(4, 2, 'SS-2025-0004', '1850000.00', '0.00', '1850000.00', 185, 'Siti Rahayu', '081298765432', 'Jl. Merdeka No. 12, Bandung', NULL, 'delivered', '2026-06-05 10:30:37', '2026-06-05 10:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 1, 'Cincin Diamond Solitaire 18K', 1, '4500000.00', '4500000.00'),
(2, 2, 2, 'Cincin Rose Gold Couple', 1, '2800000.00', '2800000.00'),
(3, 3, 4, 'Kalung Berlian Choker', 1, '6800000.00', '6800000.00'),
(4, 4, 5, 'Kalung Liontin Hati Gold', 1, '1850000.00', '1850000.00'),
(5, 5, 8, 'Gelang Berlian Tennis', 1, '8500000.00', '8500000.00'),
(6, 6, 3, 'Cincin Perak Minimalis', 1, '350000.00', '350000.00');

--
-- Triggers `order_details`
--
DELIMITER $$
CREATE TRIGGER `tr_kurangi_stok` AFTER INSERT ON `order_details` FOR EACH ROW BEGIN
    UPDATE products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_update_total_order` AFTER INSERT ON `order_details` FOR EACH ROW BEGIN
    UPDATE orders
    SET total_amount = (
        SELECT COALESCE(SUM(subtotal), 0)
        FROM order_details
        WHERE order_id = NEW.order_id
    )
    WHERE id = NEW.order_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` enum('transfer','qris','cod') COLLATE utf8mb4_unicode_ci DEFAULT 'transfer',
  `bank_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` int UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `amount`, `method`, `bank_name`, `account_number`, `proof_image`, `status`, `verified_at`, `verified_by`, `notes`, `created_at`) VALUES
(1, 1, '4050000.00', 'transfer', 'BCA', '1234567890', 'proof_001.jpg', 'verified', '2025-01-15 03:30:00', 1, NULL, '2026-06-05 10:30:38'),
(2, 2, '2800000.00', 'transfer', 'Mandiri', '0987654321', 'proof_002.jpg', 'verified', '2025-01-18 07:20:00', 1, NULL, '2026-06-05 10:30:38'),
(3, 3, '6120000.00', 'qris', NULL, NULL, 'proof_003.jpg', 'verified', '2025-02-01 02:15:00', 1, NULL, '2026-06-05 10:30:38'),
(4, 4, '1850000.00', 'transfer', 'BNI', '1122334455', 'proof_004.jpg', 'verified', '2025-02-10 09:45:00', 1, NULL, '2026-06-05 10:30:38'),
(5, 5, '7650000.00', 'transfer', 'BCA', '5566778899', 'proof_005.jpg', 'pending', NULL, NULL, NULL, '2026-06-05 10:30:38'),
(6, 6, '350000.00', 'transfer', 'BRI', '9988776655', NULL, 'pending', NULL, NULL, NULL, '2026-06-05 10:30:38');

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `tr_auto_order_status` AFTER UPDATE ON `payments` FOR EACH ROW BEGIN
    IF NEW.status = 'verified' AND OLD.status <> 'verified' THEN
        UPDATE orders
        SET status = 'processing',
            updated_at = CURRENT_TIMESTAMP
        WHERE id = NEW.order_id
          AND status NOT IN ('delivered', 'cancelled');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `weight` decimal(8,2) DEFAULT '0.00',
  `material` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `weight`, `material`, `image`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cincin Diamond Solitaire 18K', 'cincin-diamond-solitaire-18k', 'Cincin elegan berlapis emas 18 karat dengan berlian asli. Cocok untuk lamaran dan pernikahan.', '4500000.00', 15, '5.20', 'Emas 18K + Berlian', 'cincin-diamond.jpg', 1, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:38'),
(2, 1, 'Cincin Rose Gold Couple', 'cincin-rose-gold-couple', 'Pasangan cincin rose gold yang romantis. Tersedia dalam berbagai ukuran.', '2800000.00', 20, '4.80', 'Rose Gold 14K', 'cincin-rosegold.jpg', 1, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:38'),
(3, 1, 'Cincin Perak Minimalis', 'cincin-perak-minimalis', 'Cincin perak sterling dengan desain minimalis modern. Sempurna untuk sehari-hari.', '350000.00', 50, '3.50', 'Perak 925', 'cincin-perak.jpg', 0, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:38'),
(4, 2, 'Kalung Berlian Choker', 'kalung-berlian-choker', 'Kalung choker mewah dengan berlian berkilau. Tampil glamor di setiap kesempatan.', '6800000.00', 8, '12.50', 'Emas 18K + Berlian', 'kalung-choker.jpg', 1, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:38'),
(5, 2, 'Kalung Liontin Hati Gold', 'kalung-liontin-hati-gold', 'Kalung dengan liontin berbentuk hati dari emas kuning. Hadiah sempurna untuk orang terkasih.', '1850000.00', 25, '8.20', 'Emas 14K', 'kalung-hati.jpg', 1, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:38'),
(6, 2, 'Kalung Pearl Clasic', 'kalung-pearl-classic', 'Kalung mutiara klasik yang timeless. Cocok untuk acara formal maupun kasual.', '1200000.00', 30, '15.00', 'Mutiara Asli + Emas Putih', 'kalung-mutiara.jpg', 0, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(7, 3, 'Gelang Charm Gold', 'gelang-charm-gold', 'Gelang emas dengan berbagai charm lucu. Dapat disesuaikan dengan gaya Anda.', '2200000.00', 18, '6.80', 'Emas 14K', 'gelang-charm.jpg', 1, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(8, 3, 'Gelang Berlian Tennis', 'gelang-berlian-tennis', 'Gelang tennis mewah dengan deretan berlian. Tampilan premium dan elegan.', '8500000.00', 5, '9.50', 'Emas 18K + Berlian', 'gelang-tennis.jpg', 1, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:38'),
(9, 3, 'Gelang Silver Adjustable', 'gelang-silver-adjustable', 'Gelang perak yang dapat disesuaikan ukurannya. Desain simpel dan modern.', '280000.00', 60, '4.20', 'Perak 925', 'gelang-silver.jpg', 0, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(10, 4, 'Anting Diamond Drop', 'anting-diamond-drop', 'Anting mewah dengan berlian bergelantungan. Sempurna untuk acara spesial.', '3600000.00', 12, '3.80', 'Emas 18K + Berlian', 'anting-diamond.jpg', 1, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(11, 4, 'Anting Mutiara Klasik', 'anting-mutiara-klasik', 'Anting mutiara klasik yang tidak pernah ketinggalan zaman.', '950000.00', 35, '2.90', 'Mutiara + Emas Putih', 'anting-mutiara.jpg', 0, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(12, 4, 'Anting Hoop Gold', 'anting-hoop-gold', 'Anting hoop emas yang trendy. Tersedia dalam ukuran S, M, L.', '1100000.00', 40, '3.20', 'Emas 14K', 'anting-hoop.jpg', 0, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(13, 5, 'Brosco Bunga Emas', 'brosco-bunga-emas', 'Bros cantik berbentuk bunga dari emas. Aksesori sempurna untuk hijab maupun baju.', '750000.00', 22, '7.50', 'Emas 14K', 'bros-bunga.jpg', 0, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(14, 5, 'Jepit Rambut Crystal', 'jepit-rambut-crystal', 'Jepit rambut mewah dihiasi kristal Swarovski. Tampil elegan di setiap acara.', '420000.00', 45, '2.50', 'Stainless + Crystal', 'jepit-crystal.jpg', 0, 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `rating` tinyint NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `order_id`, `rating`, `comment`, `is_approved`, `created_at`) VALUES
(1, 2, 1, 1, 5, 'Cincin yang sangat cantik! Berkualitas tinggi dan sesuai ekspektasi. Pengiriman cepat. Sangat recommended!', 1, '2026-06-05 10:30:38'),
(2, 3, 2, 2, 4, 'Kualitas bagus, rose gold-nya cantik. Sedikit lebih kecil dari ekspektasi tapi overall memuaskan.', 1, '2026-06-05 10:30:38'),
(3, 4, 4, 3, 5, 'Kalung berliannya luar biasa! Kilauannya sangat indah. Worth every penny!', 1, '2026-06-05 10:30:38'),
(4, 2, 5, 4, 5, 'Kalung liontin hatinya sangat cantik, saya sangat suka! Akan beli lagi.', 1, '2026-06-05 10:30:38');

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `tr_log_review` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN
    INSERT INTO activity_logs (id_user, aktivitas)
    VALUES (
        NEW.user_id,
        CONCAT('Customer memberikan review untuk produk ID ', NEW.product_id, ' pada order ID ', NEW.order_id)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `avatar`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@shinesync.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '081234567890', 'Jakarta Pusat', 'default.png', 'admin', 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(2, 'Siti Rahayu', 'siti@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '081298765432', 'Jl. Merdeka No. 12, Bandung', 'default.png', 'customer', 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(3, 'Budi Santoso', 'budi@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '085611223344', 'Jl. Sudirman No. 45, Surabaya', 'default.png', 'customer', 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(4, 'Dewi Lestari', 'dewi@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '087733445566', 'Jl. Diponegoro No. 8, Yogyakarta', 'default.png', 'customer', 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(5, 'Rina Anjani', 'rina@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '082255667788', 'Jl. Ahmad Yani No. 20, Medan', 'default.png', 'customer', 1, '2026-06-05 10:30:37', '2026-06-05 10:30:37'),
(6, 'zahra a a', 'zahraayu@gmail.com', '$2y$10$Rnl0sKKg/2k.s6wQvLvhReZFI0q0BiVgHasdDWupTj9FvXlmKUx5O', '08123456789', '', 'default.png', 'customer', 1, '2026-06-05 10:38:08', '2026-06-05 10:38:08');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_customer_aktif`
-- (See below for the actual view)
--
CREATE TABLE `view_customer_aktif` (
`bergabung` timestamp
,`email` varchar(150)
,`id` int unsigned
,`jumlah_review` bigint
,`jumlah_transaksi` bigint
,`name` varchar(100)
,`phone` varchar(20)
,`total_belanja` decimal(37,2)
,`transaksi_terakhir` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_laporan_penjualan`
-- (See below for the actual view)
--
CREATE TABLE `view_laporan_penjualan` (
`discount` decimal(15,2)
,`email` varchar(150)
,`grand_total` decimal(15,2)
,`id_order` int unsigned
,`jumlah_item` bigint
,`metode_pembayaran` enum('transfer','qris','cod')
,`nama_customer` varchar(100)
,`order_number` varchar(50)
,`status_pembayaran` enum('pending','verified','rejected')
,`status_pesanan` enum('pending','confirmed','processing','shipped','delivered','cancelled')
,`tanggal_pesanan` varchar(72)
,`total_amount` decimal(15,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_produk_terlaris`
-- (See below for the actual view)
--
CREATE TABLE `view_produk_terlaris` (
`id` int unsigned
,`image` varchar(255)
,`jumlah_review` bigint
,`kategori` varchar(100)
,`nama_produk` varchar(200)
,`price` decimal(15,2)
,`rata_rating` decimal(7,4)
,`stock` int
,`total_pendapatan` decimal(37,2)
,`total_terjual` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 2, 7, '2026-06-05 10:30:38'),
(2, 2, 8, '2026-06-05 10:30:38'),
(3, 2, 10, '2026-06-05 10:30:38'),
(4, 3, 4, '2026-06-05 10:30:38'),
(5, 3, 5, '2026-06-05 10:30:38'),
(6, 3, 11, '2026-06-05 10:30:38'),
(7, 4, 1, '2026-06-05 10:30:38'),
(8, 4, 8, '2026-06-05 10:30:38'),
(9, 5, 2, '2026-06-05 10:30:38'),
(10, 5, 6, '2026-06-05 10:30:38');

-- --------------------------------------------------------

--
-- Structure for view `view_customer_aktif`
--
DROP TABLE IF EXISTS `view_customer_aktif`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_customer_aktif`  AS SELECT `u`.`id` AS `id`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`phone` AS `phone`, `u`.`created_at` AS `bergabung`, count(distinct `o`.`id`) AS `jumlah_transaksi`, coalesce(sum(`o`.`grand_total`),0) AS `total_belanja`, coalesce(max(`o`.`created_at`),NULL) AS `transaksi_terakhir`, count(distinct `r`.`id`) AS `jumlah_review` FROM ((`users` `u` left join `orders` `o` on(((`u`.`id` = `o`.`user_id`) and (`o`.`status` <> 'cancelled')))) left join `reviews` `r` on((`u`.`id` = `r`.`user_id`))) WHERE ((`u`.`role` = 'customer') AND (`u`.`is_active` = 1)) GROUP BY `u`.`id`, `u`.`name`, `u`.`email`, `u`.`phone`, `u`.`created_at` ORDER BY `total_belanja` AS `DESCdesc` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `view_laporan_penjualan`
--
DROP TABLE IF EXISTS `view_laporan_penjualan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_laporan_penjualan`  AS SELECT `o`.`id` AS `id_order`, `o`.`order_number` AS `order_number`, `u`.`name` AS `nama_customer`, `u`.`email` AS `email`, date_format(`o`.`created_at`,'%d %M %Y') AS `tanggal_pesanan`, `o`.`total_amount` AS `total_amount`, `o`.`discount` AS `discount`, `o`.`grand_total` AS `grand_total`, `o`.`status` AS `status_pesanan`, `p`.`status` AS `status_pembayaran`, `p`.`method` AS `metode_pembayaran`, count(`od`.`id`) AS `jumlah_item` FROM (((`orders` `o` join `users` `u` on((`o`.`user_id` = `u`.`id`))) join `order_details` `od` on((`o`.`id` = `od`.`order_id`))) left join `payments` `p` on((`o`.`id` = `p`.`order_id`))) GROUP BY `o`.`id`, `o`.`order_number`, `u`.`name`, `u`.`email`, `o`.`created_at`, `o`.`total_amount`, `o`.`discount`, `o`.`grand_total`, `o`.`status`, `p`.`status`, `p`.`method``method`  ;

-- --------------------------------------------------------

--
-- Structure for view `view_produk_terlaris`
--
DROP TABLE IF EXISTS `view_produk_terlaris`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_produk_terlaris`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `nama_produk`, `p`.`image` AS `image`, `c`.`name` AS `kategori`, `p`.`price` AS `price`, `p`.`stock` AS `stock`, sum(`od`.`quantity`) AS `total_terjual`, sum(`od`.`subtotal`) AS `total_pendapatan`, coalesce(avg(`r`.`rating`),0) AS `rata_rating`, count(distinct `r`.`id`) AS `jumlah_review` FROM ((((`products` `p` join `categories` `c` on((`p`.`category_id` = `c`.`id`))) left join `order_details` `od` on((`p`.`id` = `od`.`product_id`))) left join `orders` `o` on(((`od`.`order_id` = `o`.`id`) and (`o`.`status` <> 'cancelled')))) left join `reviews` `r` on(((`p`.`id` = `r`.`product_id`) and (`r`.`is_approved` = 1)))) WHERE (`p`.`is_active` = 1) GROUP BY `p`.`id`, `p`.`name`, `p`.`image`, `c`.`name`, `p`.`price`, `p`.`stock` ORDER BY `total_terjual` AS `DESCdesc` ASC  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_user_log` (`id_user`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_cart` (`user_id`);

--
-- Indexes for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cart_product` (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_order_number` (`order_number`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `verified_by` (`verified_by`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_product_order` (`user_id`,`product_id`,`order_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id_log` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD CONSTRAINT `cart_details_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
