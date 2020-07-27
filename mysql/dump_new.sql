-- MySQL dump 10.13  Distrib 5.7.30, for Linux (x86_64)
--
-- Host: localhost    Database: ci_test
-- ------------------------------------------------------
-- Server version	5.7.30-0ubuntu0.18.04.1

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
-- Table structure for table `boosterpack`
--

DROP TABLE IF EXISTS `boosterpack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boosterpack` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boosterpack`
--

LOCK TABLES `boosterpack` WRITE;
/*!40000 ALTER TABLE `boosterpack` DISABLE KEYS */;
INSERT INTO `boosterpack` VALUES (1,5.00,0.00,'2020-03-30 00:17:28','2020-07-26 14:19:15'),(2,20.00,0.00,'2020-03-30 00:17:28','2020-07-25 12:35:39'),(3,50.00,0.00,'2020-03-30 00:17:28','2020-07-25 12:35:39');
/*!40000 ALTER TABLE `boosterpack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `assign_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  KEY `assign_id` (`assign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,0,1,1,'Ну чо ассигн проверим',8,'2020-03-27 21:39:44','2020-07-26 13:40:33'),(2,0,1,1,'Второй коммент',0,'2020-03-27 21:39:55','2020-07-25 12:35:39'),(3,0,2,1,'Второй коммент от второго человека',0,'2020-03-27 21:40:22','2020-07-25 12:35:39'),(4,0,1,1,'test_comment',0,'2020-07-25 14:53:17','2020-07-25 14:58:38'),(5,1,1,1,'test_comment',0,'2020-07-25 15:00:03','2020-07-25 15:00:03'),(6,5,1,1,'test_comment',0,'2020-07-25 15:01:09','2020-07-25 15:01:09'),(7,6,1,1,'test_comment',0,'2020-07-25 15:01:11','2020-07-25 15:01:11'),(8,7,1,1,'test_comment',0,'2020-07-25 15:01:13','2020-07-25 15:01:13'),(9,1,1,1,'test_comment',0,'2020-07-25 15:01:51','2020-07-25 15:01:51'),(10,1,1,1,'test_comment',0,'2020-07-25 15:01:53','2020-07-25 15:01:53'),(11,9,1,1,'test_comment',0,'2020-07-25 15:01:58','2020-07-25 15:01:58');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_add_money`
--

DROP TABLE IF EXISTS `log_add_money`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_add_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_add_money`
--

LOCK TABLES `log_add_money` WRITE;
/*!40000 ALTER TABLE `log_add_money` DISABLE KEYS */;
INSERT INTO `log_add_money` VALUES (1,1,10.00,'2020-07-26 13:48:49');
/*!40000 ALTER TABLE `log_add_money` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_buy_boosterpack`
--

DROP TABLE IF EXISTS `log_buy_boosterpack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_buy_boosterpack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `boosterpack_id` int(10) NOT NULL,
  `boosterpack_price` decimal(10,2) NOT NULL,
  `boosterpack_bank` decimal(10,2) NOT NULL,
  `win_likes` int(10) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_buy_boosterpack`
--

LOCK TABLES `log_buy_boosterpack` WRITE;
/*!40000 ALTER TABLE `log_buy_boosterpack` DISABLE KEYS */;
INSERT INTO `log_buy_boosterpack` VALUES (1,1,1,5.00,0.00,0,'2020-07-26 14:14:24'),(2,1,1,5.00,2.00,0,'2020-07-26 14:14:37'),(3,1,1,5.00,6.00,0,'2020-07-26 14:14:37'),(4,1,1,5.00,4.00,0,'2020-07-26 14:14:38'),(5,1,1,5.00,6.00,9,'2020-07-26 14:16:20'),(6,1,1,5.00,0.00,2,'2020-07-26 14:17:05'),(7,1,1,5.00,2.00,5,'2020-07-26 14:18:13'),(8,1,1,5.00,2.00,7,'2020-07-26 14:18:43'),(9,1,1,5.00,0.00,3,'2020-07-26 14:18:54'),(10,1,1,5.00,2.00,7,'2020-07-26 14:19:00'),(11,1,1,5.00,0.00,1,'2020-07-26 14:19:09'),(12,1,1,5.00,4.00,6,'2020-07-26 14:19:10'),(13,1,1,5.00,3.00,8,'2020-07-26 14:19:15');
/*!40000 ALTER TABLE `log_buy_boosterpack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_likes`
--

DROP TABLE IF EXISTS `log_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `entity` enum('post','comment') NOT NULL,
  `entity_id` int(10) unsigned NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_likes`
--

LOCK TABLES `log_likes` WRITE;
/*!40000 ALTER TABLE `log_likes` DISABLE KEYS */;
INSERT INTO `log_likes` VALUES (1,1,'post',1,'2020-07-26 13:45:44'),(2,1,'post',1,'2020-07-26 13:45:44'),(3,1,'comment',1,'2020-07-26 13:45:44'),(4,1,'comment',1,'2020-07-26 13:45:44'),(5,1,'comment',1,'2020-07-26 13:45:44'),(6,1,'comment',1,'2020-07-26 13:45:44'),(7,1,'post',1,'2020-07-26 13:45:44'),(8,1,'post',1,'2020-07-26 13:45:44'),(9,1,'comment',1,'2020-07-26 13:45:44'),(10,1,'comment',1,'2020-07-26 13:45:44');
/*!40000 ALTER TABLE `log_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(10) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `img` varchar(1024) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,1,'Тестовый постик 1',5,'/images/posts/1.png','2018-08-30 13:31:14','2020-07-26 13:01:34'),(2,1,'Печальный пост',0,'/images/posts/2.png','2018-10-11 01:33:27','2020-07-25 12:35:39');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `personaname` varchar(50) NOT NULL DEFAULT '',
  `avatarfull` varchar(150) NOT NULL DEFAULT '',
  `rights` int(11) NOT NULL DEFAULT '0',
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_refilled` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` datetime NOT NULL,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `time_created` (`time_created`),
  KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin@niceadminmail.pl','1234','AdminProGod','https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/96/967871835afdb29f131325125d4395d55386c07a_full.jpg',159,0.00,70.00,70.00,'2019-07-26 01:53:54','2020-07-26 14:19:15'),(2,'simpleuser@niceadminmail.pl','1234','simpleuser','https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/86/86a0c845038332896455a566a1f805660a13609b_full.jpg',0,0.00,0.00,0.00,'2019-07-26 01:53:54','2020-07-25 13:47:22');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-07-27  9:31:12
