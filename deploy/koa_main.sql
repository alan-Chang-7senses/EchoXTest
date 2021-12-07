-- --------------------------------------------------------
-- 主機:                           192.168.3.243
-- 伺服器版本:                        10.6.5-MariaDB-1:10.6.5+maria~focal - mariadb.org binary distribution
-- 伺服器作業系統:                      debian-linux-gnu
-- HeidiSQL 版本:                  11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- 傾印 koa_main 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_main` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_main`;

-- 傾印  資料表 koa_main.CharacterCounts 結構
CREATE TABLE IF NOT EXISTS `CharacterCounts` (
  `CharacterID` bigint(20) unsigned NOT NULL,
  `PVPPlay` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 比賽次數',
  `PVPFirst` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 第一名次數',
  `PVPTopThree` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 前三名次數',
  `PVPMiddle` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 中間名次次數',
  `PVPLastThree` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 後三名次數',
  `TeamPlay` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '團體戰次數',
  `TeamWin` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '團體戰優勝次數',
  PRIMARY KEY (`CharacterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色計量數值';

-- 正在傾印表格  koa_main.CharacterCounts 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `CharacterCounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `CharacterCounts` ENABLE KEYS */;

-- 傾印  資料表 koa_main.CharacterHolder 結構
CREATE TABLE IF NOT EXISTS `CharacterHolder` (
  `CharacterID` bigint(20) unsigned NOT NULL,
  `UserID` int(10) unsigned NOT NULL DEFAULT 0,
  `Nickname` varchar(50) NOT NULL COMMENT '角色暱稱',
  `SyncRate` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '同步率',
  PRIMARY KEY (`CharacterID`),
  UNIQUE KEY `CharacterID_UserID` (`CharacterID`,`UserID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色持有資訊';

-- 正在傾印表格  koa_main.CharacterHolder 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `CharacterHolder` DISABLE KEYS */;
/*!40000 ALTER TABLE `CharacterHolder` ENABLE KEYS */;

-- 傾印  資料表 koa_main.CharacterLevel 結構
CREATE TABLE IF NOT EXISTS `CharacterLevel` (
  `CharacterID` bigint(20) unsigned NOT NULL,
  `Level` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '等級',
  `Rank` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '階級',
  `Exp` bigint(20) unsigned NOT NULL COMMENT '經驗值',
  `SlotNumber` bigint(20) unsigned NOT NULL DEFAULT 4 COMMENT '技能插槽數量',
  PRIMARY KEY (`CharacterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色養成數值';

-- 正在傾印表格  koa_main.CharacterLevel 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `CharacterLevel` DISABLE KEYS */;
/*!40000 ALTER TABLE `CharacterLevel` ENABLE KEYS */;

-- 傾印  資料表 koa_main.CharacterNFT 結構
CREATE TABLE IF NOT EXISTS `CharacterNFT` (
  `CharacterID` bigint(20) unsigned NOT NULL,
  `UserID` int(10) unsigned NOT NULL DEFAULT 0,
  `Constitution` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '體力',
  `Strength` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '力量',
  `Dexterity` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '技巧',
  `Agility` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '敏捷',
  `Attribute` tinyint(3) unsigned NOT NULL DEFAULT 100 COMMENT '屬性',
  `HeadDNA` varchar(50) NOT NULL COMMENT '頭部 DNA 編碼',
  `BodyDNA` varchar(50) NOT NULL COMMENT '身體 DNA 編碼',
  `HandDNA` varchar(50) NOT NULL COMMENT '手部 DNA 編碼',
  `LegDNA` varchar(50) NOT NULL COMMENT '腿部 DNA 編碼',
  `BackDNA` varchar(50) NOT NULL COMMENT '背脊 DNA 編碼',
  `HatDNA` varchar(50) NOT NULL COMMENT '頭冠 DNA 編碼',
  PRIMARY KEY (`CharacterID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='來自 NFT 角色資訊';

-- 正在傾印表格  koa_main.CharacterNFT 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `CharacterNFT` DISABLE KEYS */;
/*!40000 ALTER TABLE `CharacterNFT` ENABLE KEYS */;

-- 傾印  資料表 koa_main.CharacterSkill 結構
CREATE TABLE IF NOT EXISTS `CharacterSkill` (
  `CharacterID` bigint(20) unsigned NOT NULL,
  `SkillID` bigint(20) unsigned NOT NULL,
  `Level` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '等級',
  PRIMARY KEY (`CharacterID`,`SkillID`),
  KEY `CharacterID` (`CharacterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色技能等級\r\n只記錄該角色所具備的技能';

-- 正在傾印表格  koa_main.CharacterSkill 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `CharacterSkill` DISABLE KEYS */;
/*!40000 ALTER TABLE `CharacterSkill` ENABLE KEYS */;

-- 傾印  資料表 koa_main.Configs 結構
CREATE TABLE IF NOT EXISTS `Configs` (
  `Name` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  PRIMARY KEY (`Name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='雜項設置';

-- 正在傾印表格  koa_main.Configs 的資料：~1 rows (近似值)
/*!40000 ALTER TABLE `Configs` DISABLE KEYS */;
INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES
	('AmountRoomPeopleMax', '8', '開房最大人數');
/*!40000 ALTER TABLE `Configs` ENABLE KEYS */;

-- 傾印  資料表 koa_main.DatabaseInfo 結構
CREATE TABLE IF NOT EXISTS `DatabaseInfo` (
  `Label` varchar(255) NOT NULL COMMENT '標記代號',
  `Host` varchar(255) NOT NULL COMMENT '主機名稱或IP',
  `Username` varchar(255) NOT NULL COMMENT '帳號',
  `Password` varchar(255) NOT NULL COMMENT '密碼',
  `Name` varchar(255) NOT NULL COMMENT '資料庫名稱',
  `Port` smallint(5) unsigned DEFAULT 3306,
  PRIMARY KEY (`Label`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='資料庫連線資訊';

-- 正在傾印表格  koa_main.DatabaseInfo 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `DatabaseInfo` DISABLE KEYS */;
INSERT INTO `DatabaseInfo` (`Label`, `Host`, `Username`, `Password`, `Name`, `Port`) VALUES
	('KoaMain', '192.168.3.243', 'root', '1111', 'koa_main', 37002),
	('Test', '192.168.2.119', 'root', '1111', 'koa_main', 37004);
/*!40000 ALTER TABLE `DatabaseInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_main.Sessions 結構
CREATE TABLE IF NOT EXISTS `Sessions` (
  `SessionID` varchar(255) NOT NULL,
  `SessionExpires` int(10) unsigned NOT NULL DEFAULT 0,
  `SessionData` text DEFAULT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`SessionID`),
  KEY `SessionExpires` (`SessionExpires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 正在傾印表格  koa_main.Sessions 的資料：~3 rows (近似值)
/*!40000 ALTER TABLE `Sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `Sessions` ENABLE KEYS */;

-- 傾印  資料表 koa_main.Users 結構
CREATE TABLE IF NOT EXISTS `Users` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Status` tinyint(4) DEFAULT 1 COMMENT '狀態(1=啟用)',
  `Username` varchar(255) NOT NULL COMMENT '帳號',
  `Nickname` varchar(255) NOT NULL COMMENT '暱稱',
  `Password` varchar(255) NOT NULL COMMENT '密碼',
  `Level` smallint(5) unsigned NOT NULL DEFAULT 1 COMMENT '等級',
  `Exp` bigint(20) unsigned NOT NULL COMMENT '經驗值',
  `Vitality` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '體力',
  `Money` bigint(20) NOT NULL DEFAULT 0 COMMENT '金錢',
  `CreateTime` int(11) NOT NULL COMMENT '建立時間',
  `UpdateTime` int(11) NOT NULL COMMENT '更新時間',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者資料';

-- 正在傾印表格  koa_main.Users 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
