-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: logbook
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.18.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_active` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','admin@admin.com','$2y$10$OT/xW3xr4lcJK/d/RHqlHOJDccoqhXB7T4ejhVKRTM5AJKG6a2ZuG',1,'2019-05-18 20:33:20','2019-05-18 20:33:20'),(2,'raindolf','raindolf@amazingtechnologies.com.gh','$2y$10$nAzgKpOJ7KJW0pjD0x2ZpeffBcak70/tmP0XUxizvt.xZc6J9onjC',1,'2019-05-21 12:06:12','2019-05-21 12:06:12'),(3,'Kwaku','kwaku@amazingtechnologies.com.gh','$2y$10$MSMYjHbkjgMqWFq5.AKwnOFKb1GTOsXNDWxVu154ZqjYebZ0.iRR.',1,'2019-05-21 12:07:12','2019-05-21 12:07:12'),(4,'test','test@gmail.com','$2y$10$gsTyKWKOmUBLfw8RvB6rhO05bN5G4m6kYzbFlkc6apdRh/4stIGuS',1,'2019-05-21 21:02:41','2019-05-21 21:02:41');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'n/a',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'n/a',
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'n/a',
  `active` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'First Company','evault17@gmail.com','n/a','n/a','$2y$10$yslYAhFTzPzXyJafln67AeFUmam5UNWZqff8oQyKQNIuZdlkTiRO.','https://res.cloudinary.com/amazing-technologies/image/upload/v1558108372/logbook/companies/1/logos/company_logo_1.png','c6dbb6584112f230e53e2','yes','2019-05-18 20:38:09','2019-05-22 11:41:31'),(2,'La Palm','enoch.sowah16@gmail.com','n/a','n/a','n/a','https://res.cloudinary.com/amazing-technologies/image/upload/v1557224965/logbook/logos/logo.png','n/a','no','2019-05-21 05:26:02','2019-05-21 05:26:02'),(3,'Amazing Tech','amazingtech233@gmail.com','n/a','n/a','$2y$10$VoEv3eYzrsHnTwObQHHlluM7pFheArQsHOflQNU.EHIyF1X2teM.e','https://res.cloudinary.com/amazing-technologies/image/upload/v1557224965/logbook/logos/logo.png','582045f560370e0395f74','yes','2019-05-30 12:56:30','2019-05-30 12:58:06');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_activation_codes`
--

DROP TABLE IF EXISTS `company_activation_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_activation_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `activation_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_activation_codes`
--

LOCK TABLES `company_activation_codes` WRITE;
/*!40000 ALTER TABLE `company_activation_codes` DISABLE KEYS */;
INSERT INTO `company_activation_codes` VALUES (1,1,'AMZ-LB-0C13FF13B90B4FFE5D81','yes','2019-05-18 20:38:09','2019-05-18 20:38:41'),(2,2,'AMZ-LB-C7468335D4297806987B','no','2019-05-21 05:26:02','2019-05-21 05:26:02'),(3,3,'AMZ-LB-7C2DDA3EE33BC7414A9F','yes','2019-05-30 12:56:30','2019-05-30 12:58:06');
/*!40000 ALTER TABLE `company_activation_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,1,'Information Technology','2019-05-18 20:39:58','2019-05-18 20:39:58'),(2,1,'Human Resource','2019-05-18 20:41:47','2019-05-18 20:41:47'),(3,1,'Security','2019-05-18 20:42:02','2019-05-18 20:42:02'),(4,1,'Welfare','2019-05-18 20:42:09','2019-05-18 20:42:09'),(5,1,'Commercial','2019-05-18 20:42:22','2019-05-18 20:42:22'),(6,1,'Accounts','2019-05-18 20:42:28','2019-05-18 20:42:28'),(7,1,'Public Relations','2019-05-18 20:42:38','2019-05-18 20:42:38'),(8,1,'Legal','2019-05-21 05:11:16','2019-05-21 05:11:16'),(9,3,'Management','2019-05-30 12:59:56','2019-05-30 12:59:56'),(10,3,'Marketing','2019-05-30 16:04:56','2019-05-30 16:04:56'),(11,3,'Information Tech','2019-05-30 16:05:11','2019-05-30 16:05:11');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_logs`
--

DROP TABLE IF EXISTS `employee_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `time_in` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_out` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_logs`
--

