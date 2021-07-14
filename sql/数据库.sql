-- MySQL dump 10.13  Distrib 8.0.24, for Linux (x86_64)
--
-- Host: localhost    Database: ce_onlyman_cn
-- ------------------------------------------------------
-- Server version	8.0.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `applylog`
--

DROP TABLE IF EXISTS `applylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applylog` (
  `aId` int NOT NULL AUTO_INCREMENT,
  `userId` int DEFAULT NULL,
  `startTime` int DEFAULT NULL,
  `endTime` int DEFAULT NULL,
  `useTime` int DEFAULT NULL,
  `cId` int NOT NULL,
  `aTime` int DEFAULT NULL,
  `cTitle` varchar(200) NOT NULL,
  `cNames` varchar(200) NOT NULL COMMENT '参会人员',
  PRIMARY KEY (`aId`),
  KEY `FK_staffUserDepartmentLog` (`userId`),
  KEY `FK_userDepartment` (`cId`),
  CONSTRAINT `FK_staffUserDepartmentLog` FOREIGN KEY (`userId`) REFERENCES `staff` (`uId`),
  CONSTRAINT `FK_userDepartment` FOREIGN KEY (`cId`) REFERENCES `conferenceroom` (`cId`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applylog`
--

LOCK TABLES `applylog` WRITE;
/*!40000 ALTER TABLE `applylog` DISABLE KEYS */;
/*!40000 ALTER TABLE `applylog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conferenceroom`
--

DROP TABLE IF EXISTS `conferenceroom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conferenceroom` (
  `cId` int NOT NULL AUTO_INCREMENT,
  `cName` char(30) DEFAULT NULL,
  PRIMARY KEY (`cId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conferenceroom`
--

LOCK TABLES `conferenceroom` WRITE;
/*!40000 ALTER TABLE `conferenceroom` DISABLE KEYS */;
INSERT INTO `conferenceroom` VALUES (1,'大会议室'),(2,'大会议室2'),(3,'小会议室1'),(4,'小会议室2');
/*!40000 ALTER TABLE `conferenceroom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department` (
  `dId` int NOT NULL AUTO_INCREMENT,
  `dName` char(50) DEFAULT NULL,
  `dIntroduce` char(200) DEFAULT NULL,
  PRIMARY KEY (`dId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (2,'市场部','市场部'),(3,'开发部','开发部'),(4,'测试部','测试部'),(5,'售后部','售后部'),(6,'管理部','管理部');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `uId` int NOT NULL AUTO_INCREMENT,
  `userName` char(30) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `userPic` varchar(50) NOT NULL,
  `staffName` char(20) DEFAULT NULL,
  `staffAge` int DEFAULT NULL,
  `email` char(30) DEFAULT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `dId` int DEFAULT NULL,
  `admin` int NOT NULL DEFAULT '0',
  `applyTime` int NOT NULL,
  `isAdopt` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`uId`),
  KEY `员工部门` (`dId`),
  CONSTRAINT `员工部门` FOREIGN KEY (`dId`) REFERENCES `department` (`dId`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'admin','baf0f0afd319bf489c5fe7d5a44b7a52','1546621596_833053_5c2f929ccad7b.png','管理员',18,'xx@onlyman.cn','xxxx',2,1,0,1),(57,'23411','abdc2472ee4aaa9528557f6e913415e0','','23411',0,'23411@qq.com','23411',2,0,1592875928,0);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'ce_onlyman_cn'
--

--
-- Dumping routines for database 'ce_onlyman_cn'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-14 11:13:03
