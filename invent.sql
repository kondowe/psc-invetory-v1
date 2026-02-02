-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: inventory_system
-- ------------------------------------------------------
-- Server version	9.1.0

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
  `activity_log_id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `activity_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`activity_log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-28 06:41:05'),(2,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-28 09:10:41'),(3,1,'password_reset',NULL,'Reset password for user: stores','127.0.0.1','2026-01-28 09:22:19'),(4,2,'login',NULL,'User logged in successfully','::1','2026-01-28 09:22:40'),(5,2,'grv_submit',NULL,'Submitted GRV #GRV-2026-000002 for approval','::1','2026-01-28 10:04:02'),(6,2,'logout',NULL,'User logged out','::1','2026-01-28 10:09:31'),(7,1,'user_create',NULL,'Created new user: adminm','127.0.0.1','2026-01-28 10:15:13'),(8,5,'login',NULL,'User logged in successfully','::1','2026-01-28 10:15:35'),(9,5,'logout',NULL,'User logged out','::1','2026-01-28 10:23:43'),(10,2,'login',NULL,'User logged in successfully','::1','2026-01-28 10:23:50'),(11,2,'logout',NULL,'User logged out','::1','2026-01-28 10:23:58'),(12,1,'password_reset',NULL,'Reset password for user: requester1','127.0.0.1','2026-01-28 10:24:50'),(13,4,'login',NULL,'User logged in successfully','::1','2026-01-28 10:25:00'),(14,4,'logout',NULL,'User logged out','::1','2026-01-28 11:03:09'),(15,1,'password_reset',NULL,'Reset password for user: supervisor_it','127.0.0.1','2026-01-28 11:03:35'),(16,3,'login',NULL,'User logged in successfully','::1','2026-01-28 11:03:45'),(17,3,'logout',NULL,'User logged out','::1','2026-01-28 11:17:32'),(18,5,'login',NULL,'User logged in successfully','::1','2026-01-28 11:17:38'),(19,5,'logout',NULL,'User logged out','::1','2026-01-28 11:43:35'),(20,4,'login',NULL,'User logged in successfully','::1','2026-01-28 11:43:46'),(21,4,'logout',NULL,'User logged out','::1','2026-01-28 11:56:05'),(22,3,'login',NULL,'User logged in successfully','::1','2026-01-28 11:56:11'),(23,3,'logout',NULL,'User logged out','::1','2026-01-28 12:00:58'),(24,5,'login',NULL,'User logged in successfully','::1','2026-01-28 12:01:04'),(25,5,'logout',NULL,'User logged out','::1','2026-01-28 12:03:08'),(26,4,'login',NULL,'User logged in successfully','::1','2026-01-28 12:03:21'),(27,4,'logout',NULL,'User logged out','::1','2026-01-28 12:06:18'),(28,3,'login',NULL,'User logged in successfully','::1','2026-01-28 12:06:24'),(29,3,'login',NULL,'User logged in successfully','::1','2026-01-28 13:50:22'),(30,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-28 13:52:54'),(31,3,'logout',NULL,'User logged out','::1','2026-01-28 14:00:28'),(32,4,'login',NULL,'User logged in successfully','::1','2026-01-28 14:00:37'),(33,4,'logout',NULL,'User logged out','::1','2026-01-28 15:05:45'),(34,5,'login',NULL,'User logged in successfully','::1','2026-01-28 15:05:50'),(35,5,'logout',NULL,'User logged out','::1','2026-01-28 15:07:10'),(36,3,'login',NULL,'User logged in successfully','::1','2026-01-28 15:07:15'),(37,3,'logout',NULL,'User logged out','::1','2026-01-28 15:12:08'),(38,5,'login',NULL,'User logged in successfully','::1','2026-01-28 15:12:13'),(39,5,'logout',NULL,'User logged out','::1','2026-01-28 15:12:24'),(40,3,'login',NULL,'User logged in successfully','::1','2026-01-28 15:12:29'),(41,3,'logout',NULL,'User logged out','::1','2026-01-28 15:12:56'),(42,5,'login',NULL,'User logged in successfully','::1','2026-01-28 15:13:02'),(43,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-28 15:14:03'),(44,5,'logout',NULL,'User logged out','::1','2026-01-28 15:30:08'),(45,4,'login',NULL,'User logged in successfully','::1','2026-01-28 15:30:13'),(46,4,'logout',NULL,'User logged out','::1','2026-01-28 15:32:10'),(47,3,'login',NULL,'User logged in successfully','::1','2026-01-28 15:32:15'),(48,3,'logout',NULL,'User logged out','::1','2026-01-28 15:32:27'),(49,5,'login',NULL,'User logged in successfully','::1','2026-01-28 15:32:31'),(50,5,'logout',NULL,'User logged out','::1','2026-01-28 15:32:45'),(51,4,'login',NULL,'User logged in successfully','::1','2026-01-28 15:32:51'),(52,4,'logout',NULL,'User logged out','::1','2026-01-28 15:34:23'),(53,2,'login',NULL,'User logged in successfully','::1','2026-01-28 15:34:28'),(54,2,'logout',NULL,'User logged out','::1','2026-01-28 15:47:24'),(55,2,'login',NULL,'User logged in successfully','::1','2026-01-28 15:47:35'),(56,2,'grv_submit',NULL,'Submitted GRV #GRV-2026-000003 for approval','::1','2026-01-28 15:52:27'),(57,2,'logout',NULL,'User logged out','::1','2026-01-28 15:52:36'),(58,5,'login',NULL,'User logged in successfully','::1','2026-01-28 15:52:43'),(59,5,'logout',NULL,'User logged out','::1','2026-01-28 15:56:59'),(60,2,'login',NULL,'User logged in successfully','::1','2026-01-28 15:57:09'),(61,2,'item_update',NULL,'Updated item: Petrol Trade Diesel 20 litres (ID: 4)','::1','2026-01-28 16:04:07'),(62,2,'grv_submit',NULL,'Submitted GRV #GRV-2026-000004 for approval','::1','2026-01-28 16:35:16'),(63,2,'logout',NULL,'User logged out','::1','2026-01-28 16:35:33'),(64,5,'login',NULL,'User logged in successfully','::1','2026-01-28 16:35:39'),(65,5,'logout',NULL,'User logged out','::1','2026-01-28 16:36:17'),(66,4,'login',NULL,'User logged in successfully','::1','2026-01-28 16:36:22'),(67,4,'logout',NULL,'User logged out','::1','2026-01-28 16:37:18'),(68,3,'login',NULL,'User logged in successfully','::1','2026-01-28 16:37:26'),(69,3,'logout',NULL,'User logged out','::1','2026-01-28 16:44:31'),(70,5,'login',NULL,'User logged in successfully','::1','2026-01-28 16:44:37'),(71,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-28 16:45:03'),(72,5,'logout',NULL,'User logged out','::1','2026-01-28 16:47:07'),(73,2,'login',NULL,'User logged in successfully','::1','2026-01-28 16:47:14'),(74,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-29 03:27:10'),(75,4,'login',NULL,'User logged in successfully','::1','2026-01-29 03:41:09'),(76,1,'role_create',NULL,'Created new role: System Admin','127.0.0.1','2026-01-29 04:31:11'),(77,1,'role_update',NULL,'Updated permissions for role: 6','127.0.0.1','2026-01-29 04:32:22'),(78,1,'user_create',NULL,'Created new user: kondowe','127.0.0.1','2026-01-29 04:34:35'),(79,4,'logout',NULL,'User logged out','::1','2026-01-29 04:34:42'),(80,6,'login',NULL,'User logged in successfully','::1','2026-01-29 04:34:51'),(81,6,'role_update',NULL,'Updated permissions for role: 4','::1','2026-01-29 04:41:50'),(82,6,'logout',NULL,'User logged out','::1','2026-01-29 04:53:40'),(83,4,'login',NULL,'User logged in successfully','::1','2026-01-29 04:53:47'),(84,4,'logout',NULL,'User logged out','::1','2026-01-29 04:59:06'),(85,6,'login',NULL,'User logged in successfully','::1','2026-01-29 04:59:13'),(86,6,'department_update',NULL,'Updated department: ITC','::1','2026-01-29 05:17:01'),(87,6,'logout',NULL,'User logged out','::1','2026-01-29 05:26:13'),(88,4,'login',NULL,'User logged in successfully','::1','2026-01-29 05:26:32'),(89,4,'logout',NULL,'User logged out','::1','2026-01-29 05:53:44'),(90,6,'login',NULL,'User logged in successfully','::1','2026-01-29 05:57:26'),(91,6,'item_update',NULL,'Updated item: Milk (ID: 2)','::1','2026-01-29 06:14:45'),(92,6,'logout',NULL,'User logged out','::1','2026-01-29 06:32:21'),(93,4,'login',NULL,'User logged in successfully','::1','2026-01-29 06:32:26'),(94,4,'logout',NULL,'User logged out','::1','2026-01-29 06:46:18'),(95,5,'login',NULL,'User logged in successfully','::1','2026-01-29 06:46:24'),(96,5,'logout',NULL,'User logged out','::1','2026-01-29 06:46:59'),(97,3,'login',NULL,'User logged in successfully','::1','2026-01-29 06:47:05'),(98,3,'logout',NULL,'User logged out','::1','2026-01-29 06:47:54'),(99,5,'login',NULL,'User logged in successfully','::1','2026-01-29 06:48:01'),(100,5,'logout',NULL,'User logged out','::1','2026-01-29 06:55:42'),(101,5,'login',NULL,'User logged in successfully','::1','2026-01-29 06:55:46'),(102,5,'request_item_update',NULL,'Updated request item 8 to quantity 4.99','::1','2026-01-29 07:01:10'),(103,5,'request_item_update',NULL,'Updated request item 8 to quantity 4.98','::1','2026-01-29 07:01:11'),(104,5,'request_item_update',NULL,'Updated request item 8 to quantity 1','::1','2026-01-29 07:01:21'),(105,5,'request_item_update',NULL,'Updated request item 9 to quantity 1','::1','2026-01-29 07:01:27'),(106,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-29 07:02:49'),(107,1,'role_create',NULL,'Created new role: VIP','127.0.0.1','2026-01-29 07:16:42'),(108,1,'role_update',NULL,'Updated permissions for role: 7','127.0.0.1','2026-01-29 07:17:27'),(109,5,'logout',NULL,'User logged out','::1','2026-01-29 07:24:51'),(110,6,'login',NULL,'User logged in successfully','::1','2026-01-29 07:25:00'),(111,6,'settings_update',NULL,'Updated request restriction rules','::1','2026-01-29 08:25:06'),(112,6,'logout',NULL,'User logged out','::1','2026-01-29 08:25:16'),(113,4,'login',NULL,'User logged in successfully','::1','2026-01-29 08:25:24'),(114,4,'logout',NULL,'User logged out','::1','2026-01-29 08:29:42'),(115,3,'login',NULL,'User logged in successfully','::1','2026-01-29 08:30:00'),(116,3,'logout',NULL,'User logged out','::1','2026-01-29 08:34:44'),(117,5,'login',NULL,'User logged in successfully','::1','2026-01-29 08:34:50'),(118,5,'logout',NULL,'User logged out','::1','2026-01-29 08:38:45'),(119,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-29 08:39:00'),(120,1,'logout',NULL,'User logged out','127.0.0.1','2026-01-29 08:40:45'),(121,1,'login',NULL,'User logged in successfully','127.0.0.1','2026-01-29 08:40:50'),(122,2,'login',NULL,'User logged in successfully','::1','2026-01-29 08:41:05'),(123,2,'logout',NULL,'User logged out','::1','2026-01-29 08:46:18'),(124,5,'login',NULL,'User logged in successfully','::1','2026-01-29 08:46:25'),(125,5,'logout',NULL,'User logged out','::1','2026-01-29 08:47:39'),(126,2,'login',NULL,'User logged in successfully','::1','2026-01-29 08:47:45'),(127,2,'logout',NULL,'User logged out','::1','2026-01-29 08:53:55'),(128,3,'login',NULL,'User logged in successfully','::1','2026-01-29 08:54:07'),(129,3,'logout',NULL,'User logged out','::1','2026-01-29 08:54:41'),(130,3,'login',NULL,'User logged in successfully','::1','2026-01-29 08:55:23'),(131,3,'logout',NULL,'User logged out','::1','2026-01-29 08:55:27'),(132,6,'login',NULL,'User logged in successfully','::1','2026-01-29 08:55:49'),(133,6,'user_create',NULL,'Created new user: Philie','::1','2026-01-29 09:53:58'),(134,6,'user_update',NULL,'Updated user: admin','::1','2026-01-29 10:11:20'),(135,6,'logout',NULL,'User logged out','::1','2026-01-29 10:25:40'),(136,6,'login',NULL,'User logged in successfully','::1','2026-01-29 10:26:17');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `audit_log_id` bigint NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` int NOT NULL,
  `action` enum('create','update','delete','approve','reject','issue','receive','cancel') COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`audit_log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delegations`