LOCK TABLES `employee_logs` WRITE;
/*!40000 ALTER TABLE `employee_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `type` enum('full_time','part_time','contract') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_time',
  `position` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'n/a',
  `active` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Samson','Dood','82728288','evault17@gmail.com','2019-04-29','+233507032481',1,2,'full_time','Manager','https://res.cloudinary.com/amazing-technologies/image/upload/v1558285712/logbook/companies/1/employees/employee_profile_thumbnail_1558285748.webp','254522','yes','2019-05-19 17:09:23','2019-05-19 17:09:23'),(2,'Kwaku','Adomah','N/A','kwaku@amazingtechnologies.com.gh','2009-05-05','+233209180493',3,9,'full_time','Manager','https://res.cloudinary.com/amazing-technologies/image/upload/v1557928971/logbook/default_images/profile-picture.png','458730','yes','2019-05-30 13:01:37','2019-05-30 16:05:57'),(3,'Mike','Nartey','AMZ2244','raindolf@bisaapp.com','2019-04-29','+233244636555',3,9,'full_time','CEO','https://res.cloudinary.com/amazing-technologies/image/upload/v1559232443/logbook/companies/3/employees/employee_profile_thumbnail_1559232443.png','686208','yes','2019-05-30 16:07:26','2019-05-30 16:07:26');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host_company` int(11) NOT NULL,
  `host_department` int(11) NOT NULL,
  `host` int(11) NOT NULL,
  `visit_reason` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'n/a',
  `logged_out` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `time_in` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_out` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guests`
--

LOCK TABLES `guests` WRITE;
/*!40000 ALTER TABLE `guests` DISABLE KEYS */;
INSERT INTO `guests` VALUES (1,'John Doe','+233507032481','Origin Company',1,3,1,'Official Visit','https://res.cloudinary.com/amazing-technologies/image/upload/v1558407471/logbook/companies/1/guest_photos/guest_thumbnail_15584.jpg','9936','no','2019-05-21 02:59:26','1000-01-01 00:00:00'),(2,'John Doe','+233507032481','Origin Company',1,3,1,'Official Visit','https://res.cloudinary.com/amazing-technologies/image/upload/v1558407471/logbook/companies/1/guest_photos/guest_thumbnail_15584.jpg','3757','yes','2019-05-21 03:00:00','2019-05-21 03:35:11'),(3,'John Doe','+233507032481','Origin Company',1,3,1,'Official Visit','https://res.cloudinary.com/amazing-technologies/image/upload/v1558407471/logbook/companies/1/guest_photos/guest_thumbnail_15584.jpg','3454','yes','2019-05-21 05:37:45','2019-05-21 05:39:02'),(4,'John Doe','+233507032481','Origin Company',1,3,1,'Official Visit','https://res.cloudinary.com/amazing-technologies/image/upload/v1558407471/logbook/companies/1/guest_photos/guest_thumbnail_15584.jpg','9795','no','2019-05-21 22:20:50','1000-01-01 00:00:00'),(5,'John Doe','+233507032481','Origin Company',1,3,1,'Official Visit','https://res.cloudinary.com/amazing-technologies/image/upload/v1558407471/logbook/companies/1/guest_photos/guest_thumbnail_15584.jpg','3996','no','2019-05-21 22:21:25','1000-01-01 00:00:00'),(6,'John Doe','+233507032481','Origin Company',1,3,1,'Official Visit','https://res.cloudinary.com/amazing-technologies/image/upload/v1558407471/logbook/companies/1/guest_photos/guest_thumbnail_15584.jpg','0433','no','2019-05-21 22:22:07','1000-01-01 00:00:00'),(7,'John Doe','+233507032481','Origin Company',1,3,1,'Official Visit','https://res.cloudinary.com/amazing-technologies/image/upload/v1558520444/logbook/companies/1/guest_photos/guest_thumbnail_15585.jpg','0342','no','2019-05-23 04:07:52','1000-01-01 00:00:00'),(8,'Hvhbm','++233247866190','Hjk',1,3,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1558585026/logbook/companies/1/guest_photos/guest_thumbnail_15585.jpg','7237','no','2019-05-23 04:17:06','1000-01-01 00:00:00'),(9,'Gjj','++233247866190','Bnj',1,3,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1558585084/logbook/companies/1/guest_photos/guest_thumbnail_15585.jpg','5689','no','2019-05-23 04:18:04','1000-01-01 00:00:00'),(10,'Nbjbj','++233247866190','Nvnvn',1,3,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1558585198/logbook/companies/1/guest_photos/guest_thumbnail_15585.jpg','9759','no','2019-05-23 04:19:59','1000-01-01 00:00:00'),(11,'Nmkk','+233247866190','Bmk',1,3,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1558585388/logbook/companies/1/guest_photos/guest_thumbnail_15585.jpg','3970','no','2019-05-23 04:23:09','1000-01-01 00:00:00'),(12,'Nana Kwame','+233247866190','Nestle Ghana',1,3,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559013866/logbook/companies/1/guest_photos/guest_thumbnail_15590.jpg','7714','no','2019-05-28 03:24:26','1000-01-01 00:00:00'),(13,'Hj','+233244866190','Nestle Ghana',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559206190/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','0972','no','2019-05-30 08:49:51','1000-01-01 00:00:00'),(14,'Jjio','+233589566523','Nestle Ghana',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559206972/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','2439','no','2019-05-30 09:02:52','1000-01-01 00:00:00'),(15,'Nag','+233247866190','Bnk',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559208001/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','1063','no','2019-05-30 09:20:01','1000-01-01 00:00:00'),(16,'Bjjj','+233247856565','Bhj',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559208224/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','8324','no','2019-05-30 09:23:44','1000-01-01 00:00:00'),(17,'Hnj','+233245866586','Ghj',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559208373/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','4906','no','2019-05-30 09:26:13','1000-01-01 00:00:00'),(18,'Bhj','+233244886585','Hb',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559208598/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','1640','no','2019-05-30 09:29:58','1000-01-01 00:00:00'),(19,'Jjj','+233247866190','Bjj',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559213175/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','8879','no','2019-05-30 10:46:16','1000-01-01 00:00:00'),(20,'Kay','+233552216653','Nestle Ghana',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559217271/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','6114','no','2019-05-30 11:54:32','1000-01-01 00:00:00'),(21,'Jon','+233507032481','UG',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559227142/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','6396','no','2019-05-30 14:39:02','1000-01-01 00:00:00'),(22,'Jhbhjbkjn','+233245888556','Jnj',1,1,1,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559229815/logbook/companies/1/guest_photos/guest_thumbnail_15592.jpg','2899','no','2019-05-30 15:23:36','1000-01-01 00:00:00'),(23,'Nana','+233247866190','Nestle Ghana',3,1,2,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559232771/logbook/companies/3/guest_photos/guest_thumbnail_15592.jpg','2570','no','2019-05-30 16:12:51','1000-01-01 00:00:00'),(24,'Nana','+233247866190','Nestle Ghana',3,1,2,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559232771/logbook/companies/3/guest_photos/guest_thumbnail_15592.jpg','7595','no','2019-05-30 16:12:55','1000-01-01 00:00:00'),(25,'Thin','+233247866190','Nestle Ghana',3,1,2,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559233454/logbook/companies/3/guest_photos/guest_thumbnail_15592.jpg','3838','no','2019-05-30 16:24:14','1000-01-01 00:00:00'),(26,'Louis','+233262509309','Shs',3,1,2,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559588264/logbook/companies/3/guest_photos/guest_thumbnail_15595.jpg','3660','no','2019-06-03 18:57:44','1000-01-01 00:00:00'),(27,'Louis','+233262509309','Shs',3,1,2,'official','https://res.cloudinary.com/amazing-technologies/image/upload/v1559588264/logbook/companies/3/guest_photos/guest_thumbnail_15595.jpg','0165','no','2019-06-03 18:57:46','1000-01-01 00:00:00');
/*!40000 ALTER TABLE `guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_05_06_131717_create_admins_table',1),(4,'2019_05_07_110629_create_companies_table',1),(5,'2019_05_07_111120_create_company_activation_codes_table',1),(6,'2019_05_10_121601_create_departments_table',1),(7,'2019_05_15_143007_create_employees_table',1),(11,'2019_05_18_155027_create_guests_table',2),(12,'2019_05_21_175810_create_employee_logs_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-10 10:04:06
