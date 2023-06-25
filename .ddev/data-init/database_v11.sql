
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `backend_layout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend_layout` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `config` text NOT NULL,
  `icon` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `be_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `non_exclude_fields` text DEFAULT NULL,
  `explicit_allowdeny` text DEFAULT NULL,
  `allowed_languages` varchar(255) NOT NULL DEFAULT '',
  `custom_options` text DEFAULT NULL,
  `db_mountpoints` text DEFAULT NULL,
  `pagetypes_select` text DEFAULT NULL,
  `tables_select` text DEFAULT NULL,
  `tables_modify` text DEFAULT NULL,
  `groupMods` text DEFAULT NULL,
  `availableWidgets` text DEFAULT NULL,
  `mfa_providers` text DEFAULT NULL,
  `file_mountpoints` text DEFAULT NULL,
  `file_permissions` text DEFAULT NULL,
  `TSconfig` text DEFAULT NULL,
  `subgroup` text DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `category_perms` longtext DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `be_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_sessions` (
  `ses_id` varchar(190) NOT NULL DEFAULT '',
  `ses_iplock` varchar(39) NOT NULL DEFAULT '',
  `ses_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_data` longblob DEFAULT NULL,
  PRIMARY KEY (`ses_id`),
  KEY `ses_tstamp` (`ses_tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `be_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `avatar` int(10) unsigned NOT NULL DEFAULT 0,
  `password` varchar(100) NOT NULL DEFAULT '',
  `admin` smallint(5) unsigned NOT NULL DEFAULT 0,
  `usergroup` text DEFAULT NULL,
  `lang` varchar(10) NOT NULL DEFAULT 'default',
  `email` varchar(255) NOT NULL DEFAULT '',
  `db_mountpoints` text DEFAULT NULL,
  `options` smallint(5) unsigned NOT NULL DEFAULT 0,
  `realName` varchar(80) NOT NULL DEFAULT '',
  `userMods` text DEFAULT NULL,
  `allowed_languages` varchar(255) NOT NULL DEFAULT '',
  `uc` mediumblob DEFAULT NULL,
  `file_mountpoints` text DEFAULT NULL,
  `file_permissions` text DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `TSconfig` text DEFAULT NULL,
  `lastlogin` int(10) unsigned NOT NULL DEFAULT 0,
  `workspace_id` int(11) NOT NULL DEFAULT 0,
  `mfa` mediumblob DEFAULT NULL,
  `category_perms` longtext DEFAULT NULL,
  `password_reset_token` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `parent` (`pid`,`deleted`,`disable`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `be_users` VALUES (1,0,1633799703,1633799703,0,0,0,0,0,NULL,'admin',0,'$argon2i$v=19$m=65536,t=16,p=1$dVFkenVyM2FvOTE4Snk5UA$gIX6gwNwdlw4RivK/UHNSt4noZvsnw4bxTWN4HsckHw',1,NULL,'default','',NULL,0,'',NULL,'','a:14:{s:14:\"interfaceSetup\";s:0:\"\";s:10:\"moduleData\";a:9:{s:8:\"web_list\";a:4:{s:8:\"function\";N;s:8:\"language\";N;s:19:\"constant_editor_cat\";N;s:9:\"clipBoard\";s:1:\"0\";}s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";a:0:{}s:9:\"clipboard\";a:5:{s:6:\"normal\";a:2:{s:2:\"el\";a:0:{}s:4:\"mode\";s:0:\"\";}s:5:\"tab_1\";a:0:{}s:5:\"tab_2\";a:0:{}s:5:\"tab_3\";a:0:{}s:7:\"current\";s:6:\"normal\";}s:10:\"FormEngine\";a:2:{i:0;a:4:{s:32:\"581106f297d9eed8dec1190ee4d6b04d\";a:4:{i:0;s:13:\"Mountains GIF\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:3;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B3%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:3;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"c312013d83c1a6ad7fec8b36a37ba3c8\";a:4:{i:0;s:13:\"Mountains PNG\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:1;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"696addfecc296b326ff6e9f04c7ff3e1\";a:4:{i:0;s:4:\"Home\";i:1;a:5:{s:4:\"edit\";a:1:{s:5:\"pages\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:28:\"&edit%5Bpages%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:5:\"pages\";s:3:\"uid\";i:1;s:3:\"pid\";i:0;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"86205c5935270b8ee413592ec1b62292\";a:4:{i:0;s:25:\"Main TypoScript Rendering\";i:1;a:5:{s:4:\"edit\";a:1:{s:12:\"sys_template\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:35:\"&edit%5Bsys_template%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:12:\"sys_template\";s:3:\"uid\";i:1;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}}i:1;s:32:\"86205c5935270b8ee413592ec1b62292\";}s:16:\"browse_links.php\";N;s:9:\"file_list\";a:3:{s:8:\"function\";N;s:8:\"language\";N;s:19:\"constant_editor_cat\";N;}s:10:\"web_layout\";a:3:{s:8:\"function\";s:1:\"1\";s:8:\"language\";s:1:\"0\";s:19:\"constant_editor_cat\";N;}s:16:\"opendocs::recent\";a:3:{s:32:\"86205c5935270b8ee413592ec1b62292\";a:4:{i:0;s:25:\"Main TypoScript Rendering\";i:1;a:5:{s:4:\"edit\";a:1:{s:12:\"sys_template\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:35:\"&edit%5Bsys_template%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:12:\"sys_template\";s:3:\"uid\";i:1;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"c312013d83c1a6ad7fec8b36a37ba3c8\";a:4:{i:0;s:13:\"Mountains PNG\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:1;s:3:\"pid\";i:2;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"deac478137dd48a97e299bd046412e21\";a:4:{i:0;s:13:\"Mountains JPG\";i:1;a:5:{s:4:\"edit\";a:1:{s:10:\"tt_content\";a:1:{i:2;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:33:\"&edit%5Btt_content%5D%5B2%5D=edit\";i:3;a:5:{s:5:\"table\";s:10:\"tt_content\";s:3:\"uid\";i:2;s:3:\"pid\";i:2;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}}s:6:\"web_ts\";a:6:{s:8:\"function\";s:85:\"TYPO3\\CMS\\Tstemplate\\Controller\\TypoScriptTemplateInformationModuleFunctionController\";s:8:\"language\";N;s:19:\"constant_editor_cat\";s:7:\"content\";s:15:\"ts_browser_type\";s:5:\"setup\";s:16:\"ts_browser_const\";s:1:\"0\";s:23:\"ts_browser_showComments\";s:1:\"1\";}}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";i:50;s:8:\"edit_RTE\";s:1:\"1\";s:20:\"edit_docModuleUpload\";s:1:\"1\";s:25:\"resizeTextareas_MaxHeight\";i:500;s:4:\"lang\";s:7:\"default\";s:19:\"firstLoginTimeStamp\";i:1633799716;s:15:\"moduleSessionID\";a:9:{s:8:\"web_list\";s:40:\"59d586603c4c451a7613b5866169955b23d84b34\";s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";s:40:\"f14f5e4de3cf44d772d0811cd006a03522639a96\";s:9:\"clipboard\";s:40:\"f1de95ecf1147d811f11f80818a1c663707ac1b4\";s:10:\"FormEngine\";s:40:\"f14f5e4de3cf44d772d0811cd006a03522639a96\";s:16:\"browse_links.php\";s:40:\"cdd6684bda75f8b1b6d6cee35eb6bbfc1c5a8803\";s:9:\"file_list\";s:40:\"59d586603c4c451a7613b5866169955b23d84b34\";s:10:\"web_layout\";s:40:\"59d586603c4c451a7613b5866169955b23d84b34\";s:16:\"opendocs::recent\";s:40:\"f14f5e4de3cf44d772d0811cd006a03522639a96\";s:6:\"web_ts\";s:40:\"f14f5e4de3cf44d772d0811cd006a03522639a96\";}s:17:\"BackendComponents\";a:1:{s:6:\"States\";a:3:{s:8:\"Pagetree\";a:1:{s:9:\"stateHash\";a:1:{s:3:\"0_1\";s:1:\"1\";}}s:17:\"typo3-module-menu\";a:1:{s:9:\"collapsed\";s:5:\"false\";}s:15:\"FileStorageTree\";a:1:{s:9:\"stateHash\";a:1:{s:10:\"1_59663721\";s:1:\"1\";}}}}s:10:\"inlineView\";s:472:\"{\"tx_news_domain_model_news\":{\"3\":{\"sys_file_reference\":[1]},\"2\":{\"sys_file_reference\":[]},\"1\":{\"sys_file_reference\":[4]},\"7\":{\"sys_file_reference\":[5]},\"6\":{\"sys_file_reference\":[6,7]},\"5\":{\"sys_file_reference\":[8]}},\"tt_content\":{\"NEW62f90b8b572d2450519459\":{\"sys_file_reference\":[9]},\"NEW62f90babdb4a6388520815\":{\"sys_file_reference\":[10]},\"NEW62f90bfa5c2c3886539709\":{\"sys_file_reference\":[11]},\"1\":{\"sys_file_reference\":{\"3\":\"\"}},\"3\":{\"sys_file_reference\":{\"1\":\"\"}}}}\";s:10:\"navigation\";a:1:{s:5:\"width\";s:3:\"356\";}s:11:\"tx_recycler\";a:3:{s:14:\"depthSelection\";i:0;s:14:\"tableSelection\";s:0:\"\";s:11:\"resultLimit\";i:25;}}',NULL,NULL,1,NULL,1687683696,0,NULL,NULL,''),(2,0,1633799707,1633799707,0,0,0,0,0,NULL,'_cli_',0,'$argon2i$v=19$m=65536,t=16,p=1$ZlQuU051OUo4dFIwaVNiTw$emkqZDcEQmxJO0dnQxX5K0DWsTP15FFLhvoj7w+aLYg',1,NULL,'default','',NULL,0,'',NULL,'','a:9:{s:14:\"interfaceSetup\";s:0:\"\";s:10:\"moduleData\";a:0:{}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";i:50;s:8:\"edit_RTE\";s:1:\"1\";s:20:\"edit_docModuleUpload\";s:1:\"1\";s:25:\"resizeTextareas_MaxHeight\";i:500;s:4:\"lang\";s:7:\"default\";s:19:\"firstLoginTimeStamp\";i:1633799707;}',NULL,NULL,1,NULL,0,0,NULL,NULL,'');
DROP TABLE IF EXISTS `cache_hash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_hash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_hash_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_hash_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `tag` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_imagesizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_imagesizes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_imagesizes_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_imagesizes_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `tag` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_pages_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_pages_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `tag` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_pagesection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_pagesection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_pagesection_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_pagesection_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `tag` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_rootline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_rootline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_rootline_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_rootline_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `tag` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_treelist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_treelist` (
  `md5hash` varchar(32) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT 0,
  `treelist` mediumtext DEFAULT NULL,
  `tstamp` int(11) NOT NULL DEFAULT 0,
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`md5hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `fe_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `tx_extbase_type` varchar(255) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `subgroup` tinytext DEFAULT NULL,
  `TSconfig` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `fe_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_sessions` (
  `ses_id` varchar(190) NOT NULL DEFAULT '',
  `ses_iplock` varchar(39) NOT NULL DEFAULT '',
  `ses_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_data` mediumblob DEFAULT NULL,
  `ses_permanent` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`ses_id`),
  KEY `ses_tstamp` (`ses_tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `fe_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `tx_extbase_type` varchar(255) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usergroup` text DEFAULT NULL,
  `name` varchar(160) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `middle_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `uc` blob DEFAULT NULL,
  `title` varchar(40) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(40) NOT NULL DEFAULT '',
  `www` varchar(80) NOT NULL DEFAULT '',
  `company` varchar(80) NOT NULL DEFAULT '',
  `image` tinytext DEFAULT NULL,
  `TSconfig` text DEFAULT NULL,
  `lastlogin` int(10) unsigned NOT NULL DEFAULT 0,
  `is_online` int(10) unsigned NOT NULL DEFAULT 0,
  `mfa` mediumblob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`username`(100)),
  KEY `username` (`username`(100)),
  KEY `is_online` (`is_online`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `rowDescription` text DEFAULT NULL,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `perms_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_groupid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_user` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_group` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_everybody` smallint(5) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(2048) DEFAULT NULL,
  `doktype` int(10) unsigned NOT NULL DEFAULT 0,
  `TSconfig` text DEFAULT NULL,
  `is_siteroot` smallint(6) NOT NULL DEFAULT 0,
  `php_tree_stop` smallint(6) NOT NULL DEFAULT 0,
  `url` varchar(255) NOT NULL DEFAULT '',
  `shortcut` int(10) unsigned NOT NULL DEFAULT 0,
  `shortcut_mode` int(10) unsigned NOT NULL DEFAULT 0,
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `target` varchar(80) NOT NULL DEFAULT '',
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `lastUpdated` int(10) unsigned NOT NULL DEFAULT 0,
  `keywords` text DEFAULT NULL,
  `cache_timeout` int(10) unsigned NOT NULL DEFAULT 0,
  `cache_tags` varchar(255) NOT NULL DEFAULT '',
  `newUntil` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `no_search` smallint(5) unsigned NOT NULL DEFAULT 0,
  `SYS_LASTCHANGED` int(10) unsigned NOT NULL DEFAULT 0,
  `abstract` text DEFAULT NULL,
  `module` varchar(255) NOT NULL DEFAULT '',
  `extendToSubpages` smallint(5) unsigned NOT NULL DEFAULT 0,
  `author` varchar(255) NOT NULL DEFAULT '',
  `author_email` varchar(255) NOT NULL DEFAULT '',
  `nav_title` varchar(255) NOT NULL DEFAULT '',
  `nav_hide` smallint(6) NOT NULL DEFAULT 0,
  `content_from_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid_ol` smallint(6) NOT NULL DEFAULT 0,
  `l18n_cfg` smallint(6) NOT NULL DEFAULT 0,
  `fe_login_mode` smallint(6) NOT NULL DEFAULT 0,
  `backend_layout` varchar(64) NOT NULL DEFAULT '',
  `backend_layout_next_level` varchar(64) NOT NULL DEFAULT '',
  `tsconfig_includes` text DEFAULT NULL,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `determineSiteRoot` (`is_siteroot`),
  KEY `language_identifier` (`l10n_parent`,`sys_language_uid`),
  KEY `slug` (`slug`(127)),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `translation_source` (`l10n_source`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `pages` VALUES (1,0,1687686196,1633799707,1,0,0,0,0,'',0,NULL,0,0,0,0,NULL,0,'{\"doktype\":\"\",\"title\":\"\",\"slug\":\"\",\"nav_title\":\"\",\"subtitle\":\"\",\"abstract\":\"\",\"keywords\":\"\",\"description\":\"\",\"author\":\"\",\"author_email\":\"\",\"lastUpdated\":\"\",\"layout\":\"\",\"newUntil\":\"\",\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"content_from_pid\":\"\",\"target\":\"\",\"cache_timeout\":\"\",\"cache_tags\":\"\",\"is_siteroot\":\"\",\"no_search\":\"\",\"php_tree_stop\":\"\",\"module\":\"\",\"media\":\"\",\"tsconfig_includes\":\"\",\"TSconfig\":\"\",\"l18n_cfg\":\"\",\"hidden\":\"\",\"nav_hide\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"extendToSubpages\":\"\",\"fe_group\":\"\",\"fe_login_mode\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,1,1,31,31,1,'Home','/1',1,NULL,1,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1687686196,NULL,'',0,'','','',0,0,0,0,0,0,'','','EXT:imageopt/Configuration/TsConfig/Page/tx_imageopt__0100.tsconfig',0),(2,1,1633799946,1633799920,1,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,'{\"hidden\":\"\"}',0,0,0,0,1,0,31,27,0,'Page 1','/',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1633799946,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0),(4,1,1633800038,1633799980,1,0,0,0,0,'',384,NULL,0,0,0,0,NULL,3,'{\"hidden\":\"\"}',0,0,0,0,1,0,31,27,0,'Page 2','/1-1',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','','',0),(5,1,1633800065,1633800052,1,0,0,0,0,'0',448,NULL,0,0,0,0,NULL,0,'{\"hidden\":\"\"}',0,0,0,0,1,0,31,27,0,'Page 3','/page-5',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0);
DROP TABLE IF EXISTS `sys_be_shortcuts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_be_shortcuts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `route` varchar(255) NOT NULL DEFAULT '',
  `arguments` text DEFAULT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sc_group` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_category` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` tinytext NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT 0,
  `items` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `category_parent` (`parent`),
  KEY `category_list` (`pid`,`deleted`,`sys_language_uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_category_record_mm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_category_record_mm` (
  `uid_local` int(10) unsigned NOT NULL DEFAULT 0,
  `uid_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  `tablenames` varchar(255) NOT NULL DEFAULT '',
  `fieldname` varchar(255) NOT NULL DEFAULT '',
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_category_record_mm` VALUES (1,1,'tx_news_domain_model_news','categories',1,1),(2,1,'tx_news_domain_model_news','categories',1,2),(2,2,'tx_news_domain_model_news','categories',2,1),(3,3,'tx_news_domain_model_news','categories',1,1),(4,3,'tx_news_domain_model_news','categories',1,2),(11,10,'tx_news_domain_model_news','categories',0,1);
DROP TABLE IF EXISTS `sys_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `last_indexed` int(11) NOT NULL DEFAULT 0,
  `missing` smallint(6) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `type` varchar(10) NOT NULL DEFAULT '',
  `metadata` int(11) NOT NULL DEFAULT 0,
  `identifier` text DEFAULT NULL,
  `identifier_hash` varchar(40) NOT NULL DEFAULT '',
  `folder_hash` varchar(40) NOT NULL DEFAULT '',
  `extension` varchar(255) NOT NULL DEFAULT '',
  `mime_type` varchar(255) NOT NULL DEFAULT '',
  `name` tinytext DEFAULT NULL,
  `sha1` varchar(40) NOT NULL DEFAULT '',
  `size` bigint(20) unsigned NOT NULL DEFAULT 0,
  `creation_date` int(11) NOT NULL DEFAULT 0,
  `modification_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `sel01` (`storage`,`identifier_hash`),
  KEY `folder` (`storage`,`folder_hash`),
  KEY `tstamp` (`tstamp`),
  KEY `lastindex` (`last_indexed`),
  KEY `sha1` (`sha1`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_file` VALUES (1,0,1633800114,0,0,1,'2',0,'/user_upload/test1.jpg','984f1c74213b29590ed270a4cae8d2cfd422cf12','19669f1e02c2f16705ec7587044c66443be70725','jpg','image/jpeg','test1.jpg','c7254f44aa10b6f89e328731672eda5082fd4976',42520,1633799710,1633799710),(2,0,1633803887,0,0,1,'5',0,'/user_upload/index.html','c25533f303185517ca3e1e24b215d53aa74076d2','19669f1e02c2f16705ec7587044c66443be70725','html','application/x-empty','index.html','da39a3ee5e6b4b0d3255bfef95601890afd80709',0,1633803713,1633803713),(3,0,1660488499,0,0,1,'2',0,'/user_upload/mountains.gif','ab637d321855c75f1953cce933f08af263b6ffcd','19669f1e02c2f16705ec7587044c66443be70725','gif','image/gif','mountains.gif','cfc737c6ea99b64a738b9ed486c8e906a6e3f4c9',23763,1660487914,1660487914),(4,0,1660488499,0,0,1,'2',0,'/user_upload/mountains.jpg','2998a3ce0425cd791e3ca512f0a5b1d3bcc8c9cf','19669f1e02c2f16705ec7587044c66443be70725','jpg','image/jpeg','mountains.jpg','ce134d9a8000a5a713efb4d0ed44fee3dd11559f',286627,1660487914,1660487914),(5,0,1660488499,0,0,1,'2',0,'/user_upload/mountains.png','5e73e9371a790fe28c81301777f195f345bbf5a3','19669f1e02c2f16705ec7587044c66443be70725','png','image/png','mountains.png','4c3651b8d66891dae1050e61c8a1da5695195bce',1324708,1660487914,1660487914);
