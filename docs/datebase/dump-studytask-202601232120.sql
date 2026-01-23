-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: studytask.c182o44suwmb.eu-central-1.rds.amazonaws.com    Database: studytask
-- ------------------------------------------------------
-- Server version	8.0.43

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

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Max Mustermann','maxmustermann@gmail.com','Ich habe Probleme mich anzumelden!','2026-01-11 19:48:25'),(2,'Serdar Toluay','wi12b32@technikum-wien.at','Ich habe ein Gruppenproblem.','2026-01-15 11:02:47');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_members`
--

DROP TABLE IF EXISTS `group_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_members` (
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` enum('ADMIN','MEMBER') COLLATE utf8mb4_general_ci DEFAULT 'MEMBER',
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_members`
--

LOCK TABLES `group_members` WRITE;
/*!40000 ALTER TABLE `group_members` DISABLE KEYS */;
INSERT INTO `group_members` VALUES (2,3,'ADMIN','2026-01-07 19:22:37'),(4,4,'MEMBER','2026-01-08 09:04:46'),(4,6,'ADMIN','2026-01-08 09:04:12'),(4,9,'MEMBER','2026-01-08 08:54:54'),(5,10,'ADMIN','2026-01-08 09:49:55'),(6,10,'ADMIN','2026-01-08 09:50:28'),(8,11,'ADMIN','2026-01-10 17:23:36');
/*!40000 ALTER TABLE `group_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `join_code` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `join_code` (`join_code`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (2,'Testgruppe','Test','LVCFWUBS',3,'2026-01-07 19:22:37','2026-01-07 19:22:37'),(4,'Mathe','Wir machen Mathe gemeinsam!','V2DT5XGX',6,'2026-01-08 08:54:54','2026-01-08 09:51:34'),(5,'test5',NULL,'G5G4PUYN',10,'2026-01-08 09:49:55','2026-01-08 09:49:55'),(6,'test10',NULL,'KRG8KXF6',10,'2026-01-08 09:50:28','2026-01-08 09:50:28'),(8,'test','test','EEJ6WXYJ',11,'2026-01-10 17:23:36','2026-01-10 17:23:36');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_general_ci DEFAULT '#4f46e5',
  `group_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_group_tag` (`group_id`,`name`),
  CONSTRAINT `fk_tags_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'Uni','#4f46e5',2),(2,'Uni','#4f46e5',4),(3,'DRINGEND!','#4f46e5',4);
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_tags`
--

DROP TABLE IF EXISTS `task_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_tags` (
  `task_id` int NOT NULL,
  `tag_id` int NOT NULL,
  PRIMARY KEY (`task_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `task_tags_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_tags`
--

LOCK TABLES `task_tags` WRITE;
/*!40000 ALTER TABLE `task_tags` DISABLE KEYS */;
INSERT INTO `task_tags` VALUES (8,2),(7,3);
/*!40000 ALTER TABLE `task_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `status` enum('TO_DO','IN_PROGRESS','DONE') COLLATE utf8mb4_general_ci DEFAULT 'TO_DO',
  `due_at` datetime DEFAULT NULL,
  `created_by` int NOT NULL,
  `responsible_user_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (4,2,'test','','IN_PROGRESS','2026-01-29 20:25:00',3,NULL,'2026-01-07 19:25:27','2026-01-07 19:54:23'),(7,4,'Gleichungen Aufgabe','','DONE','2026-01-12 23:59:00',9,6,'2026-01-08 08:56:12','2026-01-10 16:33:49'),(8,4,'Programmieren','','DONE','2026-01-14 12:59:00',9,9,'2026-01-08 08:56:48','2026-01-11 20:11:38'),(9,4,'Englisch','','DONE','2026-01-15 20:59:00',9,4,'2026-01-08 08:57:25','2026-01-10 16:33:55');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrikelnummer` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'USER',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'9895640685','wi23b181@technikum-wien.at','$2y$10$GuiWc.mKlVILzBLS/WbsT.svwUkhek36Hy51eHXT7yYBw16zLT6FO','Ersan Kavak','2026-01-02 17:36:25','2026-01-02 17:36:25','USER'),(3,'123456789','test@gmail.com','$2y$10$tsiBi1i6pzse8vGMQKP7/upQIrnxwdfphY53iRWvTWGpixq0OONSe','Test','2026-01-03 19:04:49','2026-01-03 19:04:49','USER'),(4,'45354135','bla@gmail.com','$2y$10$oVk.Rzs0OdfwpgYPsBdv1OAogldcC92a7lqw/zbvnOrthnKo9gNx2','bla','2026-01-06 18:32:25','2026-01-06 18:32:25','USER'),(5,'1212121','wi23b021@gmail.com','$2y$10$9AFUad4ZXYMOYJ.e1jBaj.D1icpInGKplzqshhXJxQtnLNcHsYrqi','serdar toluay','2026-01-06 18:39:38','2026-01-06 18:39:38','USER'),(6,'1111111','max@gmail.com','$2y$10$NW4DFfrX5TPlQ32qj6KCderAOc0SyvTSIsuEuNKFhijnGlH7KKxjq','max','2026-01-07 10:57:23','2026-01-07 10:57:23','USER'),(7,'165436','mehmet@gmail.com','$2y$10$D8cbnqjPeG9jQCvuujgwfu04LjTQxlw1ZxANTOpwOh1n5xmdwPdk.','Mehmet','2026-01-07 17:49:10','2026-01-07 17:49:10','USER'),(8,'123123123','test2@gmail.com','$2y$10$4GIW6QU5bRzeFnj6KXARKuU3cZVOCWuFbj3.Djidf0ChCXuXayC.K','Test2','2026-01-07 19:57:51','2026-01-07 19:57:51','USER'),(9,'2358463513','wi12b32@technikum-wien.at','$2y$10$w.tx2oMbYmVPyCQ/Tn4hpOvVr6gYP3.ZmINhvwzqBT1.rF3pdgLyG','Serdar Toluay','2026-01-08 08:44:35','2026-01-15 11:35:59','USER'),(10,'6546846513','test5@gmail.com','$2y$10$nit022bXln7lKxbKgWb7Y.eYlMPHFnnBdf4gO1S1LFMXyDjRA4kMa','test5','2026-01-08 09:49:10','2026-01-08 09:49:10','USER'),(11,'565135465','wi11b111@gmail.com','$2y$10$iKBwH.S3lMAVKr26y21sf.zre/hcR1mWGPl3dI9NSOYUwPmZVhyVG','Sebastian Koger','2026-01-10 17:15:02','2026-01-10 17:15:02','USER'),(12,NULL,'admin@gmail.com','$2y$10$MT5IgfoRm.qtIh8QPmPzFehg2Umo2ePj2aIWbaxBOshKvwB8hQGJS','Admin FH','2026-01-10 17:45:39','2026-01-10 17:51:39','ADMIN'),(13,'654351354','wi77b077@technikum-wien.at','$2y$10$cVZL41m/VKahb/tjcqfs2OgnTBYDfqZzrZ8ojs/SYY6ASQltTbD8e','Fred Klaus','2026-01-15 11:03:33','2026-01-15 11:03:33','USER');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'studytask'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-23 21:20:07
