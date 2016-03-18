DROP DATABASE IF EXISTS `unimail`;

CREATE DATABASE  IF NOT EXISTS `unimail` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `unimail`;

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
-- Table structure for table `admin` (admin of unimail system)
--

DROP TABLE IF EXISTS `nm_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nm_admin` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL UNIQUE,
  `password` varchar(45) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,  
  `cellphone` varchar(45) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `nm_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nm_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL UNIQUE,
  `dirpath` varchar(45) DEFAULT NULL UNIQUE,
  `nif` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `postcode` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `phone1` varchar(40) DEFAULT NULL,
  `phone2` varchar(40) DEFAULT NULL,
  `email1` varchar(45) DEFAULT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  `mustCert` tinyint(1) DEFAULT 1,
  `active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `nm_cmailcfg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nm_cmailcfg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyID` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL UNIQUE,
  `comment` varchar(100) DEFAULT NULL,
  `host` varchar(45) DEFAULT NULL,
  `port` int(6) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `SMTPsec` varchar(4) DEFAULT NULL,
  `SMTPauth` tinyint(1) DEFAULT 1,
  `MailFrom` varchar(45) DEFAULT NULL,
  `MailFromName` varchar(45) DEFAULT NULL,
  `MailReplyTo` varchar(45) DEFAULT NULL,
  `MailReplyToName` varchar(45) DEFAULT NULL,
  `WordWrap` int(3) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`companyID`) 
    REFERENCES nm_company (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Engine`
--
DROP TABLE IF EXISTS `nm_engine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nm_engine` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyID` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL UNIQUE,
  `path` text DEFAULT NULL,
  `fields` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`companyID`) 
    REFERENCES nm_company (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `nm_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nm_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyID` int(10) unsigned NOT NULL,
  `username` varchar(45) DEFAULT NULL UNIQUE,
  `password` varchar(45) DEFAULT NULL,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,  
  `cellphone` varchar(45) DEFAULT NULL,
  `isCompAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`companyID`) 
    REFERENCES nm_company (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `nm_msgtemplate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
-- bfrHDR == insert URL before body header
-- bfrMDL == insert URL before body middle
-- bfrFTR == insert URL before body footer
-- bfrSGNT == insert URL before signature
CREATE TABLE `nm_msgtemplate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyID` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL UNIQUE,
  `subjtag` varchar(20) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `greeting` varchar(45) DEFAULT NULL,
  `bodyhdr` text DEFAULT NULL,
  `body` text DEFAULT NULL,
  `bodyftr` text DEFAULT NULL,
  `signature` text DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  `bfrHDR` tinyint(1) NOT NULL DEFAULT '0',
  `bfrMDL` tinyint(1) NOT NULL DEFAULT '0',
  `bfrFTR` tinyint(1) NOT NULL DEFAULT '0',
  `bfrSGNT` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`companyID`) 
    REFERENCES nm_company (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for system stats
--   rident = receipt ident
--   raction = receipt action
--   gpdf = PDF generated (TRUE/FALSE)
--   Note for mysql: this MySQL version can not handle microseconds,
--   that's why we can not define:
--           `on_date` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
--   so we use 'usec' to store microsecond part
DROP TABLE IF EXISTS `nm_stats`;
CREATE TABLE `nm_stats` (
  `id` bigint(41) unsigned NOT NULL AUTO_INCREMENT,
  `companyID` int(10) unsigned NOT NULL,
  `rident` varchar(45) NOT NULL,
  `raction` varchar(20) DEFAULT '',
  `path` text NOT NULL,
  `hash` text ,
  `gpdf` tinyint(1) DEFAULT 0,
  `sent` tinyint(1) DEFAULT 0,
  `error` text ,
  `on_date` datetime DEFAULT '0000-00-00 00:00:00',
  `usec` varchar(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`companyID`) 
    REFERENCES nm_company (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `Hash type`
--

DROP TABLE IF EXISTS `nm_hashtype`;
CREATE TABLE `nm_hashtype` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Populate table

INSERT INTO nm_hashtype VALUES (1, 'SHA 1', 'sha1');
INSERT INTO nm_hashtype VALUES (2, 'SHA 256', 'sha256');

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `nm_logs`;
CREATE TABLE `nm_logs` (
  `id` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `body` varchar(100) NOT NULL,
  `logdate` datetime NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `nm_logs_timestamp`;
CREATE TRIGGER `nm_logs_timestamp` BEFORE INSERT ON `nm_logs`
  FOR EACH ROW SET NEW.`logdate` = NOW();

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `nm_ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nm_ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `nm_ci_sessions` WRITE;
/*!40000 ALTER TABLE `nm_ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `nm_ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

