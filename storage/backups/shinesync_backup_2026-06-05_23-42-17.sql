-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: shinesync_db
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id_log` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned NOT NULL,
  `aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_user_log` (`id_user`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (3,11,'Customer memberikan review untuk produk ID 9 pada order ID 3','2026-06-05 13:45:30');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_details`
--

DROP TABLE IF EXISTS `cart_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_details` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cart_product` (`cart_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_details_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_details`
--

LOCK TABLES `cart_details` WRITE;
/*!40000 ALTER TABLE `cart_details` DISABLE KEYS */;
INSERT INTO `cart_details` VALUES (7,5,9,1,25000000.00,'2026-06-05 13:48:37');
/*!40000 ALTER TABLE `cart_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_cart` (`user_id`),
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (3,11,'2026-06-05 13:35:12','2026-06-05 13:35:12'),(5,13,'2026-06-05 13:48:37','2026-06-05 13:48:37');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Cincin','cincin','Koleksi cincin mewah dari emas, perak, dan platinum',NULL,1,'2026-06-05 10:50:34'),(3,'Kalung','kalung','Kalung elegan untuk berbagai kesempatan',NULL,1,'2026-06-05 10:50:34'),(5,'Gelang','gelang','Gelang cantik untuk melengkapi penampilan',NULL,1,'2026-06-05 10:50:34'),(7,'Anting','anting','Anting eksklusif dengan desain modern',NULL,1,'2026-06-05 10:50:34'),(9,'Aksesoris','aksesoris','Aksesoris perhiasan lainnya',NULL,1,'2026-06-05 10:50:34');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `product_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (3,3,9,'Kalung Super',1,25000000.00,25000000.00),(5,5,9,'Kalung Super',1,25000000.00,25000000.00);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_kurangi_stok` AFTER INSERT ON `order_details` FOR EACH ROW BEGIN
    UPDATE products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_update_total_order` AFTER INSERT ON `order_details` FOR EACH ROW BEGIN
    UPDATE orders
    SET total_amount = (
        SELECT COALESCE(SUM(subtotal), 0)
        FROM order_details
        WHERE order_id = NEW.order_id
    )
    WHERE id = NEW.order_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_order_number` (`order_number`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (3,11,'SS-2026-8387-536',25000000.00,2500000.00,22500000.00,2250,'Achira Desya Lucy','0812345678978','mz xmZ xm zm zm zm znknnk','zzzzzzzzzzzzzzzzzzzzzzzzzz','delivered','2026-06-05 13:35:36','2026-06-05 13:37:32'),(5,11,'SS-2026-1411-743',25000000.00,2500000.00,22500000.00,2250,'Achira Desya Lucy','0812345678978','cfcffffffffffff','vvvvvvvvvvv','pending','2026-06-05 13:55:43','2026-06-05 13:55:43');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_frag_order_insert` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_frag_order_update` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `orders_aktif`
--

DROP TABLE IF EXISTS `orders_aktif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_aktif` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders_aktif`
--