--

DROP TABLE IF EXISTS `delegations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delegations` (
  `delegation_id` int NOT NULL AUTO_INCREMENT,
  `delegator_user_id` int NOT NULL,
  `delegate_user_id` int NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('active','inactive','expired') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`delegation_id`),
  KEY `delegator_user_id` (`delegator_user_id`),
  KEY `delegate_user_id` (`delegate_user_id`),
  CONSTRAINT `delegations_ibfk_1` FOREIGN KEY (`delegator_user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `delegations_ibfk_2` FOREIGN KEY (`delegate_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delegations`
--

LOCK TABLES `delegations` WRITE;
/*!40000 ALTER TABLE `delegations` DISABLE KEYS */;
/*!40000 ALTER TABLE `delegations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor_user_id` int DEFAULT NULL,
  `parent_department_id` int DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `department_code` (`department_code`),
  KEY `idx_supervisor` (`supervisor_user_id`),
  KEY `idx_parent` (`parent_department_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`parent_department_id`) REFERENCES `departments` (`department_id`),
  CONSTRAINT `departments_ibfk_2` FOREIGN KEY (`supervisor_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Administration','ADM',NULL,NULL,'active','2026-01-28 08:01:18','2026-01-28 08:01:18',NULL),(2,'Finance','FIN',NULL,NULL,'active','2026-01-28 08:01:18','2026-01-28 08:01:18',NULL),(3,'ITC','IT',NULL,NULL,'active','2026-01-28 08:01:18','2026-01-29 05:17:01',NULL),(4,'Operations','OPS',NULL,NULL,'active','2026-01-28 08:01:18','2026-01-28 08:01:18',NULL);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fuel_coupon_issuance`
--

DROP TABLE IF EXISTS `fuel_coupon_issuance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fuel_coupon_issuance` (
  `fuel_issuance_id` int NOT NULL AUTO_INCREMENT,
  `issue_voucher_id` int NOT NULL,
  `coupon_id` int NOT NULL,
  `request_id` int NOT NULL,
  `vehicle_id` int DEFAULT NULL,
  `issued_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fuel_issuance_id`),
  KEY `issue_voucher_id` (`issue_voucher_id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `request_id` (`request_id`),
  KEY `vehicle_id` (`vehicle_id`),
  CONSTRAINT `fuel_coupon_issuance_ibfk_1` FOREIGN KEY (`issue_voucher_id`) REFERENCES `issue_vouchers` (`issue_voucher_id`),
  CONSTRAINT `fuel_coupon_issuance_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `fuel_coupons` (`coupon_id`),
  CONSTRAINT `fuel_coupon_issuance_ibfk_3` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`),
  CONSTRAINT `fuel_coupon_issuance_ibfk_4` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fuel_coupon_issuance`
--

LOCK TABLES `fuel_coupon_issuance` WRITE;
/*!40000 ALTER TABLE `fuel_coupon_issuance` DISABLE KEYS */;
INSERT INTO `fuel_coupon_issuance` VALUES (1,3,1,7,NULL,'2026-01-28 16:50:04'),(2,3,10,7,NULL,'2026-01-28 16:50:04'),(3,3,2,7,NULL,'2026-01-28 16:50:04'),(4,4,13,9,NULL,'2026-01-29 08:42:52'),(5,4,17,9,NULL,'2026-01-29 08:42:52'),(6,4,19,9,NULL,'2026-01-29 08:42:52'),(7,4,20,9,NULL,'2026-01-29 08:42:52'),(8,4,5,9,NULL,'2026-01-29 08:42:52');
/*!40000 ALTER TABLE `fuel_coupon_issuance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fuel_coupons`
--

DROP TABLE IF EXISTS `fuel_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fuel_coupons` (
  `coupon_id` int NOT NULL AUTO_INCREMENT,
  `coupon_serial_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` int NOT NULL COMMENT 'Links to items table',
  `fuel_type_id` int NOT NULL,
  `coupon_value` decimal(10,2) NOT NULL COMMENT 'Monetary value or liters',
  `value_type` enum('amount','liters') COLLATE utf8mb4_unicode_ci DEFAULT 'amount',
  `expiry_date` date DEFAULT NULL,
  `status` enum('available','reserved','issued','expired','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'available',
  `grv_id` int NOT NULL COMMENT 'Traceability to GRV',
  `issued_in_issue_voucher_id` int DEFAULT NULL,
  `issued_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`coupon_id`),
  UNIQUE KEY `coupon_serial_number` (`coupon_serial_number`),
  KEY `idx_serial` (`coupon_serial_number`),
  KEY `idx_status` (`status`),
  KEY `idx_fuel_type` (`fuel_type_id`),
  KEY `idx_expiry` (`expiry_date`),
  KEY `idx_grv` (`grv_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `fuel_coupons_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  CONSTRAINT `fuel_coupons_ibfk_2` FOREIGN KEY (`fuel_type_id`) REFERENCES `fuel_types` (`fuel_type_id`),
  CONSTRAINT `fuel_coupons_ibfk_3` FOREIGN KEY (`grv_id`) REFERENCES `goods_received_vouchers` (`grv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fuel_coupons`
--

LOCK TABLES `fuel_coupons` WRITE;
/*!40000 ALTER TABLE `fuel_coupons` DISABLE KEYS */;
INSERT INTO `fuel_coupons` VALUES (1,'1',3,1,20.00,'amount','2026-12-31','issued',4,3,'2026-01-28 16:50:04','2026-01-28 18:36:07','2026-01-28 18:50:04'),(2,'2',3,1,20.00,'amount','2026-12-31','issued',4,3,'2026-01-28 16:50:04','2026-01-28 18:36:07','2026-01-28 18:50:04'),(3,'3',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(4,'4',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(5,'5',3,1,20.00,'amount','2026-12-31','issued',4,4,'2026-01-29 08:42:52','2026-01-28 18:36:07','2026-01-29 10:42:52'),(6,'6',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(7,'7',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(8,'8',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(9,'9',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(10,'10',3,1,20.00,'amount','2026-12-31','issued',4,3,'2026-01-28 16:50:04','2026-01-28 18:36:07','2026-01-28 18:50:04'),(11,'11',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(12,'12',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(13,'13',3,1,20.00,'amount','2026-12-31','issued',4,4,'2026-01-29 08:42:52','2026-01-28 18:36:07','2026-01-29 10:42:52'),(14,'14',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(15,'15',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(16,'16',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(17,'17',3,1,20.00,'amount','2026-12-31','issued',4,4,'2026-01-29 08:42:52','2026-01-28 18:36:07','2026-01-29 10:42:52'),(18,'18',3,1,20.00,'amount','2026-12-31','available',4,NULL,NULL,'2026-01-28 18:36:07','2026-01-28 18:36:07'),(19,'19',3,1,20.00,'amount','2026-12-31','issued',4,4,'2026-01-29 08:42:52','2026-01-28 18:36:07','2026-01-29 10:42:52'),(20,'20',3,1,20.00,'amount','2026-12-31','issued',4,4,'2026-01-29 08:42:52','2026-01-28 18:36:07','2026-01-29 10:42:52');
/*!40000 ALTER TABLE `fuel_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fuel_types`
--

DROP TABLE IF EXISTS `fuel_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fuel_types` (
  `fuel_type_id` int NOT NULL AUTO_INCREMENT,
  `fuel_type_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fuel_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fuel_type_id`),
  UNIQUE KEY `fuel_code` (`fuel_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fuel_types`
--

LOCK TABLES `fuel_types` WRITE;
/*!40000 ALTER TABLE `fuel_types` DISABLE KEYS */;
INSERT INTO `fuel_types` VALUES (1,'Petrol','PETROL','Regular petrol','2026-01-28 08:01:18'),(2,'Diesel','DIESEL','Diesel fuel','2026-01-28 08:01:18');
/*!40000 ALTER TABLE `fuel_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goods_received_vouchers`
--

DROP TABLE IF EXISTS `goods_received_vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `goods_received_vouchers` (
  `grv_id` int NOT NULL AUTO_INCREMENT,
  `grv_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` int NOT NULL,
  `store_id` int NOT NULL,
  `reference_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'PO/Donation/Transfer reference',
  `reference_type` enum('purchase_order','donation','transfer','other') COLLATE utf8mb4_unicode_ci DEFAULT 'purchase_order',
  `received_date` date NOT NULL,
  `received_by_user_id` int NOT NULL,
  `approved_by_user_id` int DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `status` enum('draft','pending_approval','approved','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `total_value` decimal(12,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`grv_id`),
  UNIQUE KEY `grv_number` (`grv_number`),
  KEY `idx_grv_number` (`grv_number`),
  KEY `idx_supplier` (`supplier_id`),
  KEY `idx_status` (`status`),
  KEY `idx_received_date` (`received_date`),
  KEY `store_id` (`store_id`),
  KEY `received_by_user_id` (`received_by_user_id`),
  KEY `approved_by_user_id` (`approved_by_user_id`),
  CONSTRAINT `goods_received_vouchers_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`),
  CONSTRAINT `goods_received_vouchers_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`),
  CONSTRAINT `goods_received_vouchers_ibfk_3` FOREIGN KEY (`received_by_user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `goods_received_vouchers_ibfk_4` FOREIGN KEY (`approved_by_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods_received_vouchers`
--

LOCK TABLES `goods_received_vouchers` WRITE;
/*!40000 ALTER TABLE `goods_received_vouchers` DISABLE KEYS */;
INSERT INTO `goods_received_vouchers` VALUES (2,'GRV-2026-000002',1,1,'sw2343','purchase_order','2026-01-28',2,5,'2026-01-28 10:22:40','approved',50.00,'','2026-01-28 10:04:02','2026-01-28 10:22:40'),(3,'GRV-2026-000003',2,1,'sw234eee','purchase_order','2026-01-28',2,5,'2026-01-28 15:53:34','approved',1500.00,'','2026-01-28 15:52:27','2026-01-28 15:53:34'),(4,'GRV-2026-000004',2,1,'koiggf','purchase_order','2026-01-28',2,5,'2026-01-28 16:36:07','approved',600.00,'ygygyu yyuyu','2026-01-28 16:35:16','2026-01-28 16:36:07');
/*!40000 ALTER TABLE `goods_received_vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grv_items`
--

DROP TABLE IF EXISTS `grv_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grv_items` (
  `grv_item_id` int NOT NULL AUTO_INCREMENT,
  `grv_id` int NOT NULL,
  `item_id` int NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(12,2) GENERATED ALWAYS AS ((`quantity` * `unit_cost`)) STORED,
  `batch_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_fuel_coupon` tinyint(1) DEFAULT '0' COMMENT 'Fuel-specific field',
  `fuel_type_id` int DEFAULT NULL,
  `coupon_serial_from` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_serial_to` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_count` int DEFAULT NULL,
  `coupon_value` decimal(10,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`grv_item_id`),
  KEY `idx_grv` (`grv_id`),
  KEY `idx_item` (`item_id`),
  KEY `idx_fuel` (`is_fuel_coupon`),
  KEY `fuel_type_id` (`fuel_type_id`),
  CONSTRAINT `grv_items_ibfk_1` FOREIGN KEY (`grv_id`) REFERENCES `goods_received_vouchers` (`grv_id`) ON DELETE CASCADE,
  CONSTRAINT `grv_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  CONSTRAINT `grv_items_ibfk_3` FOREIGN KEY (`fuel_type_id`) REFERENCES `fuel_types` (`fuel_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grv_items`
--

LOCK TABLES `grv_items` WRITE;
/*!40000 ALTER TABLE `grv_items` DISABLE KEYS */;
INSERT INTO `grv_items` (`grv_item_id`, `grv_id`, `item_id`, `quantity`, `unit_cost`, `batch_number`, `expiry_date`, `is_fuel_coupon`, `fuel_type_id`, `coupon_serial_from`, `coupon_serial_to`, `coupon_count`, `coupon_value`, `notes`) VALUES (1,2,2,5.00,10.00,'566','2026-01-28',0,NULL,NULL,NULL,NULL,NULL,'kjsdjs dsjdks'),(2,3,4,1000.00,1.50,'6834','2026-12-31',0,NULL,NULL,NULL,NULL,NULL,'Petrol Trade'),(3,4,3,400.00,1.50,'yiun','2026-12-31',1,1,'1','20',20,20.00,'');
/*!40000 ALTER TABLE `grv_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issue_voucher_items`
--

DROP TABLE IF EXISTS `issue_voucher_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `issue_voucher_items` (
  `issue_voucher_item_id` int NOT NULL AUTO_INCREMENT,
  `issue_voucher_id` int NOT NULL,
  `request_item_id` int NOT NULL,
  `item_id` int DEFAULT NULL,
  `quantity_issued` decimal(10,2) NOT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `batch_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`issue_voucher_item_id`),
  KEY `idx_voucher` (`issue_voucher_id`),
  KEY `request_item_id` (`request_item_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `issue_voucher_items_ibfk_1` FOREIGN KEY (`issue_voucher_id`) REFERENCES `issue_vouchers` (`issue_voucher_id`) ON DELETE CASCADE,
  CONSTRAINT `issue_voucher_items_ibfk_2` FOREIGN KEY (`request_item_id`) REFERENCES `request_items` (`request_item_id`),
  CONSTRAINT `issue_voucher_items_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issue_voucher_items`
--

LOCK TABLES `issue_voucher_items` WRITE;
/*!40000 ALTER TABLE `issue_voucher_items` DISABLE KEYS */;
INSERT INTO `issue_voucher_items` VALUES (1,1,6,2,1.00,NULL,NULL,NULL),(2,2,5,4,120.00,NULL,NULL,NULL),(3,3,7,3,60.00,NULL,NULL,NULL),(4,4,10,3,100.00,NULL,NULL,NULL);
/*!40000 ALTER TABLE `issue_voucher_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issue_vouchers`
--

DROP TABLE IF EXISTS `issue_vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `issue_vouchers` (
  `issue_voucher_id` int NOT NULL AUTO_INCREMENT,
  `issue_voucher_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_id` int NOT NULL,
  `store_id` int NOT NULL,
  `issued_by_user_id` int NOT NULL,
  `received_by_user_id` int DEFAULT NULL,
  `received_by_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('draft','issued','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'issued',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`issue_voucher_id`),
  UNIQUE KEY `issue_voucher_number` (`issue_voucher_number`),
  KEY `idx_voucher_number` (`issue_voucher_number`),
  KEY `request_id` (`request_id`),
  KEY `store_id` (`store_id`),
  KEY `issued_by_user_id` (`issued_by_user_id`),
  CONSTRAINT `issue_vouchers_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`),
  CONSTRAINT `issue_vouchers_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`),
  CONSTRAINT `issue_vouchers_ibfk_3` FOREIGN KEY (`issued_by_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issue_vouchers`
--

LOCK TABLES `issue_vouchers` WRITE;
/*!40000 ALTER TABLE `issue_vouchers` DISABLE KEYS */;
INSERT INTO `issue_vouchers` VALUES (1,'IV-2026-000001',6,1,2,NULL,'John Doe','2026-01-28 15:42:59','issued','','2026-01-28 17:42:59'),(2,'IV-2026-000002',5,1,2,NULL,'John Doe','2026-01-28 15:58:28','issued','','2026-01-28 15:58:28'),(3,'IV-2026-000003',7,1,2,NULL,'John Doe','2026-01-28 16:50:04','issued','','2026-01-28 16:50:04'),(4,'IV-2026-000004',9,1,2,NULL,'John Doe','2026-01-29 08:42:52','issued','','2026-01-29 08:42:52');
/*!40000 ALTER TABLE `issue_vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_categories`
--

DROP TABLE IF EXISTS `item_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_category_id` int DEFAULT NULL,
  `is_fuel_category` tinyint(1) DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_code` (`category_code`),
  KEY `idx_parent` (`parent_category_id`),
  KEY `idx_fuel` (`is_fuel_category`),
  CONSTRAINT `item_categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `item_categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_categories`
--

LOCK TABLES `item_categories` WRITE;
/*!40000 ALTER TABLE `item_categories` DISABLE KEYS */;
INSERT INTO `item_categories` VALUES (1,'Office Supplies','OFF',NULL,0,'Stationery and office consumables','2026-01-28 08:01:18',NULL),(2,'IT Equipment','IT',NULL,0,'Computers, laptops, peripherals','2026-01-28 08:01:18',NULL),(3,'Fuel Coupons','FUEL',NULL,1,'Fuel coupons for vehicles','2026-01-28 08:01:18',NULL);
/*!40000 ALTER TABLE `item_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `uom_id` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `minimum_stock_level` decimal(10,2) DEFAULT '0.00',
  `reorder_level` decimal(10,2) DEFAULT '0.00',
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_category` (`category_id`),
  KEY `idx_sku` (`sku`),
  KEY `idx_active` (`is_active`),
  KEY `uom_id` (`uom_id`),
  FULLTEXT KEY `idx_search` (`item_name`,`description`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`category_id`),
  CONSTRAINT `items_ibfk_2` FOREIGN KEY (`uom_id`) REFERENCES `units_of_measure` (`uom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (2,'SD','Milk',1,2,'',2.00,5.00,10.00,1,'2026-01-28 10:04:02','2026-01-29 06:14:45',NULL),(3,'FUEL-PETROL','Petrol (Liters)',3,4,NULL,0.00,0.00,NULL,1,'2026-01-28 14:37:38','2026-01-28 16:37:38',NULL),(4,'FUEL-DIESEL','Petrol Trade Diesel 20 litres',3,4,'',100.00,250.00,0.00,1,'2026-01-28 14:37:38','2026-01-28 16:04:07',NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `number_sequences`
--

DROP TABLE IF EXISTS `number_sequences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `number_sequences` (
  `sequence_id` int NOT NULL AUTO_INCREMENT,
  `sequence_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_number` int DEFAULT '0',
  `padding` int DEFAULT '6',
  `reset_frequency` enum('never','daily','monthly','yearly') COLLATE utf8mb4_unicode_ci DEFAULT 'yearly',
  `last_reset_date` date DEFAULT NULL,
  PRIMARY KEY (`sequence_id`),
  UNIQUE KEY `sequence_name` (`sequence_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `number_sequences`
--

LOCK TABLES `number_sequences` WRITE;
/*!40000 ALTER TABLE `number_sequences` DISABLE KEYS */;
INSERT INTO `number_sequences` VALUES (1,'request','REQ-',11,6,'yearly','2026-01-29'),(2,'grv','GRV-',4,6,'yearly','2026-01-28'),(3,'issue','IV-',4,6,'yearly','2026-01-29');
/*!40000 ALTER TABLE `number_sequences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `permission_id` int NOT NULL AUTO_INCREMENT,
  `permission_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_key` (`permission_key`),
  KEY `idx_module` (`module`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'request.create','request','create','Create new request'),(2,'request.view_own','request','view_own','View own requests'),(3,'request.view_department','request','view_department','View department requests'),(4,'request.view_all','request','view_all','View all requests'),(5,'request.edit_own','request','edit_own','Edit own requests'),(6,'request.cancel_own','request','cancel_own','Cancel own requests'),(7,'request.approve','request','approve','Approve requests'),(8,'grv.create','grv','create','Create GRV'),(9,'grv.view','grv','view','View GRV'),(10,'grv.approve','grv','approve','Approve GRV'),(11,'issue.create','issue','create','Create issue voucher'),(12,'issue.view','issue','view','View issue vouchers'),(13,'inventory.view','inventory','view','View inventory'),(14,'inventory.manage','inventory','manage','Manage inventory'),(15,'workflow.configure','workflow','configure','Configure workflow'),(16,'workflow.view','workflow','view','View workflow'),(17,'report.view_own','report','view_own','View own reports'),(18,'report.view_department','report','view_department','View department reports'),(19,'report.view_all','report','view_all','View all reports'),(20,'user.create','user','create','Create users'),(21,'user.edit','user','edit','Edit users'),(22,'user.view','user','view','View users'),(23,'dashboard.requester','dashboard','requester','Access requester dashboard'),(24,'dashboard.supervisor','dashboard','supervisor','Access supervisor dashboard'),(25,'dashboard.admin_manager','dashboard','admin_manager','Access admin manager dashboard'),(26,'dashboard.general_admin','dashboard','general_admin','Access general admin dashboard'),(27,'dashboard.stores_officer','dashboard','stores_officer','Access stores officer dashboard');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_items`
--

DROP TABLE IF EXISTS `request_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_items` (
  `request_item_id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `item_id` int DEFAULT NULL,
  `is_custom` tinyint(1) DEFAULT '0',
  `custom_item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_requested` decimal(10,2) NOT NULL,
  `quantity_approved` decimal(10,2) DEFAULT '0.00',
  `quantity_issued` decimal(10,2) DEFAULT '0.00',
  `quantity_outstanding` decimal(10,2) GENERATED ALWAYS AS ((`quantity_approved` - `quantity_issued`)) STORED,
  `unit_cost_estimate` decimal(10,2) DEFAULT NULL,
  `justification` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','partially_issued','issued') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  PRIMARY KEY (`request_item_id`),
  KEY `idx_request` (`request_id`),
  KEY `idx_item` (`item_id`),
  CONSTRAINT `request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`) ON DELETE CASCADE,
  CONSTRAINT `request_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_items`
--

LOCK TABLES `request_items` WRITE;
/*!40000 ALTER TABLE `request_items` DISABLE KEYS */;
INSERT INTO `request_items` (`request_item_id`, `request_id`, `item_id`, `is_custom`, `custom_item_name`, `quantity_requested`, `quantity_approved`, `quantity_issued`, `unit_cost_estimate`, `justification`, `status`) VALUES (1,1,2,0,NULL,1.00,0.00,0.00,NULL,'','pending'),(2,2,2,0,NULL,1.00,1.00,0.00,NULL,'','pending'),(3,3,2,0,NULL,1.00,0.00,0.00,NULL,'','pending'),(4,4,2,0,NULL,1.00,0.00,0.00,NULL,'','pending'),(5,5,4,0,NULL,120.00,120.00,120.00,NULL,'Requested total liters','issued'),(6,6,2,0,NULL,1.00,1.00,1.00,NULL,'','issued'),(7,7,3,0,NULL,60.00,60.00,60.00,NULL,'Requested total liters','issued'),(8,8,NULL,1,'Test Custom item',1.00,0.00,0.00,NULL,'','pending'),(9,8,2,0,NULL,1.00,0.00,0.00,NULL,'','pending'),(10,9,3,0,NULL,100.00,100.00,100.00,NULL,'Requested total liters','issued');
/*!40000 ALTER TABLE `request_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `request_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_type` enum('item','fuel') COLLATE utf8mb4_unicode_ci DEFAULT 'item',
  `requester_user_id` int NOT NULL,
  `department_id` int NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `status` enum('draft','pending','approved','rejected','partially_issued','issued','closed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `date_required` date DEFAULT NULL,
  `current_workflow_step_id` int DEFAULT NULL,
  `workflow_instance_id` int DEFAULT NULL,
  `vehicle_id` int DEFAULT NULL COMMENT 'Fuel-specific field',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `departure_point` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_point` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_round_trip` tinyint(1) DEFAULT '0',
  `departure_date` date DEFAULT NULL,
  `request_company_vehicle` tinyint(1) DEFAULT '0',
  `fuel_type_id` int DEFAULT NULL,
  PRIMARY KEY (`request_id`),
  UNIQUE KEY `request_number` (`request_number`),
  KEY `idx_request_number` (`request_number`),
  KEY `idx_requester` (`requester_user_id`),
  KEY `idx_department` (`department_id`),
  KEY `idx_status` (`status`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `fk_requests_fuel_type` (`fuel_type_id`),
  CONSTRAINT `fk_requests_fuel_type` FOREIGN KEY (`fuel_type_id`) REFERENCES `fuel_types` (`fuel_type_id`),
  CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`requester_user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  CONSTRAINT `requests_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
INSERT INTO `requests` VALUES (1,'REQ-2026-000001','item',4,3,'poerer roe','low','rejected','2026-01-31',NULL,1,NULL,'2026-01-28 12:32:41','2026-01-28 12:00:19','2026-01-28 10:32:41',NULL,NULL,NULL,NULL,NULL,0,NULL,0,NULL),(2,'REQ-2026-000002','item',4,3,'sjsns dskds sk','low','approved','2026-01-31',NULL,2,NULL,'2026-01-28 13:02:58','2026-01-28 11:17:04','2026-01-28 11:02:58',NULL,NULL,NULL,NULL,NULL,0,NULL,0,NULL),(3,'REQ-2026-000003','item',4,3,'Hope this works','low','pending','2026-01-31',NULL,3,NULL,'2026-01-28 11:44:12','2026-01-28 11:44:12','2026-01-28 11:44:12',NULL,NULL,NULL,NULL,NULL,0,NULL,0,NULL),(4,'REQ-2026-000004','item',4,3,'feferfe','medium','rejected','2026-01-31',NULL,4,NULL,'2026-01-28 14:54:14','2026-01-29 07:13:01','2026-01-28 14:54:14',NULL,NULL,NULL,NULL,NULL,0,NULL,0,NULL),(5,'REQ-2026-000005','fuel',4,3,'kjkjn kjnlknl','medium','issued','2026-01-28',NULL,5,NULL,'2026-01-28 15:02:15','2026-01-28 15:58:28','2026-01-28 15:02:15',NULL,NULL,NULL,'he','dsfsd',1,'2026-01-28',1,2),(6,'REQ-2026-000006','item',4,3,'dewf fewfef','high','issued','2026-01-31',NULL,6,NULL,'2026-01-28 15:30:35','2026-01-28 15:43:00','2026-01-28 15:30:35',NULL,NULL,NULL,NULL,NULL,0,NULL,0,NULL),(7,'REQ-2026-000007','fuel',4,3,'ofpore refpe','low','issued','2026-01-28',NULL,7,NULL,'2026-01-28 16:37:09','2026-01-28 16:50:04','2026-01-28 16:37:09',NULL,NULL,NULL,'he','dsfsd',1,'2026-01-28',1,1),(8,'REQ-2026-000009','item',4,3,'test custom','low','pending','2026-02-01',NULL,8,NULL,'2026-01-29 06:33:11','2026-01-29 06:33:11','2026-01-29 06:33:11',NULL,NULL,NULL,NULL,NULL,0,NULL,0,NULL),(9,'REQ-2026-000011','fuel',4,3,'ewrwcewfc dstryrty sgrdy','low','issued','2026-01-29',NULL,9,NULL,'2026-01-29 08:28:59','2026-01-29 08:42:52','2026-01-29 08:28:59',NULL,NULL,NULL,'HQ','Mutare',1,'2026-01-29',1,1);
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1),(6,1),(7,1),(1,2),(2,2),(6,2),(7,2),(2,3),(6,3),(3,4),(4,4),(6,4),(1,5),(6,5),(7,5),(1,6),(6,6),(7,6),(2,7),(3,7),(4,7),(6,7),(5,8),(6,8),(3,9),(4,9),(5,9),(6,9),(3,10),(4,10),(6,10),(5,11),(6,11),(5,12),(6,12),(3,13),(4,13),(5,13),(6,13),(4,14),(5,14),(6,14),(2,15),(6,15),(2,16),(3,16),(6,16),(6,17),(6,18),(4,19),(6,19),(6,20),(6,21),(6,22),(1,23),(6,23),(7,23),(2,24),(6,24),(3,25),(6,25),(4,26),(6,26),(5,27),(6,27);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'requester, dept_supervisor, admin_mgr, general_admin_mgr, stores_officer',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`),
  UNIQUE KEY `role_key` (`role_key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Requester','requester','Creates item requests, views own requests and statuses','2026-01-28 08:01:18'),(2,'Department Supervisor','dept_supervisor','Approves department requests, configures department workflow','2026-01-28 08:01:18'),(3,'Administration Manager','admin_mgr','Approves/rejects requests system-wide, views inventory availability','2026-01-28 08:01:18'),(4,'General Administration Manager','general_admin_mgr','Final approval authority, view system-wide dashboards and reports','2026-01-28 08:01:18'),(5,'Stores Officer','stores_officer','Manages inventory stock, creates GRVs, issues items against approved requests','2026-01-28 08:01:18'),(6,'System Admin','system_admin','Manages Entire System','2026-01-29 06:31:11'),(7,'VIP','requester_vip','VIP requests','2026-01-29 09:16:42');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `session_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_last_activity` (`last_activity`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0009a4960303b5942873e2cfb30c10f8',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-28 15:52:54','2026-01-28 15:52:54'),('03668bce7e147947495fb13ba6428cd8',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-29 09:02:49','2026-01-29 09:02:49'),('088f32bc793b0b6428bc7f371da543ee',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 06:59:13','2026-01-29 06:59:13'),('0c46763613b22cecc8b87de424fc5a44',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 18:37:26','2026-01-28 18:37:26'),('167cf3b9e0c634fbc186a8569af468b4',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-28 11:10:41','2026-01-28 11:10:41'),('221145c987afd1c437df7cbde5d3b03b',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 05:41:09','2026-01-29 05:41:09'),('23433f105e75fef0eb8e374a42027806',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 15:50:22','2026-01-28 15:50:22'),('255f8952538b8c7ba2efb96122879a28',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 06:53:47','2026-01-29 06:53:47'),('3e9d80b5c049e41e11e22ec9d61ce4c9',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 13:03:45','2026-01-28 13:03:45'),('3eb11600aa6bd753c36326c2a44cae5f',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 12:26:17','2026-01-29 12:26:17'),('40576258483f22f0aa81acabf5116322',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 13:17:38','2026-01-28 13:17:38'),('50eb9a82dfc046f72db3409e8f5ea7ec',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 18:47:14','2026-01-28 18:47:14'),('599b9921f83b3d57c8521d2f1bd3b81c',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 13:43:46','2026-01-28 13:43:46'),('6af62613b9edbb53d5f92e6c3c7b2786',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 07:26:32','2026-01-29 07:26:32'),('6c6d14404ab05aedf6e3e632da8bf601',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 17:34:28','2026-01-28 17:34:28'),('783ddc098e45e4b317cd6717a39b833c',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 07:57:26','2026-01-29 07:57:26'),('79296fe3f665fa82e63d2d183195f78f',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-29 05:27:10','2026-01-29 05:27:10'),('84a7c195a016fd67ae3635161d9b8e7c',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-28 17:14:03','2026-01-28 17:14:03'),('855651e04265fe092e3b5fc52ce20d11',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 17:47:35','2026-01-28 17:47:35'),('8b4496d7b47da570532885f8f4e1da09',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 10:41:05','2026-01-29 10:41:05'),('94332b4e3bc8f486acaba0610e5506b8',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 06:34:51','2026-01-29 06:34:51'),('99abaf394bcbc7e7b7e513b1e848fd8d',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 08:32:26','2026-01-29 08:32:26'),('a2322be6bfa1c545bac7bbb1cb364743',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 17:57:09','2026-01-28 17:57:09'),('a680317e915e5f44be531626aa76753d',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 10:30:00','2026-01-29 10:30:00'),('a791595d2b3c9214c04558da00df6c69',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-28 08:41:05','2026-01-28 08:41:05'),('ae2a3b73694d1cc9a87cc1ff58b15828',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 12:15:35','2026-01-28 12:15:35'),('b2d6ea9ed0539f48bdeedae9357e50cf',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 09:25:00','2026-01-29 09:25:00'),('b65d472684b6d558d1f9c1ed951a0c09',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 17:13:02','2026-01-28 17:13:02'),('c32c89a28d07205decc937a2bca9984b',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 08:55:46','2026-01-29 08:55:46'),('c732bce32371d79cfd7bce27573f1234',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 11:22:40','2026-01-28 11:22:40'),('ccd938708ea8cd66bc669bf78e7d191b',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 16:00:37','2026-01-28 16:00:37'),('d0f2772cceef3ad17468388c53710c4e',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 10:47:45','2026-01-29 10:47:45'),('dbb1ee885d45920577114dca7b44ae1f',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 14:06:24','2026-01-28 14:06:24'),('eb6e857bbbc04338dd342d63b3aa337b',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 10:55:49','2026-01-29 10:55:49'),('ee994a69bd2fcb5d46295b57f38653d4',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-28 12:25:00','2026-01-28 12:25:00'),('f5dcc467d7f56da6c62673618b451cc5',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-29 10:40:50','2026-01-29 10:40:50'),('fd292f0f76e8bf26203015fef75a9fd4',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-01-29 08:48:00','2026-01-29 08:48:00'),('fd2da791a0c1000260a9aec8961baa39',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','2026-01-28 18:45:03','2026-01-28 18:45:03');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_levels`
--

DROP TABLE IF EXISTS `stock_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_levels` (
  `stock_id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `store_id` int NOT NULL,
  `quantity_on_hand` decimal(10,2) DEFAULT '0.00',
  `quantity_reserved` decimal(10,2) DEFAULT '0.00' COMMENT 'For approved but not issued requests',
  `quantity_available` decimal(10,2) GENERATED ALWAYS AS ((`quantity_on_hand` - `quantity_reserved`)) STORED,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`stock_id`),
  UNIQUE KEY `uk_item_store` (`item_id`,`store_id`),
  KEY `idx_item` (`item_id`),
  KEY `idx_store` (`store_id`),
  KEY `idx_available` (`quantity_available`),
  CONSTRAINT `stock_levels_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  CONSTRAINT `stock_levels_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_levels`
--

LOCK TABLES `stock_levels` WRITE;
/*!40000 ALTER TABLE `stock_levels` DISABLE KEYS */;
INSERT INTO `stock_levels` (`stock_id`, `item_id`, `store_id`, `quantity_on_hand`, `quantity_reserved`, `last_updated`) VALUES (1,2,1,4.00,0.00,'2026-01-28 17:43:00'),(2,4,1,880.00,0.00,'2026-01-28 17:58:28'),(3,3,1,240.00,0.00,'2026-01-29 10:42:52');
/*!40000 ALTER TABLE `stock_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `movement_id` bigint NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `store_id` int NOT NULL,
  `movement_type` enum('grv_in','issue_out','adjustment','transfer_in','transfer_out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `balance_before` decimal(10,2) DEFAULT NULL,
  `balance_after` decimal(10,2) DEFAULT NULL,
  `performed_by_user_id` int NOT NULL,
  `movement_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`movement_id`),
  KEY `idx_item` (`item_id`),
  KEY `idx_store` (`store_id`),
  KEY `performed_by_user_id` (`performed_by_user_id`),
  CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`),
  CONSTRAINT `stock_movements_ibfk_3` FOREIGN KEY (`performed_by_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES (1,2,1,'grv_in',5.00,'grv',2,0.00,5.00,5,'2026-01-28 12:22:40','Received via GRV #GRV-2026-000002'),(2,2,1,'issue_out',-1.00,'issue_voucher',1,5.00,4.00,2,'2026-01-28 17:42:59','Issued for Request #REQ-2026-000006'),(3,4,1,'grv_in',1000.00,'grv',3,0.00,1000.00,5,'2026-01-28 17:53:34','Received via GRV #GRV-2026-000003'),(4,4,1,'issue_out',-120.00,'issue_voucher',2,1000.00,880.00,2,'2026-01-28 17:58:28','Issued for Request #REQ-2026-000005'),(5,3,1,'grv_in',400.00,'grv',4,0.00,400.00,5,'2026-01-28 18:36:07','Received via GRV #GRV-2026-000004'),(6,3,1,'issue_out',-60.00,'issue_voucher',3,400.00,340.00,2,'2026-01-28 18:50:04','Issued for Request #REQ-2026-000007'),(7,3,1,'issue_out',-100.00,'issue_voucher',4,340.00,240.00,2,'2026-01-29 10:42:52','Issued for Request #REQ-2026-000011');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stores` (
  `store_id` int NOT NULL AUTO_INCREMENT,
  `store_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_type` enum('main','branch','department') COLLATE utf8mb4_unicode_ci DEFAULT 'main',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`store_id`),
  UNIQUE KEY `store_code` (`store_code`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES (1,'Main Store','MAIN','Building A, Ground Floor','main',1,'2026-01-28 08:01:18',NULL);
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `supplier_type` enum('general','fuel_vendor','both') COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`supplier_id`),
  UNIQUE KEY `supplier_code` (`supplier_code`),
  KEY `idx_active` (`is_active`),
  KEY `idx_type` (`supplier_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'ABC Supplies','ABC-001','John Smith',NULL,NULL,NULL,'general',1,'2026-01-28 08:01:18',NULL),(2,'Fuel Station','FUEL-001','Bob Jones',NULL,NULL,NULL,'fuel_vendor',1,'2026-01-28 08:01:18',NULL);
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_config`
--

DROP TABLE IF EXISTS `system_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_config` (
  `config_id` int NOT NULL AUTO_INCREMENT,
  `config_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8mb4_unicode_ci,
  `config_type` enum('string','number','boolean','json') COLLATE utf8mb4_unicode_ci DEFAULT 'string',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_editable` tinyint(1) DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_config`
--

LOCK TABLES `system_config` WRITE;
/*!40000 ALTER TABLE `system_config` DISABLE KEYS */;
INSERT INTO `system_config` VALUES (1,'app_name','Inventory Management System','string','Application name',1,'2026-01-28 08:01:18'),(2,'enable_email_notifications','true','boolean','Enable email notifications',1,'2026-01-28 08:01:18'),(3,'roles_can_request_below_reorder','[\"dept_supervisor\",\"admin_mgr\",\"general_admin_mgr\",\"requester_vip\"]','json','Roles allowed to request items below reorder level',1,'2026-01-29 10:25:06'),(4,'roles_can_request_below_min','[\"admin_mgr\",\"general_admin_mgr\",\"requester_vip\"]','json','Roles allowed to request items below minimum stock level',1,'2026-01-29 10:25:06'),(5,'enforce_stock_restrictions','true','boolean','Enable/Disable stock-level based request restrictions',1,'2026-01-29 09:22:31');
/*!40000 ALTER TABLE `system_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_of_measure`
--

DROP TABLE IF EXISTS `units_of_measure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units_of_measure` (
  `uom_id` int NOT NULL AUTO_INCREMENT,
  `uom_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uom_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uom_id`),
  UNIQUE KEY `uom_code` (`uom_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_of_measure`
--

LOCK TABLES `units_of_measure` WRITE;
/*!40000 ALTER TABLE `units_of_measure` DISABLE KEYS */;
INSERT INTO `units_of_measure` VALUES (1,'Piece','PCS','Individual items','2026-01-28 08:01:18'),(2,'Liter','L','Liters','2026-01-28 08:01:18'),(3,'Unit','UNIT','Generic unit','2026-01-28 08:01:18'),(4,'Liters','LTR',NULL,'2026-01-28 16:37:38');
/*!40000 ALTER TABLE `units_of_measure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int NOT NULL,
  `department_id` int DEFAULT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_role` (`role_id`),
  KEY `idx_department` (`department_id`),
  KEY `idx_status` (`status`),
  KEY `idx_deleted` (`deleted_at`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@inventorysystem.local','$2y$12$3iKaBCfgiYPPdUfNymKBIOjGlwXxaRv33n/BVunykJmu4kq/hVuVu','GM Admin',4,1,'active','2026-01-28 08:01:18','2026-01-29 10:11:20',NULL),(2,'stores','stores@inventorysystem.local','$2y$12$/MSA.tlPe9v8lAwKpl2bJ.sg7w9Ep6C8XBkW.tIfSBjZhhogBZYje','Stores Officer',5,4,'active','2026-01-28 08:01:18','2026-01-28 09:22:19',NULL),(3,'supervisor_it','supervisor.it@inventorysystem.local','$2y$12$OUEqgauI/pWXyX./BpIMYeyddUD6eZsxp3OkmZzpF3DHucM7bhqLK','IT Supervisor',2,3,'active','2026-01-28 08:01:18','2026-01-28 11:03:35',NULL),(4,'requester1','requester1@inventorysystem.local','$2y$12$Pwum9MtaHqhZMRXcb9P6v.ZZMDiPinefH0JmSne7vB13mEsUIT0bO','John Doe',1,3,'active','2026-01-28 08:01:18','2026-01-28 10:24:50',NULL),(5,'adminm','admin@mail.com','$2y$12$1kRG1NCX1plpsIxoC90Lt.BVtvO6JOWUyjefucsHVoEIYKbDjjT5G','Admin Manager',3,1,'active','2026-01-28 10:15:13','2026-01-28 12:15:13',NULL),(6,'kondowe','brian.kondowe@psc.org.zw','$2y$12$BYx9EzfS8SV88YAtmLLVGuaD4e3XCIPFYmOMRbnVAvmQzCLSaZNMG','Brian Kondowe',6,NULL,'active','2026-01-29 04:34:35','2026-01-29 06:34:35',NULL),(7,'Philie','philisani.sigaba@psc.org.zw','$2y$12$/VxY.eEP3v7f54fBpcniZObEw3ay2iBt/UHMGpAdQRcSUYBQmB4Ki','Philisani Sigaba',1,3,'active','2026-01-29 09:53:58','2026-01-29 11:53:58',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `vehicle_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fuel_type_id` int DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `status` enum('active','inactive','maintenance') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`vehicle_id`),
  UNIQUE KEY `vehicle_number` (`vehicle_number`),
  KEY `idx_department` (`department_id`),
  KEY `idx_status` (`status`),
  KEY `fuel_type_id` (`fuel_type_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`fuel_type_id`) REFERENCES `fuel_types` (`fuel_type_id`),
  CONSTRAINT `vehicles_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflow_instances`
--

DROP TABLE IF EXISTS `workflow_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow_instances` (
  `workflow_instance_id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `workflow_template_id` int NOT NULL,
  `current_step_order` int DEFAULT '1',
  `status` enum('in_progress','completed','rejected','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'in_progress',
  `started_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`workflow_instance_id`),
  KEY `idx_request` (`request_id`),
  KEY `idx_template` (`workflow_template_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_request_instance` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflow_instances`
--

LOCK TABLES `workflow_instances` WRITE;
/*!40000 ALTER TABLE `workflow_instances` DISABLE KEYS */;
INSERT INTO `workflow_instances` VALUES (1,1,5,1,'rejected','2026-01-28 10:32:41','2026-01-28 12:00:19'),(2,2,5,1,'completed','2026-01-28 11:02:58','2026-01-28 11:17:04'),(3,3,5,4,'in_progress','2026-01-28 11:44:12',NULL),(4,4,5,4,'rejected','2026-01-28 14:54:14','2026-01-29 07:13:01'),(5,5,5,4,'completed','2026-01-28 15:02:15','2026-01-28 15:56:51'),(6,6,5,3,'completed','2026-01-28 15:30:35','2026-01-28 15:33:13'),(7,7,5,3,'completed','2026-01-28 16:37:09','2026-01-28 16:46:59'),(8,8,5,3,'in_progress','2026-01-29 06:33:11',NULL),(9,9,5,3,'completed','2026-01-29 08:28:59','2026-01-29 08:40:38');
/*!40000 ALTER TABLE `workflow_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflow_step_instances`
--

DROP TABLE IF EXISTS `workflow_step_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow_step_instances` (
  `workflow_step_instance_id` int NOT NULL AUTO_INCREMENT,
  `workflow_instance_id` int NOT NULL,
  `workflow_step_id` int NOT NULL,
  `step_order` int NOT NULL,
  `assigned_role_id` int NOT NULL,
  `assigned_user_id` int DEFAULT NULL COMMENT 'Specific user if assigned',
  `status` enum('pending','approved','rejected','skipped','returned') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `action_taken_by_user_id` int DEFAULT NULL,
  `action_date` timestamp NULL DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `sla_due_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`workflow_step_instance_id`),
  KEY `idx_instance` (`workflow_instance_id`),
  KEY `idx_step` (`workflow_step_id`),
  KEY `idx_status` (`status`),
  KEY `idx_assigned_user` (`assigned_user_id`),
  KEY `assigned_role_id` (`assigned_role_id`),
  KEY `action_taken_by_user_id` (`action_taken_by_user_id`),
  CONSTRAINT `workflow_step_instances_ibfk_1` FOREIGN KEY (`workflow_instance_id`) REFERENCES `workflow_instances` (`workflow_instance_id`) ON DELETE CASCADE,
  CONSTRAINT `workflow_step_instances_ibfk_2` FOREIGN KEY (`workflow_step_id`) REFERENCES `workflow_steps` (`workflow_step_id`),
  CONSTRAINT `workflow_step_instances_ibfk_3` FOREIGN KEY (`assigned_role_id`) REFERENCES `roles` (`role_id`),
  CONSTRAINT `workflow_step_instances_ibfk_4` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `workflow_step_instances_ibfk_5` FOREIGN KEY (`action_taken_by_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflow_step_instances`
--

LOCK TABLES `workflow_step_instances` WRITE;
/*!40000 ALTER TABLE `workflow_step_instances` DISABLE KEYS */;
INSERT INTO `workflow_step_instances` VALUES (1,1,9,1,2,NULL,'rejected',3,'2026-01-28 12:00:19','',NULL,'2026-01-28 12:32:41'),(2,1,10,2,2,NULL,'pending',NULL,NULL,NULL,NULL,'2026-01-28 12:32:41'),(3,2,9,1,2,NULL,'approved',3,'2026-01-28 11:17:04','',NULL,'2026-01-28 13:02:58'),(4,3,9,1,2,NULL,'approved',3,'2026-01-28 12:00:08','',NULL,'2026-01-28 11:44:12'),(5,3,12,2,3,NULL,'approved',5,'2026-01-28 15:06:19','',NULL,'2026-01-28 11:44:12'),(6,3,13,3,4,NULL,'approved',1,'2026-01-28 15:31:36','',NULL,'2026-01-28 11:44:12'),(7,3,1,4,3,NULL,'approved',5,'2026-01-28 12:01:56','dfdsfd',NULL,'2026-01-28 11:44:12'),(8,3,2,5,4,NULL,'pending',NULL,NULL,NULL,NULL,'2026-01-28 11:44:12'),(9,3,3,6,5,NULL,'pending',NULL,NULL,NULL,NULL,'2026-01-28 11:44:12'),(10,4,9,1,2,NULL,'pending',NULL,NULL,NULL,NULL,'2026-01-28 14:54:14'),(11,4,12,2,3,NULL,'approved',5,'2026-01-28 15:06:40','',NULL,'2026-01-28 14:54:14'),(12,4,13,3,4,NULL,'approved',1,'2026-01-28 15:31:42','',NULL,'2026-01-28 14:54:14'),(13,4,1,4,3,NULL,'rejected',5,'2026-01-29 07:13:01','',NULL,'2026-01-28 14:54:14'),(14,5,9,1,2,NULL,'approved',3,'2026-01-28 15:12:51','hjgjhb',NULL,'2026-01-28 15:02:15'),(15,5,12,2,3,NULL,'approved',5,'2026-01-28 15:54:13','',NULL,'2026-01-28 15:02:15'),(16,5,13,3,4,NULL,'approved',1,'2026-01-28 15:56:31','',NULL,'2026-01-28 15:02:15'),(17,5,1,4,3,NULL,'approved',5,'2026-01-28 15:56:51','',NULL,'2026-01-28 15:02:15'),(18,6,9,1,2,NULL,'approved',3,'2026-01-28 15:32:24','',NULL,'2026-01-28 15:30:35'),(19,6,12,2,3,NULL,'approved',5,'2026-01-28 15:32:40','',NULL,'2026-01-28 15:30:35'),(20,6,13,3,4,NULL,'approved',1,'2026-01-28 15:33:13','',NULL,'2026-01-28 15:30:35'),(21,7,9,1,2,NULL,'approved',3,'2026-01-28 16:44:07','',NULL,'2026-01-28 16:37:09'),(22,7,12,2,3,NULL,'approved',5,'2026-01-28 16:44:49','',NULL,'2026-01-28 16:37:09'),(23,7,13,3,4,NULL,'approved',1,'2026-01-28 16:46:59','',NULL,'2026-01-28 16:37:09'),(24,8,9,1,2,NULL,'approved',3,'2026-01-29 06:47:33','asdsa ds ds',NULL,'2026-01-29 06:33:11'),(25,8,12,2,3,NULL,'approved',5,'2026-01-29 07:01:47','milk in limited quantity ',NULL,'2026-01-29 06:33:11'),(26,8,13,3,4,NULL,'pending',NULL,NULL,NULL,NULL,'2026-01-29 06:33:11'),(27,9,9,1,2,NULL,'approved',3,'2026-01-29 08:33:39','',NULL,'2026-01-29 08:28:59'),(28,9,12,2,3,NULL,'approved',5,'2026-01-29 08:36:33','',NULL,'2026-01-29 08:28:59'),(29,9,13,3,4,NULL,'approved',1,'2026-01-29 08:40:38','',NULL,'2026-01-29 08:28:59');
/*!40000 ALTER TABLE `workflow_step_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflow_steps`
--

DROP TABLE IF EXISTS `workflow_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow_steps` (
  `workflow_step_id` int NOT NULL AUTO_INCREMENT,
  `workflow_template_id` int NOT NULL,
  `step_order` int NOT NULL,
  `step_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver_role_id` int NOT NULL,
  `is_mandatory` tinyint(1) DEFAULT '1',
  `is_system_step` tinyint(1) DEFAULT '0' COMMENT 'TRUE for Admin Mgr, Gen Admin Mgr, Stores Officer',
  `can_be_removed` tinyint(1) DEFAULT '1' COMMENT 'FALSE for system steps',
  `condition_type` enum('none','amount','category','priority') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `condition_value` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON or simple value for conditions',
  `action_on_approval` enum('proceed','complete') COLLATE utf8mb4_unicode_ci DEFAULT 'proceed',
  `action_on_rejection` enum('end','return_to_requester') COLLATE utf8mb4_unicode_ci DEFAULT 'end',
  `sla_hours` int DEFAULT '24' COMMENT 'Service level agreement hours',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`workflow_step_id`),
  KEY `idx_template` (`workflow_template_id`),
  KEY `idx_order` (`workflow_template_id`,`step_order`),
  KEY `idx_role` (`approver_role_id`),
  CONSTRAINT `workflow_steps_ibfk_1` FOREIGN KEY (`workflow_template_id`) REFERENCES `workflow_templates` (`workflow_template_id`) ON DELETE CASCADE,
  CONSTRAINT `workflow_steps_ibfk_2` FOREIGN KEY (`approver_role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflow_steps`
--

LOCK TABLES `workflow_steps` WRITE;
/*!40000 ALTER TABLE `workflow_steps` DISABLE KEYS */;
INSERT INTO `workflow_steps` VALUES (1,1,1,'Administration Manager Approval',3,1,1,0,'none',NULL,'proceed','end',24,'2026-01-28 11:28:38','2026-01-28 15:15:25'),(2,1,2,'General Administration Manager Approval',4,1,1,0,'none',NULL,'proceed','end',24,'2026-01-28 11:28:38','2026-01-28 12:02:42'),(3,1,3,'Stores Officer - Release Instruction',5,1,1,0,'none',NULL,'complete','end',48,'2026-01-28 11:28:38','2026-01-28 12:02:46'),(4,2,1,'IT Supervisor Approval',2,1,0,1,'none',NULL,'proceed','return_to_requester',24,'2026-01-28 11:28:38',NULL),(5,2,2,'Operations Manager Review',2,0,0,1,'none',NULL,'proceed','return_to_requester',12,'2026-01-28 11:28:38',NULL),(6,4,1,'Administration Manager Approval',3,1,1,0,'none',NULL,'proceed','end',24,'2026-01-28 11:31:13',NULL),(7,4,2,'General Administration Manager Approval',4,1,1,0,'none',NULL,'proceed','end',24,'2026-01-28 11:31:13',NULL),(8,4,3,'Stores Officer - Release Instruction',5,1,1,0,'none',NULL,'complete','end',48,'2026-01-28 11:31:13',NULL),(9,5,1,'IT Supervisor Approval',2,1,0,1,'none',NULL,'proceed','return_to_requester',24,'2026-01-28 11:31:13',NULL),(10,5,2,'Operations Manager Review',2,0,0,1,'none',NULL,'proceed','return_to_requester',12,'2026-01-28 11:31:13','2026-01-28 10:50:00'),(11,6,1,'Operations Supervisor Approval',2,1,0,1,'none',NULL,'proceed','return_to_requester',24,'2026-01-28 11:31:13',NULL),(12,5,2,'Administration Manager',3,1,0,1,'none',NULL,'proceed','end',24,'2026-01-28 11:18:33',NULL),(13,5,3,'GM Admin',4,1,0,1,'none',NULL,'proceed','end',24,'2026-01-28 11:18:56',NULL);
/*!40000 ALTER TABLE `workflow_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflow_templates`
--

DROP TABLE IF EXISTS `workflow_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow_templates` (
  `workflow_template_id` int NOT NULL AUTO_INCREMENT,
  `template_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_type` enum('global','department') COLLATE utf8mb4_unicode_ci DEFAULT 'department',
  `department_id` int DEFAULT NULL COMMENT 'NULL for global templates',
  `request_type` enum('item','fuel','both') COLLATE utf8mb4_unicode_ci DEFAULT 'both',
  `is_active` tinyint(1) DEFAULT '1',
  `created_by_user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`workflow_template_id`),
  KEY `idx_department` (`department_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_type` (`template_type`),
  KEY `created_by_user_id` (`created_by_user_id`),
  CONSTRAINT `workflow_templates_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  CONSTRAINT `workflow_templates_ibfk_2` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflow_templates`
--

LOCK TABLES `workflow_templates` WRITE;
/*!40000 ALTER TABLE `workflow_templates` DISABLE KEYS */;
INSERT INTO `workflow_templates` VALUES (1,'Global System Workflow','global',NULL,'both',1,1,'2026-01-28 11:28:38','2026-01-28 11:28:38',NULL),(2,'IT Department Workflow','department',4,'both',1,4,'2026-01-28 11:28:38','2026-01-28 11:28:38',NULL),(4,'Global System Workflow','global',NULL,'both',1,1,'2026-01-28 11:31:13','2026-01-28 11:31:13',NULL),(5,'IT Department Workflow','department',3,'both',1,1,'2026-01-28 11:31:13','2026-01-28 11:31:13',NULL),(6,'Operations Department Workflow','department',4,'both',1,1,'2026-01-28 11:31:13','2026-01-28 11:31:13',NULL);
/*!40000 ALTER TABLE `workflow_templates` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-02 10:00:43
