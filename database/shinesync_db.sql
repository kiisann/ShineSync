-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2026 at 04:37 PM
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
  `aktivitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(3, 11, '2026-06-05 13:35:12', '2026-06-05 13:35:12');

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

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`) VALUES
(1, 'Cincin', 'cincin', 'Koleksi cincin mewah dari emas, perak, dan platinum', NULL, 1, '2026-06-05 10:50:34'),
(3, 'Kalung', 'kalung', 'Kalung elegan untuk berbagai kesempatan', NULL, 1, '2026-06-05 10:50:34'),
(5, 'Gelang', 'gelang', 'Gelang cantik untuk melengkapi penampilan', NULL, 1, '2026-06-05 10:50:34'),
(7, 'Anting', 'anting', 'Anting eksklusif dengan desain modern', NULL, 1, '2026-06-05 10:50:34'),
(9, 'Aksesoris', 'aksesoris', 'Aksesoris perhiasan lainnya', NULL, 1, '2026-06-05 10:50:34');

-- --------------------------------------------------------

--
-- Table structure for table `low_stock_alerts`
--

CREATE TABLE `low_stock_alerts` (
  `id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `stock_saat_ini` int NOT NULL,
  `threshold` int NOT NULL DEFAULT '5',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `discount`, `grand_total`, `loyalty_points`, `shipping_name`, `shipping_phone`, `shipping_address`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(3, 11, 'SS-2026-8387-536', '25000000.00', '2500000.00', '22500000.00', 2250, 'Achira Desya Lucy', '0812345678978', 'mz xmZ xm zm zm zm znknnk', 'zzzzzzzzzzzzzzzzzzzzzzzzzz', 'pending', '2026-06-05 13:35:36', '2026-06-05 13:35:36');

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
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
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
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
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
-- Table structure for table `orders_cancelled`
--

CREATE TABLE `orders_cancelled` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_completed`
--

CREATE TABLE `orders_completed` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_pending`
--

CREATE TABLE `orders_pending` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders_pending`
--

INSERT INTO `orders_pending` (`id`, `user_id`, `order_number`, `total_amount`, `discount`, `grand_total`, `loyalty_points`, `shipping_name`, `shipping_phone`, `shipping_address`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(3, 11, 'SS-2026-8387-536', '25000000.00', '2500000.00', '22500000.00', 2250, 'Achira Desya Lucy', '0812345678978', 'mz xmZ xm zm zm zm znknnk', 'zzzzzzzzzzzzzzzzzzzzzzzzzz', 'pending', '2026-06-05 13:35:36', '2026-06-05 13:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `orders_processing`
--

CREATE TABLE `orders_processing` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loyalty_points` int DEFAULT '0',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`) VALUES
(3, 3, 9, 'Kalung Super', 1, '25000000.00', '25000000.00');

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
  `method` enum('transfer','qris','cod') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'transfer',
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` int UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `amount`, `method`, `bank_name`, `account_number`, `proof_image`, `status`, `verified_at`, `verified_by`, `notes`, `created_at`) VALUES
(3, 3, '22500000.00', 'cod', '', '', NULL, 'pending', NULL, NULL, NULL, '2026-06-05 13:35:36');

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
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `weight` decimal(8,2) DEFAULT '0.00',
  `material` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `weight`, `material`, `image`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
(9, 9, 'Kalung Super', 'kalung-super', 'jsandxhkabsdhchahndxuwnquewh', '25000000.00', 49, '50.00', 'Emas 15k', '6a22ae893e673.jpg', 0, 1, '2026-06-05 11:07:54', '2026-06-05 13:35:36'),
(11, 9, 'coba', 'coba', '', '1000.00', 1, '0.00', 'coba', '', 0, 1, '2026-06-05 15:34:16', '2026-06-05 15:34:16'),
(13, 1, 'Cincin Berlian Aurora', 'cincin-berlian-aurora', 'Cincin berlian elegan dengan desain mewah.', '3500000.00', 25, '5.20', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(15, 1, 'Cincin Rose Gold Elegance', 'cincin-rose-gold-elegance', 'Cincin rose gold dengan desain modern.', '2750000.00', 30, '4.80', 'Rose Gold 18K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(17, 1, 'Cincin Diamond Princess', 'cincin-diamond-princess', 'Cincin premium untuk acara spesial.', '5200000.00', 15, '6.10', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(19, 1, 'Cincin Sapphire Queen', 'cincin-sapphire-queen', 'Cincin batu safir eksklusif.', '4800000.00', 20, '5.90', 'Emas 18K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(21, 1, 'Cincin Emerald Luxe', 'cincin-emerald-luxe', 'Cincin zamrud dengan sentuhan elegan.', '5600000.00', 12, '6.30', 'Emas 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(23, 1, 'Cincin Infinity Love', 'cincin-infinity-love', 'Simbol cinta abadi.', '1800000.00', 40, '4.00', 'Perak 925', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(25, 1, 'Cincin Royal Spark', 'cincin-royal-spark', 'Kilauan berlian premium.', '6500000.00', 10, '6.80', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(27, 1, 'Cincin Classic Gold', 'cincin-classic-gold', 'Desain klasik yang timeless.', '2400000.00', 35, '5.10', 'Emas 17K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(29, 3, 'Kalung Golden Heart', 'kalung-golden-heart', 'Kalung liontin hati elegan.', '2200000.00', 40, '12.50', 'Emas 17K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(31, 3, 'Kalung Pearl Luxury', 'kalung-pearl-luxury', 'Kalung mutiara premium.', '4800000.00', 18, '15.00', 'Mutiara', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(33, 3, 'Kalung Infinity Charm', 'kalung-infinity-charm', 'Desain infinity modern.', '1850000.00', 35, '10.20', 'Perak 925', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(35, 3, 'Kalung Moonlight Shine', 'kalung-moonlight-shine', 'Kalung bertema bulan.', '2100000.00', 28, '11.00', 'Emas Putih 18K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(37, 3, 'Kalung Royal Diamond', 'kalung-royal-diamond', 'Kalung berlian mewah.', '7900000.00', 10, '18.00', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(39, 3, 'Kalung Crystal Bloom', 'kalung-crystal-bloom', 'Kalung bunga kristal.', '1750000.00', 45, '9.00', 'Perak 925', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(41, 3, 'Kalung Emerald Grace', 'kalung-emerald-grace', 'Kalung zamrud eksklusif.', '6200000.00', 12, '16.00', 'Emas 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(43, 3, 'Kalung Diamond Star', 'kalung-diamond-star', 'Kalung bintang berlian.', '5500000.00', 14, '14.00', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(45, 5, 'Gelang Charm Luxury', 'gelang-charm-luxury', 'Gelang charm eksklusif.', '1900000.00', 28, '8.50', 'Perak 925', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(47, 5, 'Gelang Diamond Spark', 'gelang-diamond-spark', 'Kilau berlian memukau.', '4300000.00', 12, '9.20', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(49, 5, 'Gelang Classic Gold', 'gelang-classic-gold', 'Gelang emas klasik.', '3100000.00', 20, '11.00', 'Emas 18K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(51, 5, 'Gelang Elegant Pearl', 'gelang-elegant-pearl', 'Perpaduan mutiara dan emas.', '3600000.00', 16, '10.50', 'Mutiara', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(53, 5, 'Gelang Rose Charm', 'gelang-rose-charm', 'Desain feminin modern.', '2400000.00', 30, '8.00', 'Rose Gold 18K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(55, 5, 'Gelang Luxe Infinity', 'gelang-luxe-infinity', 'Gelang infinity premium.', '2800000.00', 22, '8.70', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(57, 5, 'Gelang Royal Crown', 'gelang-royal-crown', 'Desain mewah ala kerajaan.', '5800000.00', 10, '12.00', 'Emas 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(59, 5, 'Gelang Crystal Wave', 'gelang-crystal-wave', 'Desain ombak modern.', '2100000.00', 25, '8.30', 'Perak 925', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(61, 7, 'Anting Crystal Shine', 'anting-crystal-shine', 'Anting kristal berkilau.', '1500000.00', 45, '3.50', 'Perak 925', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(63, 7, 'Anting Diamond Drop', 'anting-diamond-drop', 'Anting berlian menjuntai.', '3900000.00', 16, '4.10', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(65, 7, 'Anting Rose Blossom', 'anting-rose-blossom', 'Anting bunga mawar.', '1750000.00', 32, '3.80', 'Rose Gold 18K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(67, 7, 'Anting Pearl Dew', 'anting-pearl-dew', 'Anting mutiara elegan.', '2200000.00', 24, '3.90', 'Mutiara', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(69, 7, 'Anting Golden Leaf', 'anting-golden-leaf', 'Motif daun emas.', '2600000.00', 20, '4.20', 'Emas 18K', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(71, 7, 'Anting Royal Sparkle', 'anting-royal-sparkle', 'Anting premium eksklusif.', '4500000.00', 14, '4.80', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(73, 7, 'Anting Diamond Flower', 'anting-diamond-flower', 'Desain bunga berlian.', '4900000.00', 12, '4.50', 'Emas Putih 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(75, 7, 'Anting Infinity Loop', 'anting-infinity-loop', 'Model minimalis modern.', '1650000.00', 38, '3.40', 'Perak 925', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(77, 9, 'Bros Mutiara Elegan', 'bros-mutiara-elegan', 'Bros mutiara untuk acara formal.', '450000.00', 35, '15.00', 'Mutiara', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(79, 9, 'Bros Crystal Flower', 'bros-crystal-flower', 'Bros bunga dengan kristal premium.', '550000.00', 25, '18.00', 'Alloy & Crystal', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(81, 9, 'Bros Golden Leaf', 'bros-golden-leaf', 'Bros motif daun berlapis emas.', '620000.00', 20, '20.00', 'Gold Plated', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(83, 9, 'Bros Kebaya Royal', 'bros-kebaya-royal', 'Bros mewah untuk kebaya dan acara resmi.', '850000.00', 15, '25.00', 'Emas 18K', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(85, 9, 'Jepit Rambut Crystal Shine', 'jepit-rambut-crystal-shine', 'Jepit rambut dengan hiasan kristal.', '275000.00', 40, '12.00', 'Crystal', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(87, 9, 'Hair Pin Pearl Luxe', 'hair-pin-pearl-luxe', 'Hair pin mutiara elegan.', '225000.00', 50, '8.00', 'Mutiara', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(89, 9, 'Tiara Princess Diamond', 'tiara-princess-diamond', 'Tiara cantik untuk pesta dan pernikahan.', '1800000.00', 10, '80.00', 'Crystal Premium', NULL, 1, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23'),
(91, 9, 'Pin Hijab Aurora', 'pin-hijab-aurora', 'Pin hijab minimalis dengan batu kristal.', '125000.00', 75, '5.00', 'Stainless Steel', NULL, 0, 1, '2026-06-05 16:36:23', '2026-06-05 16:36:23');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `tr_cek_stok_menipis` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
  IF NEW.stock <= 5 AND NEW.stock < OLD.stock THEN
    INSERT INTO low_stock_alerts
      (product_id, stock_saat_ini, threshold)
    VALUES
      (NEW.id, NEW.stock, 5);
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products_detail`
--

CREATE TABLE `products_detail` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_detail`
--

INSERT INTO `products_detail` (`id`, `description`, `updated_at`) VALUES
(9, 'jsandxhkabsdhchahndxuwnquewh', '2026-06-05 11:10:01');

-- --------------------------------------------------------

--
-- Table structure for table `products_info`
--

CREATE TABLE `products_info` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_info`
--

INSERT INTO `products_info` (`id`, `name`, `slug`, `category_id`, `is_active`, `created_at`) VALUES
(9, 'Cincin Diamond', 'cincin-diamond', 9, 1, '2026-06-05 11:07:54');

-- --------------------------------------------------------

--
-- Table structure for table `products_pricing`
--

CREATE TABLE `products_pricing` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `weight` decimal(8,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_pricing`
--

INSERT INTO `products_pricing` (`id`, `price`, `stock`, `weight`) VALUES
(9, '25000000.00', 50, '50.00');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `role` enum('admin','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `avatar`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@shinesync.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '081234567890', 'Jakarta Pusat', 'default.png', 'admin', 1, '2026-06-05 10:50:34', '2026-06-05 10:50:34'),
(3, 'Siti Rahayu', 'siti@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '081298765432', 'Jl. Merdeka No. 12, Bandung', 'default.png', 'customer', 1, '2026-06-05 10:50:34', '2026-06-05 10:50:34'),
(5, 'Budi Santoso', 'budi@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '085611223344', 'Jl. Sudirman No. 45, Surabaya', 'default.png', 'customer', 1, '2026-06-05 10:50:34', '2026-06-05 10:50:34'),
(7, 'Dewi Lestari', 'dewi@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '087733445566', 'Jl. Diponegoro No. 8, Yogyakarta', 'default.png', 'customer', 1, '2026-06-05 10:50:34', '2026-06-05 10:50:34'),
(9, 'Rina Anjani', 'rina@example.com', '$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu', '082255667788', 'Jl. Ahmad Yani No. 20, Medan', 'default.png', 'customer', 1, '2026-06-05 10:50:34', '2026-06-05 10:50:34'),
(11, 'Achira Desya Lucy', 'achiralucy@gmail.com', '$2y$10$R0B3YnIKceYH.VCTzP6/jOhqPvbDxnclDnz.6nn8xIvn6SEP3uwRS', '0812345678978', '', 'default.png', 'customer', 1, '2026-06-05 11:01:11', '2026-06-05 11:01:11'),
(13, 'Zahra Ayu Azizah', 'zahra@gmail.com', '$2y$10$3EVg.PXGT8KTB88OeDRxHuWgNDwiQXxO.iX6NOCwjCmuZVu8/jgzi', '085783312345', '', 'default.png', 'customer', 1, '2026-06-05 14:55:19', '2026-06-05 14:55:19');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_customer_aktif`
-- (See below for the actual view)
--
CREATE TABLE `view_customer_aktif` (
`id` int unsigned
,`name` varchar(100)
,`email` varchar(150)
,`phone` varchar(20)
,`bergabung` timestamp
,`jumlah_transaksi` bigint
,`total_belanja` decimal(37,2)
,`transaksi_terakhir` timestamp
,`jumlah_review` bigint
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_laporan_penjualan`
-- (See below for the actual view)
--
CREATE TABLE `view_laporan_penjualan` (
`id_order` int unsigned
,`order_number` varchar(50)
,`nama_customer` varchar(100)
,`email` varchar(150)
,`tanggal_pesanan` varchar(72)
,`total_amount` decimal(15,2)
,`discount` decimal(15,2)
,`grand_total` decimal(15,2)
,`status_pesanan` enum('pending','confirmed','processing','shipped','delivered','cancelled')
,`status_pembayaran` enum('pending','verified','rejected')
,`metode_pembayaran` enum('transfer','qris','cod')
,`jumlah_item` bigint
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_produk_terlaris`
-- (See below for the actual view)
--
CREATE TABLE `view_produk_terlaris` (
`id` int unsigned
,`nama_produk` varchar(200)
,`image` varchar(255)
,`kategori` varchar(100)
,`price` decimal(15,2)
,`stock` int
,`total_terjual` decimal(32,0)
,`total_pendapatan` decimal(37,2)
,`rata_rating` decimal(7,4)
,`jumlah_review` bigint
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
-- Indexes for table `low_stock_alerts`
--
ALTER TABLE `low_stock_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

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
  MODIFY `id_log` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `low_stock_alerts`
--
ALTER TABLE `low_stock_alerts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `low_stock_alerts`
--
ALTER TABLE `low_stock_alerts`
  ADD CONSTRAINT `low_stock_alerts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

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
