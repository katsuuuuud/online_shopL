-- MySQL dump 10.13  Distrib 9.6.0, for Linux (aarch64)
--
-- Host: localhost    Database: online_shop
-- ------------------------------------------------------
-- Server version	9.6.0

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '69b9dd39-5e79-11f1-af61-ca33169e42aa:1-172,
efe6ab57-70ce-11f1-ab3f-4e032be9841b:1-58';

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  PRIMARY KEY (`cart_item_id`),
  UNIQUE KEY `uniq_cart_product` (`cart_id`,`product_id`),
  KEY `fk_cart_items_product` (`product_id`),
  CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cartId`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`productId`) ON DELETE CASCADE,
  CONSTRAINT `CHK_cart_item_quantity` CHECK ((`quantity` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `cartId` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cartId`),
  UNIQUE KEY `uniq_user_id` (`user_id`),
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,30,'2026-06-21 18:10:35','2026-06-21 18:10:36'),(2,24,'2026-06-22 08:41:39','2026-06-22 08:41:39');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `categoryId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Accessories','fashion accessories'),(2,'Electronics','electronic devices'),(3,'Sports','sports equipment'),(4,'Books','printed books'),(5,'Bags','bags and backpacks'),(6,'Cosmetics','beauty products');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discounts` (
  `discountId` int NOT NULL AUTO_INCREMENT,
  `description` varchar(60) DEFAULT NULL,
  `discount_value` int NOT NULL,
  `type` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`discountId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES (1,'Winter sale',15,'percentage','2026-01-01','2026-01-31',0),(2,'Spring sale',20,'percentage','2026-04-01','2026-04-30',0),(3,'Summer sale',25,'percentage','2026-06-01','2026-06-30',1),(4,'Back to school',10,'percentage','2026-08-15','2026-09-15',1),(5,'Black Friday',50,'percentage','2026-11-25','2026-11-30',0),(6,'New Year bonus',30,'fixed_amount','2026-12-20','2027-01-05',0);
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_07_03_132742_create_cart_items_table',0),(2,'2026_07_03_132742_create_carts_table',0),(3,'2026_07_03_132742_create_categories_table',0),(4,'2026_07_03_132742_create_discounts_table',0),(5,'2026_07_03_132742_create_notifications_table',0),(6,'2026_07_03_132742_create_order_items_table',0),(7,'2026_07_03_132742_create_orders_table',0),(8,'2026_07_03_132742_create_prices_table',0),(9,'2026_07_03_132742_create_product_audit_table',0),(10,'2026_07_03_132742_create_products_table',0),(11,'2026_07_03_132742_create_users_table',0),(12,'2026_07_03_132745_add_foreign_keys_to_cart_items_table',0),(13,'2026_07_03_132745_add_foreign_keys_to_carts_table',0),(14,'2026_07_03_132745_add_foreign_keys_to_notifications_table',0),(15,'2026_07_03_132745_add_foreign_keys_to_orders_table',0),(16,'2026_07_03_132745_add_foreign_keys_to_prices_table',0),(17,'2026_07_03_132745_add_foreign_keys_to_product_audit_table',0),(18,'2026_07_03_132745_add_foreign_keys_to_products_table',0),(19,'2019_12_14_000001_create_personal_access_tokens_table',1),(20,'2026_07_05_113142_add_foreign_keys_to_order_items_table',2),(21,'2026_07_05_125750_add_unique_index_to_users_email',3),(22,'2026_07_05_133725_change_amount_type_in_orders_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `notificationId` int NOT NULL AUTO_INCREMENT,
  `created_at` date NOT NULL,
  `customer_id` int NOT NULL,
  `order_id` int NOT NULL,
  `message` varchar(70) NOT NULL,
  PRIMARY KEY (`notificationId`),
  KEY `fk_notifications_customer` (`customer_id`),
  KEY `fk_notifications_order` (`order_id`),
  CONSTRAINT `fk_notifications_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  CONSTRAINT `fk_notifications_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,'2026-05-20',1,1,'Your order has been delivered'),(2,'2026-05-21',2,2,'Order confirmed'),(3,'2026-05-22',3,3,'Order shipped'),(4,'2026-05-23',4,4,'Order delivered'),(5,'2026-05-24',5,5,'Payment received'),(6,'2026-05-25',6,6,'Thank you for your purchase');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'RUB',
  PRIMARY KEY (`order_item_id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`productId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (6,12,15,11,1,35.00,'USD'),(7,12,18,11,2,14.99,'USD'),(8,13,14,12,3,899.99,'USD'),(9,14,16,13,1,19.99,'USD'),(10,15,15,14,1,35.00,'USD'),(11,16,14,15,1,899.99,'USD'),(12,17,18,16,1,14.99,'USD'),(13,18,18,17,1,14.99,'USD'),(14,19,13,18,1,0.00,'RUB'),(15,20,17,19,1,60.99,'USD'),(16,21,14,20,1,899.99,'USD'),(17,22,14,21,2,899.99,'USD'),(18,23,18,22,1,14.99,'USD'),(19,24,18,23,28,14.99,'USD'),(20,25,18,24,1,14.99,'USD'),(21,25,14,24,1,899.99,'USD'),(22,26,14,24,1,899.99,'USD'),(23,27,14,24,1,899.99,'USD'),(24,28,16,24,1,19.99,'USD'),(25,29,18,24,1,14.99,'USD'),(26,30,15,24,1,35.00,'USD'),(27,30,16,24,1,19.99,'USD'),(28,31,14,24,1,899.99,'USD'),(29,31,17,24,1,60.99,'USD'),(30,32,14,24,2,899.99,'USD'),(31,32,15,24,2,35.00,'USD'),(32,33,14,24,4,899.99,'USD'),(33,34,15,24,1,35.00,'USD'),(34,35,14,24,2,899.99,'USD'),(35,35,15,24,1,35.00,'USD'),(36,35,16,24,1,19.99,'USD');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `orderId` int NOT NULL AUTO_INCREMENT,
  `created_at` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `customer_id` int NOT NULL,
  `status` varchar(10) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`orderId`),
  KEY `fk_orders_customer` (`customer_id`),
  CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'2026-05-11',1.00,1,'finished','Address 3'),(2,'2026-05-12',2.00,2,'pending','Address 4'),(3,'2026-05-13',1.00,3,'shipped','Address 5'),(4,'2026-05-14',1.00,4,'finished','Address 6'),(5,'2026-05-15',3.00,5,'pending','Address 7'),(6,'2026-05-16',1.00,6,'shipped','Address 8'),(7,'2026-06-12',900.00,2,'new','vdv 3'),(8,'2026-06-12',900.00,3,'new','vdv 3'),(10,'2026-06-12',900.00,9,'new','fnnnf'),(11,'2026-06-12',55.00,10,'new','denj 3'),(12,'2026-06-12',65.00,11,'new','fjfji'),(13,'2026-06-12',2700.00,12,'new','lalalla'),(14,'2026-06-12',20.00,13,'new','rhrhfhf'),(15,'2026-06-12',35.00,14,'new','jjfjfjf'),(16,'2026-06-12',900.00,15,'new','gfehwfgb'),(17,'2026-06-12',15.00,16,'new','eeeeeeee'),(18,'2026-06-12',15.00,17,'new','didididi'),(19,'2026-06-12',0.00,18,'new','ff'),(20,'2026-06-12',61.00,19,'new','f4 4'),(21,'2026-06-12',900.00,20,'new','jcdsivjc'),(22,'2026-06-12',1800.00,21,'new','hcudhf1'),(23,'2026-06-15',15.00,22,'new','jkdfc'),(24,'2026-06-15',420.00,23,'new','cnkc'),(25,'2026-06-17',915.00,24,'new','Home'),(26,'2026-06-18',900.00,24,'new','Home'),(27,'2026-06-18',900.00,24,'new','Home'),(28,'2026-06-19',20.00,24,'new','Homie'),(29,'2026-06-19',15.00,24,'new','Homie'),(30,'2026-06-22',55.00,24,'new','Homie'),(31,'2026-06-22',961.00,24,'new','Homie'),(32,'2026-06-22',1870.00,24,'new','Homie'),(33,'2026-06-25',3600.00,24,'new','Homi'),(34,'2026-06-29',35.00,24,'new','Homi'),(35,'2026-07-05',1854.97,24,'new','Homi');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prices` (
  `priceauditId` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `date` date DEFAULT '2025-09-11',
  PRIMARY KEY (`priceauditId`),
  KEY `fk_prices_product` (`product_id`),
  CONSTRAINT `fk_prices_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`productId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prices`
--

LOCK TABLES `prices` WRITE;
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
INSERT INTO `prices` VALUES (1,13,79.99,'USD',1,'2025-09-11'),(2,14,899.99,'USD',1,'2025-09-11'),(3,15,35.00,'USD',1,'2025-09-11'),(4,16,19.99,'USD',1,'2025-09-11'),(6,18,14.99,'USD',1,'2025-09-11'),(10,17,60.99,'USD',1,'2026-02-10'),(11,19,499.99,'USD',1,'2025-09-11');
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_audit`
--

DROP TABLE IF EXISTS `product_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_audit` (
  `auditId` int NOT NULL AUTO_INCREMENT,
  `quantity` int DEFAULT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`auditId`),
  KEY `fk_product_audit_product` (`product_id`),
  CONSTRAINT `fk_product_audit_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`productId`) ON DELETE CASCADE,
  CONSTRAINT `CHK_quantity` CHECK ((`quantity` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_audit`
--

LOCK TABLES `product_audit` WRITE;
/*!40000 ALTER TABLE `product_audit` DISABLE KEYS */;
INSERT INTO `product_audit` VALUES (1,1000,13),(2,991,14),(3,995,15),(4,997,16),(5,999,17),(6,0,18);
/*!40000 ALTER TABLE `product_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `productId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `category_id` int NOT NULL,
  `discount_id` int DEFAULT NULL,
  `has_discount` tinyint(1) NOT NULL,
  PRIMARY KEY (`productId`),
  KEY `fk_products_category` (`category_id`),
  KEY `fk_products_discount` (`discount_id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`categoryId`) ON DELETE CASCADE,
  CONSTRAINT `fk_products_discount` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`discountId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (13,'Watch','classic wrist watch',1,1,1),(14,'Laptop','lightweight office laptop',2,5,1),(15,'Football','professional football',3,NULL,0),(16,'Novel','fiction book',4,6,1),(17,'Backpack','travel backpack',5,4,1),(18,'Lipstick','beauty cosmetic product',6,NULL,0),(19,'Phone','xhwidbciuwc',2,NULL,0);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `userId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Alice','alice@mail.com','+70000000001','Address 3',''),(2,'David','david@mail.com','+70000000002','Address 4',''),(3,'Emma','emma@mail.com','+70000000003','Address 5',''),(4,'Frank','frank@mail.com','+70000000004','Address 6',''),(5,'Grace','grace@mail.com','+70000000005','Address 7',''),(6,'Helen','helen@mail.com','+70000000006','Address 8',''),(9,'di','htirh@hffb.vvkfd','36473875','fnnnf',''),(10,'borat','vasya@co.com','4934','denj 3',''),(11,'jdvne','njef@jdfdf.fo','+34834','fjfji',''),(12,'la','lala@fk','432432','lalalla',''),(13,'rrrrr','r5@fgfg','4874','rhrhfhf',''),(14,'ddddd','dddd@jjfjf','34394893248','jjfjfjf',''),(15,'ndfjsnjds','nfdbjfh@dfsgf','38923','gfehwfgb',''),(16,'dddddddd','d@eeeee','44444444','eeeeeeee',''),(17,'╨░╨╗╨▓╨╝╨╝╨░╨╝╨║╨╝','hjsdhvuh@hhh','3446677','didididi',''),(18,'Ehhh','oooo@r','34456','ff',''),(19,'╤Л╤Л','a@f','345123','f4 4',''),(20,'╨▓╨┐╨░╤А╨╛','dkscns@hd','83492384','jcdsivjc',''),(21,'╨▓╨▓╨▓╨▓','hcdsfw@hdcb','384444','hcudhf1',''),(22,'dkokj','fjifj2kv@dm','32432','jkdfc',''),(23,'bhbj','njnxas@kds','u324293','cnkc',''),(24,'User','user1@gmail.com','1234567','Homi','$2y$10$RLTqu22GRISrpoPWuaezYeWKa4TZvb3Vk5UWYqgst.2vH2cF9x1nK'),(25,'us2','us2@gmail.com','123456','adress','$2y$10$KHCs9oo.fK49zfsAi1lBPOgP/qO9aj3y.k33o2xufUjBrPhfASk8u'),(26,'╤В╨▓╤Л╨╝╤В╤Л╤И╨▓','hfnudv@cdivcni','4398584q309','hrbvhf','$2y$10$CMduAR.mkyFSjYPiGJmi.esUeTWjSVVJwXawPP9qlVHET0LSvp01W'),(27,'ksdcnc','vbj@bd','bf39482','hfedn','$2y$10$QyDXYj21UMA/opeBIoLGAewI66JIRIoujyJz/OVDgIFhipIRqmDQK'),(28,'user3','user3@gmail.com','+77777777777','dom','$2y$10$UV6mCBNXv.CDjutXjDVFMuMD3O.VPavtlRY8TRNJIE/Ofs1uoCHZC'),(29,'dididi','didi@di','123456','asdfg','$2y$10$GaKWrj5b.f1OfJzCYEEk6e0v.bWty47O3CQgSWyBDvBLZrIYuXriC'),(30,'User2','user2@gm','123456','home','$2y$12$KbdfScs.qIeDyLnxuzrEE.Y7Wu.D6UwDCdBSUNj0p3RK2GPc/Z88m');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-05 14:18:51