DROP TABLE IF EXISTS `sys_file_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_collection` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` tinytext DEFAULT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'static',
  `files` int(11) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `folder` text DEFAULT NULL,
  `recursive` smallint(6) NOT NULL DEFAULT 0,
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_file_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_metadata` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `file` int(11) NOT NULL DEFAULT 0,
  `title` tinytext DEFAULT NULL,
  `width` int(11) NOT NULL DEFAULT 0,
  `height` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `alternative` text DEFAULT NULL,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `file` (`file`),
  KEY `fal_filelist` (`l10n_parent`,`sys_language_uid`),
  KEY `parent` (`pid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_file_metadata` VALUES (1,0,1633800114,1633800114,1,0,0,NULL,0,'',0,0,0,0,1,NULL,720,449,NULL,NULL,0),(2,0,1633803887,1633803887,1,0,0,NULL,0,'',0,0,0,0,2,NULL,0,0,NULL,NULL,0),(3,0,1660488499,1660488499,1,0,0,NULL,0,'',0,0,0,0,3,NULL,300,240,NULL,NULL,0),(4,0,1660488499,1660488499,1,0,0,NULL,0,'',0,0,0,0,4,NULL,800,532,NULL,NULL,0),(5,0,1660488499,1660488499,1,0,0,NULL,0,'',0,0,0,0,5,NULL,1024,649,NULL,NULL,0);
DROP TABLE IF EXISTS `sys_file_processedfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_processedfile` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `tstamp` int(11) NOT NULL DEFAULT 0,
  `crdate` int(11) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `original` int(11) NOT NULL DEFAULT 0,
  `identifier` varchar(512) NOT NULL DEFAULT '',
  `name` tinytext DEFAULT NULL,
  `processing_url` text DEFAULT NULL,
  `configuration` blob DEFAULT NULL,
  `configurationsha1` varchar(40) NOT NULL DEFAULT '',
  `originalfilesha1` varchar(40) NOT NULL DEFAULT '',
  `task_type` varchar(200) NOT NULL DEFAULT '',
  `checksum` varchar(32) NOT NULL DEFAULT '',
  `width` int(11) DEFAULT 0,
  `height` int(11) DEFAULT 0,
  `tx_imageopt_executed_successfully` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `combined_1` (`original`,`task_type`(100),`configurationsha1`),
  KEY `identifier` (`storage`,`identifier`(180))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_file_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_reference` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `uid_local` int(11) NOT NULL DEFAULT 0,
  `uid_foreign` int(11) NOT NULL DEFAULT 0,
  `tablenames` varchar(64) NOT NULL DEFAULT '',
  `fieldname` varchar(64) NOT NULL DEFAULT '',
  `sorting_foreign` int(11) NOT NULL DEFAULT 0,
  `table_local` varchar(64) NOT NULL DEFAULT '',
  `title` tinytext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `alternative` text DEFAULT NULL,
  `link` varchar(1024) NOT NULL DEFAULT '',
  `crop` varchar(4000) NOT NULL DEFAULT '',
  `autoplay` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `tablenames_fieldname` (`tablenames`(32),`fieldname`(12)),
  KEY `deleted` (`deleted`),
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`),
  KEY `combined_1` (`l10n_parent`,`t3ver_oid`,`t3ver_wsid`,`t3ver_state`,`deleted`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_file_reference` VALUES (9,1,1687684147,1660488612,1,0,0,0,0,NULL,'{\"hidden\":\"\"}',0,0,0,0,5,1,'tt_content','image',1,'sys_file',NULL,NULL,NULL,'','{\"default\":{\"cropArea\":{\"x\":0,\"y\":0,\"width\":1,\"height\":1},\"selectedRatio\":\"NaN\",\"focusArea\":null}}',0),(10,1,1660491061,1660488692,1,0,0,0,0,NULL,'',0,0,0,0,4,2,'tt_content','image',1,'sys_file',NULL,NULL,NULL,'','{\"default\":{\"cropArea\":{\"x\":0,\"y\":0,\"width\":1,\"height\":1},\"selectedRatio\":\"NaN\",\"focusArea\":null}}',0),(11,1,1660491075,1660488711,1,0,0,0,0,NULL,'',0,0,0,0,3,3,'tt_content','image',1,'sys_file',NULL,NULL,NULL,'','{\"default\":{\"cropArea\":{\"x\":0,\"y\":0,\"width\":1,\"height\":1},\"selectedRatio\":\"NaN\",\"focusArea\":null}}',0);
DROP TABLE IF EXISTS `sys_file_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_storage` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `driver` tinytext DEFAULT NULL,
  `configuration` text DEFAULT NULL,
  `is_default` smallint(6) NOT NULL DEFAULT 0,
  `is_browsable` smallint(6) NOT NULL DEFAULT 0,
  `is_public` smallint(6) NOT NULL DEFAULT 0,
  `is_writable` smallint(6) NOT NULL DEFAULT 0,
  `is_online` smallint(6) NOT NULL DEFAULT 1,
  `auto_extract_metadata` smallint(6) NOT NULL DEFAULT 1,
  `processingfolder` tinytext DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_file_storage` VALUES (1,0,1633799890,1633799890,0,0,'This is the local fileadmin/ directory. This storage mount has been created automatically by TYPO3.','fileadmin','Local','<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"basePath\">\n                    <value index=\"vDEF\">fileadmin/</value>\n                </field>\n                <field index=\"pathType\">\n                    <value index=\"vDEF\">relative</value>\n                </field>\n                <field index=\"caseSensitive\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>',1,1,1,1,1,1,NULL);
DROP TABLE IF EXISTS `sys_filemounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_filemounts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `base` int(10) unsigned NOT NULL DEFAULT 0,
  `read_only` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_history` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `actiontype` smallint(6) NOT NULL DEFAULT 0,
  `usertype` varchar(2) NOT NULL DEFAULT 'BE',
  `userid` int(10) unsigned DEFAULT NULL,
  `originaluserid` int(10) unsigned DEFAULT NULL,
  `recuid` int(11) NOT NULL DEFAULT 0,
  `tablename` varchar(255) NOT NULL DEFAULT '',
  `history_data` mediumtext DEFAULT NULL,
  `workspace` int(11) DEFAULT 0,
  `correlation_id` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `recordident_1` (`tablename`(100),`recuid`),
  KEY `recordident_2` (`tablename`(100),`tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_language` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `flag` varchar(20) NOT NULL DEFAULT '',
  `language_isocode` varchar(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_lockedrecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_lockedrecords` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `record_table` varchar(255) NOT NULL DEFAULT '',
  `record_uid` int(11) NOT NULL DEFAULT 0,
  `record_pid` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) NOT NULL DEFAULT '',
  `feuserid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`,`tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_log` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `action` smallint(5) unsigned NOT NULL DEFAULT 0,
  `recuid` int(10) unsigned NOT NULL DEFAULT 0,
  `tablename` varchar(255) NOT NULL DEFAULT '',
  `recpid` int(11) NOT NULL DEFAULT 0,
  `error` smallint(5) unsigned NOT NULL DEFAULT 0,
  `details` text DEFAULT NULL,
  `type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `channel` varchar(20) NOT NULL DEFAULT 'default',
  `details_nr` smallint(6) NOT NULL DEFAULT 0,
  `IP` varchar(39) NOT NULL DEFAULT '',
  `log_data` text DEFAULT NULL,
  `event_pid` int(11) NOT NULL DEFAULT -1,
  `workspace` int(11) NOT NULL DEFAULT 0,
  `NEWid` varchar(30) NOT NULL DEFAULT '',
  `request_id` varchar(13) NOT NULL DEFAULT '',
  `time_micro` double NOT NULL DEFAULT 0,
  `component` varchar(255) NOT NULL DEFAULT '',
  `level` varchar(10) NOT NULL DEFAULT 'info',
  `message` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`,`event_pid`),
  KEY `recuidIdx` (`recuid`),
  KEY `user_auth` (`type`,`action`,`tstamp`),
  KEY `request` (`request_id`),
  KEY `combined_1` (`tstamp`,`type`,`userid`),
  KEY `errorcount` (`tstamp`,`error`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_news` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` mediumtext DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sys_refindex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_refindex` (
  `hash` varchar(32) NOT NULL DEFAULT '',
  `tablename` varchar(255) NOT NULL DEFAULT '',
  `recuid` int(11) NOT NULL DEFAULT 0,
  `field` varchar(64) NOT NULL DEFAULT '',
  `flexpointer` varchar(255) NOT NULL DEFAULT '',
  `softref_key` varchar(30) NOT NULL DEFAULT '',
  `softref_id` varchar(40) NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `workspace` int(11) NOT NULL DEFAULT 0,
  `ref_table` varchar(255) NOT NULL DEFAULT '',
  `ref_uid` int(11) NOT NULL DEFAULT 0,
  `ref_string` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`hash`),
  KEY `lookup_rec` (`tablename`(100),`recuid`),
  KEY `lookup_uid` (`ref_table`(100),`ref_uid`),
  KEY `lookup_string` (`ref_string`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_refindex` VALUES ('1c9b8784c1518ef7b22704c4fc698ca9','sys_file',2,'storage','','','',0,0,'sys_file_storage',1,''),('24d47b29aa969cf4db8635e76dd1c386','sys_file',3,'storage','','','',0,0,'sys_file_storage',1,''),('39433ea4a82060704109046e4828d3c8','sys_file',1,'storage','','','',0,0,'sys_file_storage',1,''),('5f96c9d1b23a3eb2e3d486af7580481d','tx_news_domain_model_news',8,'tags','','','',1,0,'tx_news_domain_model_tag',13,''),('608a993cdac975e58fcb3ab662100b0b','tt_content',2,'image','','','',0,0,'sys_file_reference',10,''),('67b90e6dccc1d6b9f30da2d36966db9b','sys_file_reference',11,'uid_local','','','',0,0,'sys_file',3,''),('76b1fdf70b530b6a38e2a32f38662df6','tt_content',1,'image','','','',0,0,'sys_file_reference',9,''),('777ff0dd31b1293f0716575883b21f36','sys_file_reference',10,'uid_local','','','',0,0,'sys_file',4,''),('791d3f9d43dcbfa78cd49dd8258caa09','sys_file',5,'storage','','','',0,0,'sys_file_storage',1,''),('88fbed8d79bb703be444c0c3b7868a86','tx_news_domain_model_news',8,'tags','','','',0,0,'tx_news_domain_model_tag',10,''),('9b3b13ed917ab3bb864500827b04be21','tt_content',3,'image','','','',0,0,'sys_file_reference',11,''),('b106d33011e8c34cc1b2b38e9b1d148d','tx_news_domain_model_news',9,'tags','','','',1,0,'tx_news_domain_model_tag',11,''),('bab37143de5339e474516691bf0c5857','sys_file',4,'storage','','','',0,0,'sys_file_storage',1,''),('c95a0a7f1642524fc15936be6089bda3','tx_news_domain_model_news',10,'tags','','','',0,0,'tx_news_domain_model_tag',13,''),('dd7467573df5df2d8f1de4892926a523','tx_news_domain_model_news',9,'tags','','','',0,0,'tx_news_domain_model_tag',10,''),('f248493cc189f41b4f2a187098eb573f','sys_file_reference',9,'uid_local','','','',0,0,'sys_file',5,''),('fe80a6589cac9798aa13ab5e0192cb56','sys_file',1,'metadata','','','',0,0,'sys_file_metadata',1,'');
DROP TABLE IF EXISTS `sys_registry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_registry` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_namespace` varchar(128) NOT NULL DEFAULT '',
  `entry_key` varchar(128) NOT NULL DEFAULT '',
  `entry_value` mediumblob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `entry_identifier` (`entry_namespace`,`entry_key`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_registry` VALUES (1,'installUpdate','TYPO3\\CMS\\Install\\Updates\\FeeditExtractionUpdate','i:1;'),(2,'installUpdate','TYPO3\\CMS\\Install\\Updates\\TaskcenterExtractionUpdate','i:1;'),(3,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysActionExtractionUpdate','i:1;'),(4,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SvgFilesSanitization','i:1;'),(5,'installUpdate','TYPO3\\CMS\\Install\\Updates\\ShortcutRecordsMigration','i:1;'),(6,'installUpdate','TYPO3\\CMS\\Install\\Updates\\CollectionsExtractionUpdate','i:1;'),(7,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendUserLanguageMigration','i:1;'),(8,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysLogChannel','i:1;'),(9,'installUpdate','GeorgRinger\\News\\Updates\\RealurlAliasNewsSlugUpdater','i:1;'),(10,'installUpdate','GeorgRinger\\News\\Updates\\NewsSlugUpdater','i:1;'),(11,'installUpdate','GeorgRinger\\News\\Updates\\PopulateCategorySlugs','i:1;'),(12,'installUpdate','GeorgRinger\\News\\Updates\\PopulateTagSlugs','i:1;'),(13,'installUpdateRows','rowUpdatersDone','a:4:{i:0;s:69:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceVersionRecordsMigration\";i:1;s:66:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\L18nDiffsourceToJsonMigration\";i:2;s:77:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceMovePlaceholderRemovalMigration\";i:3;s:76:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceNewPlaceholderRemovalMigration\";}'),(14,'extensionDataImport','typo3/sysext/core/ext_tables_static+adt.sql','s:0:\"\";'),(15,'extensionDataImport','typo3/sysext/extbase/ext_tables_static+adt.sql','s:0:\"\";'),(16,'extensionDataImport','typo3/sysext/fluid/ext_tables_static+adt.sql','s:0:\"\";'),(17,'extensionDataImport','typo3/sysext/install/ext_tables_static+adt.sql','s:0:\"\";'),(18,'extensionDataImport','typo3/sysext/recordlist/ext_tables_static+adt.sql','s:0:\"\";'),(19,'extensionDataImport','typo3/sysext/backend/ext_tables_static+adt.sql','s:0:\"\";'),(20,'extensionDataImport','typo3/sysext/extensionmanager/ext_tables_static+adt.sql','s:0:\"\";'),(21,'extensionDataImport','typo3/sysext/filelist/ext_tables_static+adt.sql','s:0:\"\";'),(22,'extensionDataImport','typo3/sysext/frontend/ext_tables_static+adt.sql','s:0:\"\";'),(23,'extensionDataImport','helhum/typo3-console/ext_tables_static+adt.sql','s:0:\"\";'),(24,'extensionDataImport','typo3conf/ext/news/ext_tables_static+adt.sql','s:0:\"\";'),(25,'extensionDataImport','les_static+adt.sql','s:0:\"\";'),(26,'core','formProtectionSessionToken:1','s:64:\"354ced82d2c50a4be4ddfafa562a15797a14a8bbda18f9e1565536ac1b5cf142\";');
DROP TABLE IF EXISTS `sys_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_template` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `root` smallint(5) unsigned NOT NULL DEFAULT 0,
  `clear` smallint(5) unsigned NOT NULL DEFAULT 0,
  `include_static_file` text DEFAULT NULL,
  `constants` text DEFAULT NULL,
  `config` text DEFAULT NULL,
  `basedOn` tinytext DEFAULT NULL,
  `includeStaticAfterBasedOn` smallint(5) unsigned NOT NULL DEFAULT 0,
  `static_file_mode` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `roottemplate` (`deleted`,`hidden`,`root`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sys_template` VALUES (1,1,1687684381,1633803717,1,0,0,0,0,0,'This is an Empty Site Package TypoScript template.\r\n\r\nFor each website you need a TypoScript template on the main page of your website (on the top level). For better maintenance all TypoScript should be extracted into external files via @import \'EXT:site_myproject/Configuration/TypoScript/setup.typoscript\'',0,0,0,0,0,'Main TypoScript Rendering',1,1,'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:fluid_styled_content/Configuration/TypoScript/Styling/','styles.content.textmedia.maxW = 2000','@import \'EXT:imageopt/Configuration/TypoScript/setup.typoscript\'\r\n\r\npage = PAGE\r\npage.100 = CONTENT\r\npage.100 {\r\n    table = tt_content\r\n    select {\r\n        orderBy = sorting\r\n        where = {#colPos}=0\r\n    }\r\n}\r\n',NULL,0,0);
DROP TABLE IF EXISTS `tt_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tt_content` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rowDescription` text DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l18n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l18n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `CType` varchar(255) NOT NULL DEFAULT '',
  `header` varchar(255) NOT NULL DEFAULT '',
  `header_position` varchar(255) NOT NULL DEFAULT '',
  `bodytext` mediumtext DEFAULT NULL,
  `bullets_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_description` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `assets` int(10) unsigned NOT NULL DEFAULT 0,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `imagewidth` int(10) unsigned NOT NULL DEFAULT 0,
  `imageorient` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imagecols` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageborder` smallint(5) unsigned NOT NULL DEFAULT 0,
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `frame_class` varchar(60) NOT NULL DEFAULT 'default',
  `cols` int(10) unsigned NOT NULL DEFAULT 0,
  `space_before_class` varchar(60) NOT NULL DEFAULT '',
  `space_after_class` varchar(60) NOT NULL DEFAULT '',
  `records` text DEFAULT NULL,
  `pages` text DEFAULT NULL,
  `colPos` int(10) unsigned NOT NULL DEFAULT 0,
  `subheader` varchar(255) NOT NULL DEFAULT '',
  `header_link` varchar(1024) NOT NULL DEFAULT '',
  `image_zoom` smallint(5) unsigned NOT NULL DEFAULT 0,
  `header_layout` varchar(30) NOT NULL DEFAULT '0',
  `list_type` varchar(255) NOT NULL DEFAULT '',
  `sectionIndex` smallint(5) unsigned NOT NULL DEFAULT 0,
  `linkToTop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `file_collections` text DEFAULT NULL,
  `filelink_size` smallint(5) unsigned NOT NULL DEFAULT 0,
  `filelink_sorting` varchar(64) NOT NULL DEFAULT '',
  `filelink_sorting_direction` varchar(4) NOT NULL DEFAULT '',
  `target` varchar(30) NOT NULL DEFAULT '',
  `date` int(10) unsigned NOT NULL DEFAULT 0,
  `recursive` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageheight` int(10) unsigned NOT NULL DEFAULT 0,
  `pi_flexform` mediumtext DEFAULT NULL,
  `accessibility_title` varchar(30) NOT NULL DEFAULT '',
  `accessibility_bypass` smallint(5) unsigned NOT NULL DEFAULT 0,
  `accessibility_bypass_text` varchar(30) NOT NULL DEFAULT '',
  `category_field` varchar(64) NOT NULL DEFAULT '',
  `table_class` varchar(60) NOT NULL DEFAULT '',
  `table_caption` varchar(255) DEFAULT NULL,
  `table_delimiter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_enclosure` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_header_position` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_tfoot` smallint(5) unsigned NOT NULL DEFAULT 0,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  `selected_categories` longtext DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`sorting`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `language` (`l18n_parent`,`sys_language_uid`),
  KEY `translation_source` (`l10n_source`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `tt_content` VALUES (1,'',1,1687684147,1660488612,1,0,0,0,0,'',256,0,0,0,0,NULL,0,'{\"CType\":\"\",\"colPos\":\"\",\"header\":\"\",\"header_layout\":\"\",\"header_position\":\"\",\"date\":\"\",\"header_link\":\"\",\"subheader\":\"\",\"image\":\"\",\"imagewidth\":\"\",\"imageheight\":\"\",\"imageborder\":\"\",\"imageorient\":\"\",\"imagecols\":\"\",\"image_zoom\":\"\",\"layout\":\"\",\"frame_class\":\"\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":\"\",\"linkToTop\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"editlock\":\"\",\"categories\":\"\",\"rowDescription\":\"\"}',0,0,0,0,'image','Mountains PNG','',NULL,0,0,0,0,1,1024,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','',1,0,NULL,0,'','','',0,0,0,NULL,'',0,'','','',NULL,124,0,0,0,0,NULL),(2,'',1,1660491061,1660488692,1,0,0,0,0,'',512,0,0,0,0,NULL,0,'{\"colPos\":\"\",\"sys_language_uid\":\"\"}',0,0,0,0,'image','Mountains JPG','',NULL,0,0,0,0,1,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','',1,0,NULL,0,'','','',0,0,0,NULL,'',0,'','','',NULL,124,0,0,0,0,NULL),(3,'',1,1660491075,1660488711,1,0,0,0,0,'',768,0,0,0,0,NULL,0,'{\"colPos\":\"\",\"sys_language_uid\":\"\"}',0,0,0,0,'image','Mountains GIF','',NULL,0,0,0,0,1,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','',1,0,NULL,0,'','','',0,0,0,NULL,'',0,'','','',NULL,124,0,0,0,0,NULL);
DROP TABLE IF EXISTS `tx_extensionmanager_domain_model_extension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_extensionmanager_domain_model_extension` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `extension_key` varchar(60) NOT NULL DEFAULT '',
  `repository` int(11) NOT NULL DEFAULT 1,
  `remote` varchar(100) NOT NULL DEFAULT 'ter',
  `version` varchar(15) NOT NULL DEFAULT '',
  `alldownloadcounter` int(10) unsigned NOT NULL DEFAULT 0,
  `downloadcounter` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(150) NOT NULL DEFAULT '',
  `description` mediumtext DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `review_state` int(11) NOT NULL DEFAULT 0,
  `category` int(11) NOT NULL DEFAULT 0,
  `last_updated` int(10) unsigned NOT NULL DEFAULT 0,
  `serialized_dependencies` mediumtext DEFAULT NULL,
  `author_name` varchar(255) NOT NULL DEFAULT '',
  `author_email` varchar(255) NOT NULL DEFAULT '',
  `ownerusername` varchar(50) NOT NULL DEFAULT '',
  `md5hash` varchar(35) NOT NULL DEFAULT '',
  `update_comment` mediumtext DEFAULT NULL,
  `authorcompany` varchar(255) NOT NULL DEFAULT '',
  `integer_version` int(11) NOT NULL DEFAULT 0,
  `current_version` int(11) NOT NULL DEFAULT 0,
  `lastreviewedversion` int(11) NOT NULL DEFAULT 0,
  `documentation_link` varchar(2048) DEFAULT NULL,
  `distribution_image` varchar(255) DEFAULT NULL,
  `distribution_welcome_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `versionextrepo` (`extension_key`,`version`,`remote`),
  KEY `index_extrepo` (`extension_key`,`remote`),
  KEY `index_versionrepo` (`integer_version`,`remote`,`extension_key`),
  KEY `index_currentversions` (`current_version`,`review_state`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `tx_imageopt_domain_model_executorresult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_imageopt_domain_model_executorresult` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `provider_result` int(10) unsigned NOT NULL DEFAULT 0,
  `size_before` varchar(20) NOT NULL DEFAULT '',
  `size_after` varchar(20) NOT NULL DEFAULT '',
  `command` text DEFAULT NULL,
  `command_output` text DEFAULT NULL,
  `command_status` varchar(255) NOT NULL DEFAULT '',
  `executed_successfully` smallint(5) unsigned NOT NULL DEFAULT 0,
  `error_message` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `tx_imageopt_domain_model_executorresult` VALUES (1,1,1687684612,1687684612,0,0,0,1,'392600','48838','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptIWci6N\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptIWci6N\'','','0',1,''),(2,1,1687684612,1687684612,0,0,0,2,'61356','39612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptGWL1lN\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptGWL1lN\'','','0',1,''),(3,1,1687684612,1687684612,0,0,0,3,'6681','1056','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptF9iKPO\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptF9iKPO\'','','0',1,''),(4,1,1687684612,1687684612,0,0,0,4,'64218','9100','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptkpRYcL\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptkpRYcL\'','','0',1,''),(5,1,1687684612,1687684612,0,0,0,5,'1834','1216','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptPVhtGM\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptPVhtGM\'','','0',1,''),(6,1,1687686244,1687686244,0,0,0,6,'2089','2081','/usr/bin/gifsicle --optimize=3 \'/var/www/html/.test/v11/var/transient/tx_imageopt4bc0Fm\' -o \'/var/www/html/.test/v11/var/transient/tx_imageopt4bc0Fm\'','','0',1,''),(7,1,1687686244,1687686244,0,0,0,7,'15981','15988','/usr/bin/gifsicle --optimize=3 \'/var/www/html/.test/v11/var/transient/tx_imageoptxOjkUi\' -o \'/var/www/html/.test/v11/var/transient/tx_imageoptxOjkUi\'','','0',1,''),(8,1,1687686244,1687686244,0,0,0,8,'380384','153003','/usr/bin/pngquant \'/var/www/html/.test/v11/var/transient/tx_imageoptRhqJZk\' --force --ext \'\' --strip --speed 1','','0',1,''),(9,1,1687686244,1687686244,0,0,0,9,'153003','148159','/usr/bin/optipng \'/var/www/html/.test/v11/var/transient/tx_imageoptMGBdWk\' -quiet -strip all -o7','','0',1,''),(10,1,1687686244,1687686244,0,0,0,10,'153003','149343','/usr/bin/pngcrush -s -rem alla -brute -reduce -ow \'/var/www/html/.test/v11/var/transient/tx_imageoptBfAYkl\' >/dev/null','','0',1,''),(11,1,1687686244,1687686244,0,0,0,11,'380384','48608','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptI37oIi\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptI37oIi\'','','0',1,''),(12,1,1687686244,1687686244,0,0,0,12,'1136662','458871','/usr/bin/pngquant \'/var/www/html/.test/v11/var/transient/tx_imageoptmgh25k\' --force --ext \'\' --strip --speed 1','','0',1,''),(13,1,1687686244,1687686244,0,0,0,13,'458871','438728','/usr/bin/optipng \'/var/www/html/.test/v11/var/transient/tx_imageoptmrchpj\' -quiet -strip all -o7','','0',1,''),(14,1,1687686244,1687686244,0,0,0,14,'458871','439041','/usr/bin/pngcrush -s -rem alla -brute -reduce -ow \'/var/www/html/.test/v11/var/transient/tx_imageoptL64bXl\' >/dev/null','','0',1,''),(15,1,1687686244,1687686244,0,0,0,15,'1136662','139444','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt2ndENm\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt2ndENm\'','','0',1,''),(16,1,1687686244,1687686244,0,0,0,16,'268469','268469','/usr/bin/mozjpeg-cjpeg -tune-hvs-psnr -quality 85 -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageoptn5y8Li\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptn5y8Li\'','','0',1,''),(17,1,1687686244,1687686244,0,0,0,16,'268469','78697','/bin/cp -f \'/var/www/html/.test/v11/var/transient/tx_imageoptn5y8Li\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptn5y8Li\'','','0',1,''),(18,1,1687686244,1687686244,0,0,0,17,'78697','78697','/usr/bin/mozjpeg-jpegtran -copy none -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageoptV1Fvij\' \'/var/www/html/.test/v11/var/transient/tx_imageoptV1Fvij\'','','0',1,''),(19,1,1687686244,1687686244,0,0,0,18,'268469','60548','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptB9xbSk\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptB9xbSk\'','','0',1,''),(20,1,1687686244,1687686244,0,0,0,19,'23625','23628','/usr/bin/gifsicle --optimize=3 \'/var/www/html/.test/v11/var/transient/tx_imageoptxIzULj\' -o \'/var/www/html/.test/v11/var/transient/tx_imageoptxIzULj\'','','0',1,''),(21,1,1687687200,1687687200,0,0,0,20,'392600','158550','/usr/bin/pngquant \'/var/www/html/.test/v11/var/transient/tx_imageoptkiKhb6\' --force --ext \'\' --strip --speed 1','','0',1,''),(22,1,1687687200,1687687200,0,0,0,21,'158550','153271','/usr/bin/optipng \'/var/www/html/.test/v11/var/transient/tx_imageoptLQegH4\' -quiet -strip all -o7','','0',1,''),(23,1,1687687200,1687687200,0,0,0,22,'158550','154317','/usr/bin/pngcrush -s -rem alla -brute -reduce -ow \'/var/www/html/.test/v11/var/transient/tx_imageoptAca7x5\' >/dev/null','','0',1,''),(24,1,1687687200,1687687200,0,0,0,23,'392600','48838','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt9IUDw6\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt9IUDw6\'','','0',1,''),(25,1,1687687200,1687687200,0,0,0,24,'61356','61356','/usr/bin/mozjpeg-cjpeg -tune-hvs-psnr -quality 85 -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageoptLEKQr6\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptLEKQr6\'','','0',1,''),(26,1,1687687200,1687687200,0,0,0,24,'61356','50315','/bin/cp -f \'/var/www/html/.test/v11/var/transient/tx_imageoptLEKQr6\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptLEKQr6\'','','0',1,''),(27,1,1687687200,1687687200,0,0,0,25,'50315','50315','/usr/bin/mozjpeg-jpegtran -copy none -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageoptHiHup7\' \'/var/www/html/.test/v11/var/transient/tx_imageoptHiHup7\'','','0',1,''),(28,1,1687687200,1687687200,0,0,0,26,'61356','39612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptEj15f7\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptEj15f7\'','','0',1,''),(29,1,1687687200,1687687200,0,0,0,27,'6681','3708','/usr/bin/pngquant \'/var/www/html/.test/v11/var/transient/tx_imageopth6DtL5\' --force --ext \'\' --strip --speed 1','','0',1,''),(30,1,1687687200,1687687200,0,0,0,28,'3708','3697','/usr/bin/optipng \'/var/www/html/.test/v11/var/transient/tx_imageoptq7XFD5\' -quiet -strip all -o7','','0',1,''),(31,1,1687687200,1687687200,0,0,0,29,'3708','3697','/usr/bin/pngcrush -s -rem alla -brute -reduce -ow \'/var/www/html/.test/v11/var/transient/tx_imageoptUx7wz5\' >/dev/null','','0',1,''),(32,1,1687687200,1687687200,0,0,0,30,'6681','1056','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptIWWJb4\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptIWWJb4\'','','0',1,''),(33,1,1687687200,1687687200,0,0,0,31,'64218','26684','/usr/bin/pngquant \'/var/www/html/.test/v11/var/transient/tx_imageopt7vC5L7\' --force --ext \'\' --strip --speed 1','','0',1,''),(34,1,1687687200,1687687200,0,0,0,32,'26684','26648','/usr/bin/optipng \'/var/www/html/.test/v11/var/transient/tx_imageoptFJ2TE7\' -quiet -strip all -o7','','0',1,''),(35,1,1687687200,1687687200,0,0,0,33,'26684','27713','/usr/bin/pngcrush -s -rem alla -brute -reduce -ow \'/var/www/html/.test/v11/var/transient/tx_imageoptBhmXt7\' >/dev/null','','0',1,''),(36,1,1687687200,1687687200,0,0,0,34,'64218','9100','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptt4keL7\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptt4keL7\'','','0',1,''),(37,1,1687687200,1687687200,0,0,0,35,'1834','1834','/usr/bin/mozjpeg-cjpeg -tune-hvs-psnr -quality 85 -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageoptsxTHW4\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptsxTHW4\'','','0',1,''),(38,1,1687687200,1687687200,0,0,0,35,'1834','1687','/bin/cp -f \'/var/www/html/.test/v11/var/transient/tx_imageoptsxTHW4\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptsxTHW4\'','','0',1,''),(39,1,1687687200,1687687200,0,0,0,36,'1687','1687','/usr/bin/mozjpeg-jpegtran -copy none -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageoptYoq7b6\' \'/var/www/html/.test/v11/var/transient/tx_imageoptYoq7b6\'','','0',1,''),(40,1,1687687200,1687687200,0,0,0,37,'1834','1216','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptPDoks6\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptPDoks6\'','','0',1,''),(41,1,1687687200,1687687200,0,0,0,38,'2081','2081','/usr/bin/gifsicle --optimize=3 \'/var/www/html/.test/v11/var/transient/tx_imageoptUUcvV5\' -o \'/var/www/html/.test/v11/var/transient/tx_imageoptUUcvV5\'','','0',1,''),(42,1,1687687200,1687687200,0,0,0,39,'15981','15988','/usr/bin/gifsicle --optimize=3 \'/var/www/html/.test/v11/var/transient/tx_imageoptHwtZp3\' -o \'/var/www/html/.test/v11/var/transient/tx_imageoptHwtZp3\'','','0',1,''),(43,1,1687687200,1687687200,0,0,0,40,'148159','152978','/usr/bin/pngquant \'/var/www/html/.test/v11/var/transient/tx_imageopteEjOP7\' --force --ext \'\' --strip --speed 1','','0',1,''),(44,1,1687687200,1687687200,0,0,0,41,'148159','148159','/usr/bin/optipng \'/var/www/html/.test/v11/var/transient/tx_imageoptBBmlu5\' -quiet -strip all -o7','','0',1,''),(45,1,1687687200,1687687200,0,0,0,42,'148159','149343','/usr/bin/pngcrush -s -rem alla -brute -reduce -ow \'/var/www/html/.test/v11/var/transient/tx_imageoptKbLRo4\' >/dev/null','','0',1,''),(46,1,1687687200,1687687200,0,0,0,43,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptF1oA75\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptF1oA75\'','','0',1,''),(47,1,1687687200,1687687200,0,0,0,44,'438728','458834','/usr/bin/pngquant \'/var/www/html/.test/v11/var/transient/tx_imageoptwnBxC7\' --force --ext \'\' --strip --speed 1','','0',1,''),(48,1,1687687200,1687687200,0,0,0,45,'438728','438728','/usr/bin/optipng \'/var/www/html/.test/v11/var/transient/tx_imageoptiF1Fm4\' -quiet -strip all -o7','','0',1,''),(49,1,1687687200,1687687200,0,0,0,46,'438728','439041','/usr/bin/pngcrush -s -rem alla -brute -reduce -ow \'/var/www/html/.test/v11/var/transient/tx_imageoptWwG2S6\' >/dev/null','','0',1,''),(50,1,1687687200,1687687200,0,0,0,47,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptEIEsK3\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptEIEsK3\'','','0',1,''),(51,1,1687687200,1687687200,0,0,0,48,'78697','78697','/usr/bin/mozjpeg-cjpeg -tune-hvs-psnr -quality 85 -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageoptZvAeN6\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptZvAeN6\'','','0',1,''),(52,1,1687687200,1687687200,0,0,0,48,'78697','78089','/bin/cp -f \'/var/www/html/.test/v11/var/transient/tx_imageoptZvAeN6\'_tmp \'/var/www/html/.test/v11/var/transient/tx_imageoptZvAeN6\'','','0',1,''),(53,1,1687687200,1687687200,0,0,0,49,'78089','78089','/usr/bin/mozjpeg-jpegtran -copy none -optimize -outfile \'/var/www/html/.test/v11/var/transient/tx_imageopt1DIED4\' \'/var/www/html/.test/v11/var/transient/tx_imageopt1DIED4\'','','0',1,''),(54,1,1687687200,1687687200,0,0,0,50,'78697','59672','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptf4yfu6\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptf4yfu6\'','','0',1,''),(55,1,1687687200,1687687200,0,0,0,51,'23625','23628','/usr/bin/gifsicle --optimize=3 \'/var/www/html/.test/v11/var/transient/tx_imageoptuZFLs5\' -o \'/var/www/html/.test/v11/var/transient/tx_imageoptuZFLs5\'','','0',1,''),(56,1,1687687294,1687687294,0,0,0,52,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptjcqy9R\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptjcqy9R\'','','0',1,''),(57,1,1687687294,1687687294,0,0,0,53,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt40HQvR\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt40HQvR\'','','0',1,''),(58,1,1687687294,1687687294,0,0,0,54,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt7YZeRR\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt7YZeRR\'','','0',1,''),(59,1,1687687294,1687687294,0,0,0,55,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptlyHTAV\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptlyHTAV\'','','0',1,''),(60,1,1687687294,1687687294,0,0,0,56,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptPjVDlT\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptPjVDlT\'','','0',1,''),(61,1,1687687356,1687687356,0,0,0,57,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptutWMPG\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptutWMPG\'','','0',1,''),(62,1,1687687356,1687687356,0,0,0,58,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptzJpYGI\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptzJpYGI\'','','0',1,''),(63,1,1687687356,1687687356,0,0,0,59,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptoWeSXH\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptoWeSXH\'','','0',1,''),(64,1,1687687372,1687687372,0,0,0,60,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptAGAq6H\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptAGAq6H\'','','0',1,''),(65,1,1687687372,1687687372,0,0,0,61,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptJNIkcI\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptJNIkcI\'','','0',1,''),(66,1,1687687372,1687687372,0,0,0,62,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptU0GYgG\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptU0GYgG\'','','0',1,''),(67,1,1687687372,1687687372,0,0,0,63,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptCLEjIG\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptCLEjIG\'','','0',1,''),(68,1,1687687372,1687687372,0,0,0,64,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopth76tgF\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopth76tgF\'','','0',1,''),(69,1,1687687372,1687687372,0,0,0,65,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptSxBS3H\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptSxBS3H\'','','0',1,''),(70,1,1687687372,1687687372,0,0,0,66,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptQ7NGrF\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptQ7NGrF\'','','0',1,''),(71,1,1687687372,1687687372,0,0,0,67,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopthH6ICH\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopthH6ICH\'','','0',1,''),(72,1,1687690227,1687690227,0,0,0,68,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopthq5khB\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopthq5khB\'','','0',1,''),(73,1,1687690227,1687690227,0,0,0,69,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptrkXxSC\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptrkXxSC\'','','0',1,''),(74,1,1687690227,1687690227,0,0,0,70,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptZPxFLy\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptZPxFLy\'','','0',1,''),(75,1,1687690227,1687690227,0,0,0,71,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptJOhTVy\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptJOhTVy\'','','0',1,''),(76,1,1687690227,1687690227,0,0,0,72,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptV15OFz\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptV15OFz\'','','0',1,''),(77,1,1687690227,1687690227,0,0,0,73,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt3xlUuB\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt3xlUuB\'','','0',1,''),(78,1,1687690227,1687690227,0,0,0,74,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptCqSYdB\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptCqSYdB\'','','0',1,''),(79,1,1687690227,1687690227,0,0,0,75,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptaGH4PA\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptaGH4PA\'','','0',1,''),(80,1,1687690253,1687690253,0,0,0,76,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptQvyIeT\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptQvyIeT\'','','0',1,''),(81,1,1687690253,1687690253,0,0,0,77,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptEZqLpP\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptEZqLpP\'','','0',1,''),(82,1,1687690253,1687690253,0,0,0,78,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptY2LaLP\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptY2LaLP\'','','0',1,''),(83,1,1687690253,1687690253,0,0,0,79,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt0YjXlP\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt0YjXlP\'','','0',1,''),(84,1,1687690253,1687690253,0,0,0,80,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptCSeA6R\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptCSeA6R\'','','0',1,''),(85,1,1687690253,1687690253,0,0,0,81,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptjHOrfP\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptjHOrfP\'','','0',1,''),(86,1,1687690253,1687690253,0,0,0,82,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptLEDmgP\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptLEDmgP\'','','0',1,''),(87,1,1687690253,1687690253,0,0,0,83,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptJxBPvT\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptJxBPvT\'','','0',1,''),(88,1,1687693092,1687693092,0,0,0,84,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptR1kfM2\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptR1kfM2\'','','0',1,''),(89,1,1687693092,1687693092,0,0,0,85,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptKyBUG2\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptKyBUG2\'','','0',1,''),(90,1,1687693092,1687693092,0,0,0,86,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptixlNE3\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptixlNE3\'','','0',1,''),(91,1,1687693092,1687693092,0,0,0,87,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptIoBe22\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptIoBe22\'','','0',1,''),(92,1,1687693092,1687693092,0,0,0,88,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptvcMcR5\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptvcMcR5\'','','0',1,''),(93,1,1687693092,1687693092,0,0,0,89,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt2yX7C4\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt2yX7C4\'','','0',1,''),(94,1,1687693092,1687693092,0,0,0,90,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt7XafN6\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt7XafN6\'','','0',1,''),(95,1,1687693092,1687693092,0,0,0,91,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptLyx604\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptLyx604\'','','0',1,''),(96,1,1687693121,1687693121,0,0,0,92,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopto9Cj2B\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopto9Cj2B\'','','0',1,''),(97,1,1687693121,1687693121,0,0,0,93,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptSQLvLz\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptSQLvLz\'','','0',1,''),(98,1,1687693121,1687693121,0,0,0,94,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptWWASmy\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptWWASmy\'','','0',1,''),(99,1,1687693121,1687693121,0,0,0,95,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptOsPpfC\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptOsPpfC\'','','0',1,''),(100,1,1687693121,1687693121,0,0,0,96,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptkD8WQz\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptkD8WQz\'','','0',1,''),(101,1,1687693121,1687693121,0,0,0,97,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptS1SXWA\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptS1SXWA\'','','0',1,''),(102,1,1687693121,1687693121,0,0,0,98,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt0jywZB\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt0jywZB\'','','0',1,''),(103,1,1687693121,1687693121,0,0,0,99,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptOlqqTA\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptOlqqTA\'','','0',1,''),(104,1,1687693150,1687693150,0,0,0,100,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptbjLoPU\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptbjLoPU\'','','0',1,''),(105,1,1687693150,1687693150,0,0,0,101,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptqcZzGU\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptqcZzGU\'','','0',1,''),(106,1,1687693150,1687693150,0,0,0,102,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptbdo7uU\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptbdo7uU\'','','0',1,''),(107,1,1687693150,1687693150,0,0,0,103,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt8vEyKT\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt8vEyKT\'','','0',1,''),(108,1,1687693150,1687693150,0,0,0,104,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptSQypFW\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptSQypFW\'','','0',1,''),(109,1,1687693150,1687693150,0,0,0,105,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptA22NtX\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptA22NtX\'','','0',1,''),(110,1,1687693150,1687693150,0,0,0,106,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptixrnKU\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptixrnKU\'','','0',1,''),(111,1,1687693150,1687693150,0,0,0,107,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptZuiY5W\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptZuiY5W\'','','0',1,''),(112,1,1687693250,1687693250,0,0,0,108,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt8yZcIb\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt8yZcIb\'','','0',1,''),(113,1,1687693250,1687693250,0,0,0,109,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptfF9khb\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptfF9khb\'','','0',1,''),(114,1,1687693250,1687693250,0,0,0,110,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptb7ZoHe\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptb7ZoHe\'','','0',1,''),(115,1,1687693250,1687693250,0,0,0,111,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptEc6LZc\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptEc6LZc\'','','0',1,''),(116,1,1687693250,1687693250,0,0,0,112,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptaxVLPc\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptaxVLPc\'','','0',1,''),(117,1,1687693250,1687693250,0,0,0,113,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptKK5kSb\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptKK5kSb\'','','0',1,''),(118,1,1687693250,1687693250,0,0,0,114,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptaIYure\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptaIYure\'','','0',1,''),(119,1,1687693250,1687693250,0,0,0,115,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptjtG8Pd\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptjtG8Pd\'','','0',1,''),(120,1,1687693381,1687693381,0,0,0,116,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptd6WAdi\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptd6WAdi\'','','0',1,''),(121,1,1687693381,1687693381,0,0,0,117,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptctHURh\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptctHURh\'','','0',1,''),(122,1,1687693381,1687693381,0,0,0,118,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptMsicgg\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptMsicgg\'','','0',1,''),(123,1,1687693381,1687693381,0,0,0,119,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptDrVMyj\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptDrVMyj\'','','0',1,''),(124,1,1687693381,1687693381,0,0,0,120,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptVOSlXf\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptVOSlXf\'','','0',1,''),(125,1,1687693381,1687693381,0,0,0,121,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptscaHQg\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptscaHQg\'','','0',1,''),(126,1,1687693381,1687693381,0,0,0,122,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptxIwDCg\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptxIwDCg\'','','0',1,''),(127,1,1687693381,1687693381,0,0,0,123,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt2PwE8f\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt2PwE8f\'','','0',1,''),(128,1,1687693407,1687693407,0,0,0,124,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptD6WwvH\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptD6WwvH\'','','0',1,''),(129,1,1687693407,1687693407,0,0,0,125,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptyWXR6G\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptyWXR6G\'','','0',1,''),(130,1,1687693407,1687693407,0,0,0,126,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt15RkvI\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt15RkvI\'','','0',1,''),(131,1,1687693407,1687693407,0,0,0,127,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt7cP1jK\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt7cP1jK\'','','0',1,''),(132,1,1687693407,1687693407,0,0,0,128,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptbpR9PG\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptbpR9PG\'','','0',1,''),(133,1,1687693407,1687693407,0,0,0,129,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptusrWVI\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptusrWVI\'','','0',1,''),(134,1,1687693407,1687693407,0,0,0,130,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptj5NPBG\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptj5NPBG\'','','0',1,''),(135,1,1687693407,1687693407,0,0,0,131,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptwKZKkI\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptwKZKkI\'','','0',1,''),(136,1,1687694316,1687694316,0,0,0,132,'153271','48752','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptRDTg9K\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptRDTg9K\'','','0',1,''),(137,1,1687694316,1687694316,0,0,0,133,'50315','38998','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptW7KRMK\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptW7KRMK\'','','0',1,''),(138,1,1687694316,1687694316,0,0,0,134,'3697','1026','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt3LqsqN\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt3LqsqN\'','','0',1,''),(139,1,1687694316,1687694316,0,0,0,135,'26648','8800','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt12CUZL\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt12CUZL\'','','0',1,''),(140,1,1687694316,1687694316,0,0,0,136,'1687','1140','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopthXNEQK\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopthXNEQK\'','','0',1,''),(141,1,1687694316,1687694316,0,0,0,137,'2081','526','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptq0xWKL\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptq0xWKL\'','','0',1,''),(142,1,1687694316,1687694316,0,0,0,138,'15981','5240','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopthjaxWJ\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopthjaxWJ\'','','0',1,''),(143,1,1687694316,1687694316,0,0,0,139,'148159','46848','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptOS5kNM\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptOS5kNM\'','','0',1,''),(144,1,1687694316,1687694316,0,0,0,140,'438728','138936','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt524EhK\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt524EhK\'','','0',1,''),(145,1,1687694316,1687694316,0,0,0,141,'78089','58612','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageopt3QaySN\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageopt3QaySN\'','','0',1,''),(146,1,1687694316,1687694316,0,0,0,142,'23625','15044','/usr/bin/convert -quality 85 \'/var/www/html/.test/v11/var/transient/tx_imageoptBAQCaO\' webp:\'/var/www/html/.test/v11/var/transient/tx_imageoptBAQCaO\'','','0',1,'');
DROP TABLE IF EXISTS `tx_imageopt_domain_model_moderesult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_imageopt_domain_model_moderesult` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `file_absolute_path` text DEFAULT NULL,
  `size_before` varchar(20) NOT NULL DEFAULT '',
  `size_after` varchar(20) NOT NULL DEFAULT '',
  `executed_successfully` smallint(5) unsigned NOT NULL DEFAULT 0,
  `step_results` int(10) unsigned NOT NULL DEFAULT 0,
  `info` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `tx_imageopt_domain_model_moderesult` VALUES (1,1,1687684612,1687684612,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','392600','48838',1,1,''),(2,1,1687684612,1687684612,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','61356','39612',1,1,''),(3,1,1687684612,1687684612,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','6681','1056',1,1,''),(4,1,1687684612,1687684612,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','64218','9100',1,1,''),(5,1,1687684612,1687684612,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1834','1216',1,1,''),(6,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif','2089','2081',1,2,''),(7,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif','15981','15981',1,2,''),(8,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','380384','148159',1,2,''),(9,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','380384','48608',1,1,''),(10,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','1136662','438728',1,2,''),(11,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','1136662','139444',1,1,''),(12,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','268469','78697',1,2,''),(13,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','268469','60548',1,1,''),(14,1,1687686244,1687686244,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','23625',1,2,''),(15,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','392600','153271',1,2,''),(16,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','392600','48838',1,1,''),(17,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','61356','50315',1,2,''),(18,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','61356','39612',1,1,''),(19,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','6681','3697',1,2,''),(20,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','6681','1056',1,1,''),(21,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','64218','26648',1,2,''),(22,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','64218','9100',1,1,''),(23,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1834','1687',1,2,''),(24,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1834','1216',1,1,''),(25,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif','2081','2081',1,2,''),(26,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif','15981','15981',1,2,''),(27,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','148159',1,2,''),(28,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(29,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','438728',1,2,''),(30,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(31,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78697','78089',1,2,''),(32,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78697','59672',1,1,''),(33,1,1687687200,1687687200,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','23625',1,2,''),(34,1,1687687294,1687687294,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(35,1,1687687294,1687687294,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(36,1,1687687294,1687687294,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(37,1,1687687294,1687687294,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(38,1,1687687294,1687687294,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(39,1,1687687356,1687687356,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(40,1,1687687356,1687687356,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(41,1,1687687356,1687687356,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(42,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(43,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(44,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(45,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(46,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(47,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(48,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(49,1,1687687372,1687687372,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(50,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(51,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(52,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(53,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(54,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(55,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(56,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(57,1,1687690227,1687690227,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(58,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(59,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(60,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(61,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(62,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(63,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(64,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(65,1,1687690253,1687690253,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(66,1,1687692424,1687692424,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif\"'),(67,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(68,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(69,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(70,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(71,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(72,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(73,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(74,1,1687693092,1687693092,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(75,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(76,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(77,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(78,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(79,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(80,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(81,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(82,1,1687693121,1687693121,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(83,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(84,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(85,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(86,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(87,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(88,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif','2081','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif\"'),(89,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif','15981','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif\"'),(90,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(91,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(92,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(93,1,1687693150,1687693150,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif\"'),(94,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(95,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(96,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(97,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(98,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(99,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif','2081','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif\"'),(100,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif','15981','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif\"'),(101,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(102,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(103,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(104,1,1687693250,1687693250,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif\"'),(105,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(106,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(107,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(108,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(109,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(110,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif','2081','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif\"'),(111,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif','15981','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif\"'),(112,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(113,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(114,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(115,1,1687693381,1687693381,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif\"'),(116,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(117,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(118,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(119,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(120,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(121,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif','2081','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif\"'),(122,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif','15981','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif\"'),(123,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(124,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(125,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(126,1,1687693407,1687693407,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','',1,0,'This mode do not support the file type of: \"/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif\"'),(127,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_180d0ef4e1.png','153271','48752',1,1,''),(128,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_979fd9509f.jpg','50315','38998',1,1,''),(129,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_85053d5de3.png','3697','1026',1,1,''),(130,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_f3d9f1d607.png','26648','8800',1,1,''),(131,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_c55dd7e11d.jpg','1687','1140',1,1,''),(132,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_f68aa20101.gif','2081','526',1,1,''),(133,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_3c14b6acf3.gif','15981','5240',1,1,''),(134,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_bae0c4fcbe.png','148159','46848',1,1,''),(135,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/2/0/csm_mountains_36392e0347.png','438728','138936',1,1,''),(136,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/1/4/csm_mountains_a851c5db4f.jpg','78089','58612',1,1,''),(137,1,1687694316,1687694316,0,0,0,'/var/www/html/.test/v11/public/fileadmin/_processed_/5/d/csm_mountains_e9d0cf541d.gif','23625','15044',1,1,'');
DROP TABLE IF EXISTS `tx_imageopt_domain_model_providerresult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_imageopt_domain_model_providerresult` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `size_before` varchar(20) NOT NULL DEFAULT '',
  `size_after` varchar(20) NOT NULL DEFAULT '',
  `executed_successfully` smallint(5) unsigned NOT NULL DEFAULT 0,
  `step_result` int(10) unsigned NOT NULL DEFAULT 0,
  `executors_results` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `tx_imageopt_domain_model_providerresult` VALUES (1,1,1687684612,1687684612,0,0,0,'webpImagemagick','392600','48838',1,1,1),(2,1,1687684612,1687684612,0,0,0,'webpImagemagick','61356','39612',1,2,1),(3,1,1687684612,1687684612,0,0,0,'webpImagemagick','6681','1056',1,3,1),(4,1,1687684612,1687684612,0,0,0,'webpImagemagick','64218','9100',1,4,1),(5,1,1687684612,1687684612,0,0,0,'webpImagemagick','1834','1216',1,5,1),(6,1,1687686244,1687686244,0,0,0,'gifsicle','2089','2081',1,7,1),(7,1,1687686244,1687686244,0,0,0,'gifsicle','15981','15988',1,9,1),(8,1,1687686244,1687686244,0,0,0,'pngquant','380384','153003',1,10,1),(9,1,1687686244,1687686244,0,0,0,'optipng','153003','148159',1,11,1),(10,1,1687686244,1687686244,0,0,0,'pngcrush','153003','149343',1,11,1),(11,1,1687686244,1687686244,0,0,0,'webpImagemagick','380384','48608',1,12,1),(12,1,1687686244,1687686244,0,0,0,'pngquant','1136662','458871',1,13,1),(13,1,1687686244,1687686244,0,0,0,'optipng','458871','438728',1,14,1),(14,1,1687686244,1687686244,0,0,0,'pngcrush','458871','439041',1,14,1),(15,1,1687686244,1687686244,0,0,0,'webpImagemagick','1136662','139444',1,15,1),(16,1,1687686244,1687686244,0,0,0,'mozjpeg','268469','78697',1,16,2),(17,1,1687686244,1687686244,0,0,0,'jpegtranMozjpeg','78697','78697',1,17,1),(18,1,1687686244,1687686244,0,0,0,'webpImagemagick','268469','60548',1,18,1),(19,1,1687686244,1687686244,0,0,0,'gifsicle','23625','23628',1,20,1),(20,1,1687687200,1687687200,0,0,0,'pngquant','392600','158550',1,21,1),(21,1,1687687200,1687687200,0,0,0,'optipng','158550','153271',1,22,1),(22,1,1687687200,1687687200,0,0,0,'pngcrush','158550','154317',1,22,1),(23,1,1687687200,1687687200,0,0,0,'webpImagemagick','392600','48838',1,23,1),(24,1,1687687200,1687687200,0,0,0,'mozjpeg','61356','50315',1,24,2),(25,1,1687687200,1687687200,0,0,0,'jpegtranMozjpeg','50315','50315',1,25,1),(26,1,1687687200,1687687200,0,0,0,'webpImagemagick','61356','39612',1,26,1),(27,1,1687687200,1687687200,0,0,0,'pngquant','6681','3708',1,27,1),(28,1,1687687200,1687687200,0,0,0,'optipng','3708','3697',1,28,1),(29,1,1687687200,1687687200,0,0,0,'pngcrush','3708','3697',1,28,1),(30,1,1687687200,1687687200,0,0,0,'webpImagemagick','6681','1056',1,29,1),(31,1,1687687200,1687687200,0,0,0,'pngquant','64218','26684',1,30,1),(32,1,1687687200,1687687200,0,0,0,'optipng','26684','26648',1,31,1),(33,1,1687687200,1687687200,0,0,0,'pngcrush','26684','27713',1,31,1),(34,1,1687687200,1687687200,0,0,0,'webpImagemagick','64218','9100',1,32,1),(35,1,1687687200,1687687200,0,0,0,'mozjpeg','1834','1687',1,33,2),(36,1,1687687200,1687687200,0,0,0,'jpegtranMozjpeg','1687','1687',1,34,1),(37,1,1687687200,1687687200,0,0,0,'webpImagemagick','1834','1216',1,35,1),(38,1,1687687200,1687687200,0,0,0,'gifsicle','2081','2081',1,37,1),(39,1,1687687200,1687687200,0,0,0,'gifsicle','15981','15988',1,39,1),(40,1,1687687200,1687687200,0,0,0,'pngquant','148159','152978',1,40,1),(41,1,1687687200,1687687200,0,0,0,'optipng','148159','148159',1,41,1),(42,1,1687687200,1687687200,0,0,0,'pngcrush','148159','149343',1,41,1),(43,1,1687687200,1687687200,0,0,0,'webpImagemagick','148159','46848',1,42,1),(44,1,1687687200,1687687200,0,0,0,'pngquant','438728','458834',1,43,1),(45,1,1687687200,1687687200,0,0,0,'optipng','438728','438728',1,44,1),(46,1,1687687200,1687687200,0,0,0,'pngcrush','438728','439041',1,44,1),(47,1,1687687200,1687687200,0,0,0,'webpImagemagick','438728','138936',1,45,1),(48,1,1687687200,1687687200,0,0,0,'mozjpeg','78697','78089',1,46,2),(49,1,1687687200,1687687200,0,0,0,'jpegtranMozjpeg','78089','78089',1,47,1),(50,1,1687687200,1687687200,0,0,0,'webpImagemagick','78697','59672',1,48,1),(51,1,1687687200,1687687200,0,0,0,'gifsicle','23625','23628',1,50,1),(52,1,1687687294,1687687294,0,0,0,'webpImagemagick','153271','48752',1,51,1),(53,1,1687687294,1687687294,0,0,0,'webpImagemagick','50315','38998',1,52,1),(54,1,1687687294,1687687294,0,0,0,'webpImagemagick','3697','1026',1,53,1),(55,1,1687687294,1687687294,0,0,0,'webpImagemagick','26648','8800',1,54,1),(56,1,1687687294,1687687294,0,0,0,'webpImagemagick','1687','1140',1,55,1),(57,1,1687687356,1687687356,0,0,0,'webpImagemagick','148159','46848',1,56,1),(58,1,1687687356,1687687356,0,0,0,'webpImagemagick','438728','138936',1,57,1),(59,1,1687687356,1687687356,0,0,0,'webpImagemagick','78089','58612',1,58,1),(60,1,1687687372,1687687372,0,0,0,'webpImagemagick','153271','48752',1,59,1),(61,1,1687687372,1687687372,0,0,0,'webpImagemagick','50315','38998',1,60,1),(62,1,1687687372,1687687372,0,0,0,'webpImagemagick','3697','1026',1,61,1),(63,1,1687687372,1687687372,0,0,0,'webpImagemagick','26648','8800',1,62,1),(64,1,1687687372,1687687372,0,0,0,'webpImagemagick','1687','1140',1,63,1),(65,1,1687687372,1687687372,0,0,0,'webpImagemagick','148159','46848',1,64,1),(66,1,1687687372,1687687372,0,0,0,'webpImagemagick','438728','138936',1,65,1),(67,1,1687687372,1687687372,0,0,0,'webpImagemagick','78089','58612',1,66,1),(68,1,1687690227,1687690227,0,0,0,'webpImagemagick','153271','48752',1,67,1),(69,1,1687690227,1687690227,0,0,0,'webpImagemagick','50315','38998',1,68,1),(70,1,1687690227,1687690227,0,0,0,'webpImagemagick','3697','1026',1,69,1),(71,1,1687690227,1687690227,0,0,0,'webpImagemagick','26648','8800',1,70,1),(72,1,1687690227,1687690227,0,0,0,'webpImagemagick','1687','1140',1,71,1),(73,1,1687690227,1687690227,0,0,0,'webpImagemagick','148159','46848',1,72,1),(74,1,1687690227,1687690227,0,0,0,'webpImagemagick','438728','138936',1,73,1),(75,1,1687690227,1687690227,0,0,0,'webpImagemagick','78089','58612',1,74,1),(76,1,1687690253,1687690253,0,0,0,'webpImagemagick','153271','48752',1,75,1),(77,1,1687690253,1687690253,0,0,0,'webpImagemagick','50315','38998',1,76,1),(78,1,1687690253,1687690253,0,0,0,'webpImagemagick','3697','1026',1,77,1),(79,1,1687690253,1687690253,0,0,0,'webpImagemagick','26648','8800',1,78,1),(80,1,1687690253,1687690253,0,0,0,'webpImagemagick','1687','1140',1,79,1),(81,1,1687690253,1687690253,0,0,0,'webpImagemagick','148159','46848',1,80,1),(82,1,1687690253,1687690253,0,0,0,'webpImagemagick','438728','138936',1,81,1),(83,1,1687690253,1687690253,0,0,0,'webpImagemagick','78089','58612',1,82,1),(84,1,1687693092,1687693092,0,0,0,'webpImagemagick','153271','48752',1,83,1),(85,1,1687693092,1687693092,0,0,0,'webpImagemagick','50315','38998',1,84,1),(86,1,1687693092,1687693092,0,0,0,'webpImagemagick','3697','1026',1,85,1),(87,1,1687693092,1687693092,0,0,0,'webpImagemagick','26648','8800',1,86,1),(88,1,1687693092,1687693092,0,0,0,'webpImagemagick','1687','1140',1,87,1),(89,1,1687693092,1687693092,0,0,0,'webpImagemagick','148159','46848',1,88,1),(90,1,1687693092,1687693092,0,0,0,'webpImagemagick','438728','138936',1,89,1),(91,1,1687693092,1687693092,0,0,0,'webpImagemagick','78089','58612',1,90,1),(92,1,1687693121,1687693121,0,0,0,'webpImagemagick','153271','48752',1,91,1),(93,1,1687693121,1687693121,0,0,0,'webpImagemagick','50315','38998',1,92,1),(94,1,1687693121,1687693121,0,0,0,'webpImagemagick','3697','1026',1,93,1),(95,1,1687693121,1687693121,0,0,0,'webpImagemagick','26648','8800',1,94,1),(96,1,1687693121,1687693121,0,0,0,'webpImagemagick','1687','1140',1,95,1),(97,1,1687693121,1687693121,0,0,0,'webpImagemagick','148159','46848',1,96,1),(98,1,1687693121,1687693121,0,0,0,'webpImagemagick','438728','138936',1,97,1),(99,1,1687693121,1687693121,0,0,0,'webpImagemagick','78089','58612',1,98,1),(100,1,1687693150,1687693150,0,0,0,'webpImagemagick','153271','48752',1,99,1),(101,1,1687693150,1687693150,0,0,0,'webpImagemagick','50315','38998',1,100,1),(102,1,1687693150,1687693150,0,0,0,'webpImagemagick','3697','1026',1,101,1),(103,1,1687693150,1687693150,0,0,0,'webpImagemagick','26648','8800',1,102,1),(104,1,1687693150,1687693150,0,0,0,'webpImagemagick','1687','1140',1,103,1),(105,1,1687693150,1687693150,0,0,0,'webpImagemagick','148159','46848',1,104,1),(106,1,1687693150,1687693150,0,0,0,'webpImagemagick','438728','138936',1,105,1),(107,1,1687693150,1687693150,0,0,0,'webpImagemagick','78089','58612',1,106,1),(108,1,1687693250,1687693250,0,0,0,'webpImagemagick','153271','48752',1,107,1),(109,1,1687693250,1687693250,0,0,0,'webpImagemagick','50315','38998',1,108,1),(110,1,1687693250,1687693250,0,0,0,'webpImagemagick','3697','1026',1,109,1),(111,1,1687693250,1687693250,0,0,0,'webpImagemagick','26648','8800',1,110,1),(112,1,1687693250,1687693250,0,0,0,'webpImagemagick','1687','1140',1,111,1),(113,1,1687693250,1687693250,0,0,0,'webpImagemagick','148159','46848',1,112,1),(114,1,1687693250,1687693250,0,0,0,'webpImagemagick','438728','138936',1,113,1),(115,1,1687693250,1687693250,0,0,0,'webpImagemagick','78089','58612',1,114,1),(116,1,1687693381,1687693381,0,0,0,'webpImagemagick','153271','48752',1,115,1),(117,1,1687693381,1687693381,0,0,0,'webpImagemagick','50315','38998',1,116,1),(118,1,1687693381,1687693381,0,0,0,'webpImagemagick','3697','1026',1,117,1),(119,1,1687693381,1687693381,0,0,0,'webpImagemagick','26648','8800',1,118,1),(120,1,1687693381,1687693381,0,0,0,'webpImagemagick','1687','1140',1,119,1),(121,1,1687693381,1687693381,0,0,0,'webpImagemagick','148159','46848',1,120,1),(122,1,1687693381,1687693381,0,0,0,'webpImagemagick','438728','138936',1,121,1),(123,1,1687693381,1687693381,0,0,0,'webpImagemagick','78089','58612',1,122,1),(124,1,1687693407,1687693407,0,0,0,'webpImagemagick','153271','48752',1,123,1),(125,1,1687693407,1687693407,0,0,0,'webpImagemagick','50315','38998',1,124,1),(126,1,1687693407,1687693407,0,0,0,'webpImagemagick','3697','1026',1,125,1),(127,1,1687693407,1687693407,0,0,0,'webpImagemagick','26648','8800',1,126,1),(128,1,1687693407,1687693407,0,0,0,'webpImagemagick','1687','1140',1,127,1),(129,1,1687693407,1687693407,0,0,0,'webpImagemagick','148159','46848',1,128,1),(130,1,1687693407,1687693407,0,0,0,'webpImagemagick','438728','138936',1,129,1),(131,1,1687693407,1687693407,0,0,0,'webpImagemagick','78089','58612',1,130,1),(132,1,1687694316,1687694316,0,0,0,'webpImagemagick','153271','48752',1,131,1),(133,1,1687694316,1687694316,0,0,0,'webpImagemagick','50315','38998',1,132,1),(134,1,1687694316,1687694316,0,0,0,'webpImagemagick','3697','1026',1,133,1),(135,1,1687694316,1687694316,0,0,0,'webpImagemagick','26648','8800',1,134,1),(136,1,1687694316,1687694316,0,0,0,'webpImagemagick','1687','1140',1,135,1),(137,1,1687694316,1687694316,0,0,0,'webpImagemagick','2081','526',1,136,1),(138,1,1687694316,1687694316,0,0,0,'webpImagemagick','15981','5240',1,137,1),(139,1,1687694316,1687694316,0,0,0,'webpImagemagick','148159','46848',1,138,1),(140,1,1687694316,1687694316,0,0,0,'webpImagemagick','438728','138936',1,139,1),(141,1,1687694316,1687694316,0,0,0,'webpImagemagick','78089','58612',1,140,1),(142,1,1687694316,1687694316,0,0,0,'webpImagemagick','23625','15044',1,141,1);
DROP TABLE IF EXISTS `tx_imageopt_domain_model_stepresult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_imageopt_domain_model_stepresult` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `size_before` varchar(20) NOT NULL DEFAULT '',
  `size_after` varchar(20) NOT NULL DEFAULT '',
  `provider_winner_name` varchar(255) NOT NULL DEFAULT '',
  `executed_successfully` smallint(5) unsigned NOT NULL DEFAULT 0,
  `mode_result` int(10) unsigned NOT NULL DEFAULT 0,
  `providers_results` int(10) unsigned NOT NULL DEFAULT 0,
  `info` text DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `tx_imageopt_domain_model_stepresult` VALUES (1,1,1687684612,1687684612,0,0,0,'10','Webp convert','392600','48838','webpImagemagick',1,1,1,'Winner is webpImagemagick with optimized image smaller by: 87.56%'),(2,1,1687684612,1687684612,0,0,0,'10','Webp convert','61356','39612','webpImagemagick',1,2,1,'Winner is webpImagemagick with optimized image smaller by: 35.44%'),(3,1,1687684612,1687684612,0,0,0,'10','Webp convert','6681','1056','webpImagemagick',1,3,1,'Winner is webpImagemagick with optimized image smaller by: 84.19%'),(4,1,1687684612,1687684612,0,0,0,'10','Webp convert','64218','9100','webpImagemagick',1,4,1,'Winner is webpImagemagick with optimized image smaller by: 85.83%'),(5,1,1687684612,1687684612,0,0,0,'10','Webp convert','1834','1216','webpImagemagick',1,5,1,'Winner is webpImagemagick with optimized image smaller by: 33.7%'),(6,1,1687686244,1687686244,0,0,0,'10','Lossy, good quality image optimisation.','2089','2089','',1,6,0,'No providers enabled (or defined).'),(7,1,1687686244,1687686244,0,0,0,'20','Lossless image optimisation.','2089','2081','gifsicle',1,6,1,'Winner is gifsicle with optimized image smaller by: 0.38%'),(8,1,1687686244,1687686244,0,0,0,'10','Lossy, good quality image optimisation.','15981','15981','',1,7,0,'No providers enabled (or defined).'),(9,1,1687686244,1687686244,0,0,0,'20','Lossless image optimisation.','15981','15981','',1,7,1,'No winner of this step. Non of the optimized images were smaller than original.'),(10,1,1687686244,1687686244,0,0,0,'10','Lossy, good quality image optimisation.','380384','153003','pngquant',1,8,1,'Winner is pngquant with optimized image smaller by: 59.78%'),(11,1,1687686244,1687686244,0,0,0,'20','Lossless image optimisation.','153003','148159','optipng',1,8,2,'Winner is optipng with optimized image smaller by: 3.17%'),(12,1,1687686244,1687686244,0,0,0,'10','Webp convert','380384','48608','webpImagemagick',1,9,1,'Winner is webpImagemagick with optimized image smaller by: 87.22%'),(13,1,1687686244,1687686244,0,0,0,'10','Lossy, good quality image optimisation.','1136662','458871','pngquant',1,10,1,'Winner is pngquant with optimized image smaller by: 59.63%'),(14,1,1687686244,1687686244,0,0,0,'20','Lossless image optimisation.','458871','438728','optipng',1,10,2,'Winner is optipng with optimized image smaller by: 4.39%'),(15,1,1687686244,1687686244,0,0,0,'10','Webp convert','1136662','139444','webpImagemagick',1,11,1,'Winner is webpImagemagick with optimized image smaller by: 87.73%'),(16,1,1687686244,1687686244,0,0,0,'10','Lossy, good quality image optimisation.','268469','78697','mozjpeg',1,12,1,'Winner is mozjpeg with optimized image smaller by: 70.69%'),(17,1,1687686244,1687686244,0,0,0,'20','Lossless image optimisation.','78697','78697','',1,12,1,'No winner of this step. Non of the optimized images were smaller than original.'),(18,1,1687686244,1687686244,0,0,0,'10','Webp convert','268469','60548','webpImagemagick',1,13,1,'Winner is webpImagemagick with optimized image smaller by: 77.45%'),(19,1,1687686244,1687686244,0,0,0,'10','Lossy, good quality image optimisation.','23625','23625','',1,14,0,'No providers enabled (or defined).'),(20,1,1687686244,1687686244,0,0,0,'20','Lossless image optimisation.','23625','23625','',1,14,1,'No winner of this step. Non of the optimized images were smaller than original.'),(21,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','392600','158550','pngquant',1,15,1,'Winner is pngquant with optimized image smaller by: 59.62%'),(22,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','158550','153271','optipng',1,15,2,'Winner is optipng with optimized image smaller by: 3.33%'),(23,1,1687687200,1687687200,0,0,0,'10','Webp convert','392600','48838','webpImagemagick',1,16,1,'Winner is webpImagemagick with optimized image smaller by: 87.56%'),(24,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','61356','50315','mozjpeg',1,17,1,'Winner is mozjpeg with optimized image smaller by: 17.99%'),(25,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','50315','50315','',1,17,1,'No winner of this step. Non of the optimized images were smaller than original.'),(26,1,1687687200,1687687200,0,0,0,'10','Webp convert','61356','39612','webpImagemagick',1,18,1,'Winner is webpImagemagick with optimized image smaller by: 35.44%'),(27,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','6681','3708','pngquant',1,19,1,'Winner is pngquant with optimized image smaller by: 44.5%'),(28,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','3708','3697','optipng',1,19,2,'Winner is optipng with optimized image smaller by: 0.3%'),(29,1,1687687200,1687687200,0,0,0,'10','Webp convert','6681','1056','webpImagemagick',1,20,1,'Winner is webpImagemagick with optimized image smaller by: 84.19%'),(30,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','64218','26684','pngquant',1,21,1,'Winner is pngquant with optimized image smaller by: 58.45%'),(31,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','26684','26648','optipng',1,21,2,'Winner is optipng with optimized image smaller by: 0.13%'),(32,1,1687687200,1687687200,0,0,0,'10','Webp convert','64218','9100','webpImagemagick',1,22,1,'Winner is webpImagemagick with optimized image smaller by: 85.83%'),(33,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','1834','1687','mozjpeg',1,23,1,'Winner is mozjpeg with optimized image smaller by: 8.02%'),(34,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','1687','1687','',1,23,1,'No winner of this step. Non of the optimized images were smaller than original.'),(35,1,1687687200,1687687200,0,0,0,'10','Webp convert','1834','1216','webpImagemagick',1,24,1,'Winner is webpImagemagick with optimized image smaller by: 33.7%'),(36,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','2081','2081','',1,25,0,'No providers enabled (or defined).'),(37,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','2081','2081','',1,25,1,'No winner of this step. Non of the optimized images were smaller than original.'),(38,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','15981','15981','',1,26,0,'No providers enabled (or defined).'),(39,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','15981','15981','',1,26,1,'No winner of this step. Non of the optimized images were smaller than original.'),(40,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','148159','148159','',1,27,1,'No winner of this step. Non of the optimized images were smaller than original.'),(41,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','148159','148159','',1,27,2,'No winner of this step. Non of the optimized images were smaller than original.'),(42,1,1687687200,1687687200,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,28,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(43,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','438728','438728','',1,29,1,'No winner of this step. Non of the optimized images were smaller than original.'),(44,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','438728','438728','',1,29,2,'No winner of this step. Non of the optimized images were smaller than original.'),(45,1,1687687200,1687687200,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,30,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(46,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','78697','78089','mozjpeg',1,31,1,'Winner is mozjpeg with optimized image smaller by: 0.77%'),(47,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','78089','78089','',1,31,1,'No winner of this step. Non of the optimized images were smaller than original.'),(48,1,1687687200,1687687200,0,0,0,'10','Webp convert','78697','59672','webpImagemagick',1,32,1,'Winner is webpImagemagick with optimized image smaller by: 24.18%'),(49,1,1687687200,1687687200,0,0,0,'10','Lossy, good quality image optimisation.','23625','23625','',1,33,0,'No providers enabled (or defined).'),(50,1,1687687200,1687687200,0,0,0,'20','Lossless image optimisation.','23625','23625','',1,33,1,'No winner of this step. Non of the optimized images were smaller than original.'),(51,1,1687687294,1687687294,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,34,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(52,1,1687687294,1687687294,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,35,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(53,1,1687687294,1687687294,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,36,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(54,1,1687687294,1687687294,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,37,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(55,1,1687687294,1687687294,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,38,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(56,1,1687687356,1687687356,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,39,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(57,1,1687687356,1687687356,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,40,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(58,1,1687687356,1687687356,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,41,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(59,1,1687687372,1687687372,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,42,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(60,1,1687687372,1687687372,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,43,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(61,1,1687687372,1687687372,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,44,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(62,1,1687687372,1687687372,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,45,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(63,1,1687687372,1687687372,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,46,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(64,1,1687687372,1687687372,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,47,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(65,1,1687687372,1687687372,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,48,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(66,1,1687687372,1687687372,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,49,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(67,1,1687690227,1687690227,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,50,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(68,1,1687690227,1687690227,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,51,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(69,1,1687690227,1687690227,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,52,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(70,1,1687690227,1687690227,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,53,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(71,1,1687690227,1687690227,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,54,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(72,1,1687690227,1687690227,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,55,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(73,1,1687690227,1687690227,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,56,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(74,1,1687690227,1687690227,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,57,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(75,1,1687690253,1687690253,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,58,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(76,1,1687690253,1687690253,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,59,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(77,1,1687690253,1687690253,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,60,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(78,1,1687690253,1687690253,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,61,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(79,1,1687690253,1687690253,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,62,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(80,1,1687690253,1687690253,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,63,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(81,1,1687690253,1687690253,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,64,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(82,1,1687690253,1687690253,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,65,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(83,1,1687693092,1687693092,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,67,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(84,1,1687693092,1687693092,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,68,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(85,1,1687693092,1687693092,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,69,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(86,1,1687693092,1687693092,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,70,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(87,1,1687693092,1687693092,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,71,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(88,1,1687693092,1687693092,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,72,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(89,1,1687693092,1687693092,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,73,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(90,1,1687693092,1687693092,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,74,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(91,1,1687693121,1687693121,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,75,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(92,1,1687693121,1687693121,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,76,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(93,1,1687693121,1687693121,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,77,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(94,1,1687693121,1687693121,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,78,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(95,1,1687693121,1687693121,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,79,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(96,1,1687693121,1687693121,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,80,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(97,1,1687693121,1687693121,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,81,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(98,1,1687693121,1687693121,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,82,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(99,1,1687693150,1687693150,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,83,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(100,1,1687693150,1687693150,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,84,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(101,1,1687693150,1687693150,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,85,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(102,1,1687693150,1687693150,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,86,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(103,1,1687693150,1687693150,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,87,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(104,1,1687693150,1687693150,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,90,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(105,1,1687693150,1687693150,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,91,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(106,1,1687693150,1687693150,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,92,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(107,1,1687693250,1687693250,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,94,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(108,1,1687693250,1687693250,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,95,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(109,1,1687693250,1687693250,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,96,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(110,1,1687693250,1687693250,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,97,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(111,1,1687693250,1687693250,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,98,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(112,1,1687693250,1687693250,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,101,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(113,1,1687693250,1687693250,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,102,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(114,1,1687693250,1687693250,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,103,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(115,1,1687693381,1687693381,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,105,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(116,1,1687693381,1687693381,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,106,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(117,1,1687693381,1687693381,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,107,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(118,1,1687693381,1687693381,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,108,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(119,1,1687693381,1687693381,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,109,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(120,1,1687693381,1687693381,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,112,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(121,1,1687693381,1687693381,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,113,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(122,1,1687693381,1687693381,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,114,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(123,1,1687693407,1687693407,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,116,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(124,1,1687693407,1687693407,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,117,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(125,1,1687693407,1687693407,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,118,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(126,1,1687693407,1687693407,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,119,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(127,1,1687693407,1687693407,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,120,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(128,1,1687693407,1687693407,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,123,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(129,1,1687693407,1687693407,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,124,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(130,1,1687693407,1687693407,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,125,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(131,1,1687694316,1687694316,0,0,0,'10','Webp convert','153271','48752','webpImagemagick',1,127,1,'Winner is webpImagemagick with optimized image smaller by: 68.19%'),(132,1,1687694316,1687694316,0,0,0,'10','Webp convert','50315','38998','webpImagemagick',1,128,1,'Winner is webpImagemagick with optimized image smaller by: 22.49%'),(133,1,1687694316,1687694316,0,0,0,'10','Webp convert','3697','1026','webpImagemagick',1,129,1,'Winner is webpImagemagick with optimized image smaller by: 72.25%'),(134,1,1687694316,1687694316,0,0,0,'10','Webp convert','26648','8800','webpImagemagick',1,130,1,'Winner is webpImagemagick with optimized image smaller by: 66.98%'),(135,1,1687694316,1687694316,0,0,0,'10','Webp convert','1687','1140','webpImagemagick',1,131,1,'Winner is webpImagemagick with optimized image smaller by: 32.42%'),(136,1,1687694316,1687694316,0,0,0,'10','Webp convert','2081','526','webpImagemagick',1,132,1,'Winner is webpImagemagick with optimized image smaller by: 74.72%'),(137,1,1687694316,1687694316,0,0,0,'10','Webp convert','15981','5240','webpImagemagick',1,133,1,'Winner is webpImagemagick with optimized image smaller by: 67.21%'),(138,1,1687694316,1687694316,0,0,0,'10','Webp convert','148159','46848','webpImagemagick',1,134,1,'Winner is webpImagemagick with optimized image smaller by: 68.38%'),(139,1,1687694316,1687694316,0,0,0,'10','Webp convert','438728','138936','webpImagemagick',1,135,1,'Winner is webpImagemagick with optimized image smaller by: 68.33%'),(140,1,1687694316,1687694316,0,0,0,'10','Webp convert','78089','58612','webpImagemagick',1,136,1,'Winner is webpImagemagick with optimized image smaller by: 24.94%'),(141,1,1687694316,1687694316,0,0,0,'10','Webp convert','23625','15044','webpImagemagick',1,137,1,'Winner is webpImagemagick with optimized image smaller by: 36.32%');
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