LOCK TABLES `orders_aktif` WRITE;
/*!40000 ALTER TABLE `orders_aktif` DISABLE KEYS */;
INSERT INTO `orders_aktif` VALUES (6,3,'SS-2025-0006',350000.00,0.00,350000.00,35,'Budi Santoso','085611223344','Jl. Sudirman No. 45, Surabaya',NULL,'pending','2026-06-05 10:30:37','2026-06-05 10:30:37'),(3,4,'SS-2025-0003',6800000.00,680000.00,6120000.00,612,'Dewi Lestari','087733445566','Jl. Diponegoro No. 8, Yogyakarta',NULL,'processing','2026-06-05 10:30:37','2026-06-05 10:30:37'),(5,5,'SS-2025-0005',8500000.00,850000.00,7650000.00,765,'Rina Anjani','082255667788','Jl. Ahmad Yani No. 20, Medan',NULL,'shipped','2026-06-05 10:30:37','2026-06-05 10:30:37'),(6,3,'SS-2025-0006',350000.00,0.00,350000.00,35,'Budi Santoso','085611223344','Jl. Sudirman No. 45, Surabaya',NULL,'pending','2026-06-05 10:30:37','2026-06-05 10:30:37'),(3,4,'SS-2025-0003',6800000.00,680000.00,6120000.00,612,'Dewi Lestari','087733445566','Jl. Diponegoro No. 8, Yogyakarta',NULL,'processing','2026-06-05 10:30:37','2026-06-05 10:30:37'),(5,5,'SS-2025-0005',8500000.00,850000.00,7650000.00,765,'Rina Anjani','082255667788','Jl. Ahmad Yani No. 20, Medan',NULL,'shipped','2026-06-05 10:30:37','2026-06-05 10:30:37');
/*!40000 ALTER TABLE `orders_aktif` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_arsip`
--

DROP TABLE IF EXISTS `orders_arsip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_arsip` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders_arsip`
--

LOCK TABLES `orders_arsip` WRITE;
/*!40000 ALTER TABLE `orders_arsip` DISABLE KEYS */;
INSERT INTO `orders_arsip` VALUES (1,2,'SS-2025-0001',4500000.00,450000.00,4050000.00,405,'Siti Rahayu','081298765432','Jl. Merdeka No. 12, Bandung',NULL,'delivered','2026-06-05 10:30:37','2026-06-05 10:30:37'),(2,3,'SS-2025-0002',2800000.00,0.00,2800000.00,280,'Budi Santoso','085611223344','Jl. Sudirman No. 45, Surabaya',NULL,'delivered','2026-06-05 10:30:37','2026-06-05 10:30:37'),(4,2,'SS-2025-0004',1850000.00,0.00,1850000.00,185,'Siti Rahayu','081298765432','Jl. Merdeka No. 12, Bandung',NULL,'delivered','2026-06-05 10:30:37','2026-06-05 10:30:37'),(1,2,'SS-2025-0001',4500000.00,450000.00,4050000.00,405,'Siti Rahayu','081298765432','Jl. Merdeka No. 12, Bandung',NULL,'delivered','2026-06-05 10:30:37','2026-06-05 10:30:37'),(2,3,'SS-2025-0002',2800000.00,0.00,2800000.00,280,'Budi Santoso','085611223344','Jl. Sudirman No. 45, Surabaya',NULL,'delivered','2026-06-05 10:30:37','2026-06-05 10:30:37'),(4,2,'SS-2025-0004',1850000.00,0.00,1850000.00,185,'Siti Rahayu','081298765432','Jl. Merdeka No. 12, Bandung',NULL,'delivered','2026-06-05 10:30:37','2026-06-05 10:30:37');
/*!40000 ALTER TABLE `orders_arsip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_cancelled`
--

DROP TABLE IF EXISTS `orders_cancelled`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_cancelled` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders_cancelled`
--

LOCK TABLES `orders_cancelled` WRITE;
/*!40000 ALTER TABLE `orders_cancelled` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_cancelled` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_completed`
--

DROP TABLE IF EXISTS `orders_completed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_completed` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders_completed`
--

LOCK TABLES `orders_completed` WRITE;
/*!40000 ALTER TABLE `orders_completed` DISABLE KEYS */;
INSERT INTO `orders_completed` VALUES (3,11,'SS-2026-8387-536',25000000.00,2500000.00,22500000.00,2250,'Achira Desya Lucy','0812345678978','mz xmZ xm zm zm zm znknnk','zzzzzzzzzzzzzzzzzzzzzzzzzz','delivered','2026-06-05 13:35:36','2026-06-05 13:37:32');
/*!40000 ALTER TABLE `orders_completed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_pending`
--

DROP TABLE IF EXISTS `orders_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_pending` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders_pending`
--

LOCK TABLES `orders_pending` WRITE;
/*!40000 ALTER TABLE `orders_pending` DISABLE KEYS */;
INSERT INTO `orders_pending` VALUES (5,11,'SS-2026-1411-743',25000000.00,2500000.00,22500000.00,2250,'Achira Desya Lucy','0812345678978','cfcffffffffffff','vvvvvvvvvvv','pending','2026-06-05 13:55:43','2026-06-05 13:55:43'),(3,11,'SS-2026-8387-536',25000000.00,2500000.00,22500000.00,2250,'Achira Desya Lucy','0812345678978','mz xmZ xm zm zm zm znknnk','zzzzzzzzzzzzzzzzzzzzzzzzzz','pending','2026-06-05 13:35:36','2026-06-05 13:35:36');
/*!40000 ALTER TABLE `orders_pending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders_processing`
--

DROP TABLE IF EXISTS `orders_processing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_processing` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders_processing`
--

LOCK TABLES `orders_processing` WRITE;
/*!40000 ALTER TABLE `orders_processing` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders_processing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` enum('transfer','qris','cod') COLLATE utf8mb4_unicode_ci DEFAULT 'transfer',
  `bank_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` int unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `verified_by` (`verified_by`),
  KEY `idx_order` (`order_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (3,3,22500000.00,'cod','','','pay_3_1780666586.jpeg','verified','2026-06-05 13:37:11',1,NULL,'2026-06-05 13:35:36'),(5,5,22500000.00,'transfer','Bank Mandiri','',NULL,'pending',NULL,NULL,NULL,'2026-06-05 13:55:43');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_auto_order_status` AFTER UPDATE ON `payments` FOR EACH ROW BEGIN
    IF NEW.status = 'verified' AND OLD.status <> 'verified' THEN
        UPDATE orders
        SET status = 'processing',
            updated_at = CURRENT_TIMESTAMP
        WHERE id = NEW.order_id
          AND status NOT IN ('delivered', 'cancelled');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_category` (`category_id`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_active` (`is_active`),
  KEY `idx_slug` (`slug`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (9,9,'Kalung Super','kalung-super','jsandxhkabsdhchahndxuwnquewh',25000000.00,48,50.00,'Emas 15k','6a22ae893e673.jpg',0,1,'2026-06-05 11:07:54','2026-06-05 13:55:43'),(11,9,'Cincin Diamond','cincin-diamond','dcdfdddddd',500000.00,10,12.00,'Emas 15k','6a22dc382744e.jpg',0,1,'2026-06-05 14:24:56','2026-06-05 14:24:56'),(13,7,'Anting Jumbo','anting-jumbo','nnnnxnxnsjhswhhehd',750000.00,5,25.00,'Anting 20k','6a22f02a1b5c8.jpeg',0,1,'2026-06-05 15:49:34','2026-06-05 15:50:02');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_detail`
--

DROP TABLE IF EXISTS `products_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products_detail` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_detail`
--

LOCK TABLES `products_detail` WRITE;
/*!40000 ALTER TABLE `products_detail` DISABLE KEYS */;
INSERT INTO `products_detail` VALUES (9,'jsandxhkabsdhchahndxuwnquewh','2026-06-05 11:10:01'),(9,'jsandxhkabsdhchahndxuwnquewh','2026-06-05 11:10:01');
/*!40000 ALTER TABLE `products_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_info`
--

DROP TABLE IF EXISTS `products_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products_info` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int unsigned NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_info`
--

LOCK TABLES `products_info` WRITE;
/*!40000 ALTER TABLE `products_info` DISABLE KEYS */;
INSERT INTO `products_info` VALUES (9,'Cincin Diamond','cincin-diamond',9,1,'2026-06-05 11:07:54'),(9,'Cincin Diamond','cincin-diamond',9,1,'2026-06-05 11:07:54');
/*!40000 ALTER TABLE `products_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_pricing`
--

DROP TABLE IF EXISTS `products_pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products_pricing` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `weight` decimal(8,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_pricing`
--

LOCK TABLES `products_pricing` WRITE;
/*!40000 ALTER TABLE `products_pricing` DISABLE KEYS */;
INSERT INTO `products_pricing` VALUES (9,25000000.00,50,50.00),(9,25000000.00,50,50.00);
/*!40000 ALTER TABLE `products_pricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `order_id` int unsigned NOT NULL,
  `rating` tinyint NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_product_order` (`user_id`,`product_id`,`order_id`),
  KEY `order_id` (`order_id`),
  KEY `idx_product` (`product_id`),
  KEY `idx_rating` (`rating`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (3,11,9,3,5,'',0,'2026-06-05 13:45:30');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_log_review` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN
    INSERT INTO activity_logs (id_user, aktivitas)
    VALUES (
        NEW.user_id,
        CONCAT('Customer memberikan review untuk produk ID ', NEW.product_id, ' pada order ID ', NEW.order_id)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@shinesync.com','$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu','081234567890','Jakarta Pusat','default.png','admin',1,'2026-06-05 10:50:34','2026-06-05 10:50:34'),(3,'Siti Rahayu','siti@example.com','$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu','081298765432','Jl. Merdeka No. 12, Bandung','default.png','customer',1,'2026-06-05 10:50:34','2026-06-05 10:50:34'),(5,'Budi Santoso','budi@example.com','$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu','085611223344','Jl. Sudirman No. 45, Surabaya','default.png','customer',1,'2026-06-05 10:50:34','2026-06-05 10:50:34'),(7,'Dewi Lestari','dewi@example.com','$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu','087733445566','Jl. Diponegoro No. 8, Yogyakarta','default.png','customer',1,'2026-06-05 10:50:34','2026-06-05 10:50:34'),(9,'Rina Anjani','rina@example.com','$2y$10$onlJ0nnbND1b/xNoiIAAI.JDhPkJmaP.lzMf8TSpML/V/ne744Wcu','082255667788','Jl. Ahmad Yani No. 20, Medan','default.png','customer',1,'2026-06-05 10:50:34','2026-06-05 10:50:34'),(11,'Achira Desya Lucy','achiralucy@gmail.com','$2y$10$WTirf5d041DfhHth5yzaGuh6K7a2Jh0o/3BvyaqlN6xOxQtY3pXBW','0812345678978','','default.png','customer',1,'2026-06-05 11:01:11','2026-06-05 14:21:56'),(13,'lucy','lucy@gmail.com','$2y$10$e6gFJvfZ1XqZ/GiLgaTofeQOY0arI.7s6boZ3noAxuK4vXXHHjqUG','0812345678978','','default.png','customer',1,'2026-06-05 13:46:33','2026-06-05 13:46:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `view_customer_aktif`
--

DROP TABLE IF EXISTS `view_customer_aktif`;
/*!50001 DROP VIEW IF EXISTS `view_customer_aktif`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_customer_aktif` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `email`,
 1 AS `phone`,
 1 AS `bergabung`,
 1 AS `jumlah_transaksi`,
 1 AS `total_belanja`,
 1 AS `transaksi_terakhir`,
 1 AS `jumlah_review`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_laporan_penjualan`
--

DROP TABLE IF EXISTS `view_laporan_penjualan`;
/*!50001 DROP VIEW IF EXISTS `view_laporan_penjualan`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_laporan_penjualan` AS SELECT 
 1 AS `id_order`,
 1 AS `order_number`,
 1 AS `nama_customer`,
 1 AS `email`,
 1 AS `tanggal_pesanan`,
 1 AS `total_amount`,
 1 AS `discount`,
 1 AS `grand_total`,
 1 AS `status_pesanan`,
 1 AS `status_pembayaran`,
 1 AS `metode_pembayaran`,
 1 AS `jumlah_item`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_produk_terlaris`
--

DROP TABLE IF EXISTS `view_produk_terlaris`;
/*!50001 DROP VIEW IF EXISTS `view_produk_terlaris`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_produk_terlaris` AS SELECT 
 1 AS `id`,
 1 AS `nama_produk`,
 1 AS `image`,
 1 AS `kategori`,
 1 AS `price`,
 1 AS `stock`,
 1 AS `total_terjual`,
 1 AS `total_pendapatan`,
 1 AS `rata_rating`,
 1 AS `jumlah_review`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_wishlist` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` VALUES (3,11,9,'2026-06-05 14:20:57');
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `view_customer_aktif`
--

/*!50001 DROP VIEW IF EXISTS `view_customer_aktif`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_customer_aktif` AS select `u`.`id` AS `id`,`u`.`name` AS `name`,`u`.`email` AS `email`,`u`.`phone` AS `phone`,`u`.`created_at` AS `bergabung`,count(distinct `o`.`id`) AS `jumlah_transaksi`,coalesce(sum(`o`.`grand_total`),0) AS `total_belanja`,coalesce(max(`o`.`created_at`),NULL) AS `transaksi_terakhir`,count(distinct `r`.`id`) AS `jumlah_review` from ((`users` `u` left join `orders` `o` on(((`u`.`id` = `o`.`user_id`) and (`o`.`status` <> 'cancelled')))) left join `reviews` `r` on((`u`.`id` = `r`.`user_id`))) where ((`u`.`role` = 'customer') and (`u`.`is_active` = 1)) group by `u`.`id`,`u`.`name`,`u`.`email`,`u`.`phone`,`u`.`created_at` order by `total_belanja` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_laporan_penjualan`
--

/*!50001 DROP VIEW IF EXISTS `view_laporan_penjualan`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_laporan_penjualan` AS select `o`.`id` AS `id_order`,`o`.`order_number` AS `order_number`,`u`.`name` AS `nama_customer`,`u`.`email` AS `email`,date_format(`o`.`created_at`,'%d %M %Y') AS `tanggal_pesanan`,`o`.`total_amount` AS `total_amount`,`o`.`discount` AS `discount`,`o`.`grand_total` AS `grand_total`,`o`.`status` AS `status_pesanan`,`p`.`status` AS `status_pembayaran`,`p`.`method` AS `metode_pembayaran`,count(`od`.`id`) AS `jumlah_item` from (((`orders` `o` join `users` `u` on((`o`.`user_id` = `u`.`id`))) join `order_details` `od` on((`o`.`id` = `od`.`order_id`))) left join `payments` `p` on((`o`.`id` = `p`.`order_id`))) group by `o`.`id`,`o`.`order_number`,`u`.`name`,`u`.`email`,`o`.`created_at`,`o`.`total_amount`,`o`.`discount`,`o`.`grand_total`,`o`.`status`,`p`.`status`,`p`.`method` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_produk_terlaris`
--

/*!50001 DROP VIEW IF EXISTS `view_produk_terlaris`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_produk_terlaris` AS select `p`.`id` AS `id`,`p`.`name` AS `nama_produk`,`p`.`image` AS `image`,`c`.`name` AS `kategori`,`p`.`price` AS `price`,`p`.`stock` AS `stock`,sum(`od`.`quantity`) AS `total_terjual`,sum(`od`.`subtotal`) AS `total_pendapatan`,coalesce(avg(`r`.`rating`),0) AS `rata_rating`,count(distinct `r`.`id`) AS `jumlah_review` from ((((`products` `p` join `categories` `c` on((`p`.`category_id` = `c`.`id`))) left join `order_details` `od` on((`p`.`id` = `od`.`product_id`))) left join `orders` `o` on(((`od`.`order_id` = `o`.`id`) and (`o`.`status` <> 'cancelled')))) left join `reviews` `r` on(((`p`.`id` = `r`.`product_id`) and (`r`.`is_approved` = 1)))) where (`p`.`is_active` = 1) group by `p`.`id`,`p`.`name`,`p`.`image`,`c`.`name`,`p`.`price`,`p`.`stock` order by `total_terjual` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-05 23:42:18
