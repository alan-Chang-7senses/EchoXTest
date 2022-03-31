-- --------------------------------------------------------
-- 主機:                           192.168.3.8
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


-- 傾印 koa_elitetest 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_elitetest` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_elitetest`;

-- 傾印  資料表 koa_elitetest.RacePlayer 結構
CREATE TABLE IF NOT EXISTS `RacePlayer` (
  `RaceID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '競賽編號',
  `UserID` int(10) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `TrackOrder` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '賽道順序',
  `Ranking` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '排名',
  `Duration` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '完賽區間時間',
  `FinishS` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '結束S值',
  `FinishH` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '結束H值',
  PRIMARY KEY (`RaceID`,`UserID`),
  KEY `RaceID` (`RaceID`),
  KEY `Duration` (`Duration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='競賽角色';

-- 傾印  資料表 koa_elitetest.Races 結構
CREATE TABLE IF NOT EXISTS `Races` (
  `RaceID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '狀態',
  `CreateTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '建立時間',
  `FinishTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '結束時間',
  `Duration` decimal(20,6) DEFAULT NULL COMMENT '完賽間隔時間',
  PRIMARY KEY (`RaceID`) USING BTREE,
  KEY `Status` (`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='競賽資訊';

-- 傾印  資料表 koa_elitetest.RaceSkills 結構
CREATE TABLE IF NOT EXISTS `RaceSkills` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `RaceID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '競賽編號',
  `UserID` int(10) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `SkillID` varchar(50) NOT NULL DEFAULT '0' COMMENT '技能識別ID',
  `Position` varchar(50) NOT NULL DEFAULT '0' COMMENT '發動絕對位置',
  `TrackType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '賽道類別',
  `TrackShape` tinyint(4) NOT NULL DEFAULT 0 COMMENT '賽道形狀',
  `BeforeS` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '發動前S值',
  `BeforeH` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '發動前H值',
  `BeforeEnergy` varchar(50) NOT NULL DEFAULT '0' COMMENT '發動前能量',
  `AfterS` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '發動後S值',
  `AfterH` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '發動後H值',
  `AfterEnergy` varchar(50) NOT NULL DEFAULT '0' COMMENT '發動後能量',
  PRIMARY KEY (`Serial`),
  KEY `RaceID` (`RaceID`),
  KEY `UserID` (`UserID`),
  KEY `SkillID` (`SkillID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='競賽技能紀錄';

-- 傾印  資料表 koa_elitetest.TotalLoginHours 結構
CREATE TABLE IF NOT EXISTS `TotalLoginHours` (
  `Hours` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '小時',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '數量',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`Hours`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='登入時間總計量';

-- 正在傾印表格  koa_elitetest.TotalLoginHours 的資料：~24 rows (近似值)
/*!40000 ALTER TABLE `TotalLoginHours` DISABLE KEYS */;
INSERT INTO `TotalLoginHours` (`Hours`, `Amount`, `UpdateTime`) VALUES
	(0, 0, 0),
	(1, 0, 0),
	(2, 0, 0),
	(3, 0, 0),
	(4, 0, 0),
	(5, 0, 0),
	(6, 0, 0),
	(7, 0, 0),
	(8, 0, 0),
	(9, 0, 0),
	(10, 0, 0),
	(11, 0, 0),
	(12, 0, 0),
	(13, 0, 0),
	(14, 0, 0),
	(15, 0, 0),
	(16, 0, 0),
	(17, 0, 0),
	(18, 0, 0),
	(19, 0, 0),
	(20, 0, 0),
	(21, 0, 0),
	(22, 0, 0),
	(23, 0, 0);
/*!40000 ALTER TABLE `TotalLoginHours` ENABLE KEYS */;

-- 傾印  資料表 koa_elitetest.TotalRaceBeginHours 結構
CREATE TABLE IF NOT EXISTS `TotalRaceBeginHours` (
  `Hours` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '小時',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '數量',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`Hours`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='登入時間總計量';

-- 正在傾印表格  koa_elitetest.TotalRaceBeginHours 的資料：~24 rows (近似值)
/*!40000 ALTER TABLE `TotalRaceBeginHours` DISABLE KEYS */;
INSERT INTO `TotalRaceBeginHours` (`Hours`, `Amount`, `UpdateTime`) VALUES
	(0, 0, 0),
	(1, 0, 0),
	(2, 0, 0),
	(3, 0, 0),
	(4, 0, 0),
	(5, 0, 0),
	(6, 0, 0),
	(7, 0, 0),
	(8, 0, 0),
	(9, 0, 0),
	(10, 0, 0),
	(11, 0, 0),
	(12, 0, 0),
	(13, 0, 0),
	(14, 0, 0),
	(15, 0, 0),
	(16, 0, 0),
	(17, 0, 0),
	(18, 0, 0),
	(19, 0, 0),
	(20, 0, 0),
	(21, 0, 0),
	(22, 0, 0),
	(23, 0, 0);
/*!40000 ALTER TABLE `TotalRaceBeginHours` ENABLE KEYS */;

-- 傾印  資料表 koa_elitetest.TotalUserRace 結構
CREATE TABLE IF NOT EXISTS `TotalUserRace` (
  `UserID` int(10) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `BeginAmount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '起始次數',
  `FinishAmount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '完賽次數',
  `Win` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '獲勝次數',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`UserID`),
  KEY `BeginAmount` (`BeginAmount`),
  KEY `FinishAmount` (`FinishAmount`),
  KEY `Win` (`Win`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者參賽總量';

-- 傾印  資料表 koa_elitetest.UserLogin 結構
CREATE TABLE IF NOT EXISTS `UserLogin` (
  `Serial` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者ID',
  `UserIP` varchar(50) NOT NULL DEFAULT '' COMMENT '使用者IP',
  `RecordTime` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者登入紀錄';

-- 傾印  資料表 koa_elitetest.Users 結構
CREATE TABLE IF NOT EXISTS `Users` (
  `UserID` int(10) NOT NULL AUTO_INCREMENT,
  `Status` tinyint(4) DEFAULT 1 COMMENT '狀態(1=啟用)',
  `Username` varchar(255) NOT NULL COMMENT '帳號',
  `Password` varchar(255) DEFAULT NULL COMMENT '密碼',
  `Race` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '當前競賽',
  `Score` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '累積分數',
  `CreateTime` int(11) NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(11) NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`),
  KEY `Race` (`Race`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者資料';

-- 正在傾印表格  koa_elitetest.Users 的資料：~10 rows (近似值)
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Password`, `Race`, `Score`, `CreateTime`, `UpdateTime`) VALUES
	(1, 1, 'test0001', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(2, 1, 'test0002', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(3, 1, 'test0003', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(4, 1, 'test0004', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(5, 1, 'test0005', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(6, 1, 'test0006', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(7, 1, 'test0007', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(8, 1, 'test0008', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(9, 1, 'test0009', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(10, 1, 'test0010', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(11, 1, 'test0011', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(12, 1, 'test0012', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(13, 1, 'test0013', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(14, 1, 'test0014', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(15, 1, 'test0015', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(16, 1, 'test0016', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(17, 1, 'test0017', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(18, 1, 'test0018', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(19, 1, 'test0019', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(20, 1, 'test0020', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(21, 1, 'test0021', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(22, 1, 'test0022', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(23, 1, 'test0023', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(24, 1, 'test0024', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(25, 1, 'test0025', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(26, 1, 'test0026', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(27, 1, 'test0027', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(28, 1, 'test0028', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(29, 1, 'test0029', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(30, 1, 'test0030', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(31, 1, 'test0031', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(32, 1, 'test0032', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(33, 1, 'test0033', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(34, 1, 'test0034', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(35, 1, 'test0035', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(36, 1, 'test0036', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(37, 1, 'test0037', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(38, 1, 'test0038', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(39, 1, 'test0039', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(40, 1, 'test0040', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(41, 1, 'test0041', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(42, 1, 'test0042', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(43, 1, 'test0043', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(44, 1, 'test0044', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(45, 1, 'test0045', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(46, 1, 'test0046', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(47, 1, 'test0047', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(48, 1, 'test0048', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(49, 1, 'test0049', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(50, 1, 'test0050', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(51, 1, 'test0051', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(52, 1, 'test0052', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(53, 1, 'test0053', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(54, 1, 'test0054', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(55, 1, 'test0055', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(56, 1, 'test0056', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(57, 1, 'test0057', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(58, 1, 'test0058', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(59, 1, 'test0059', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(60, 1, 'test0060', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(61, 1, 'test0061', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(62, 1, 'test0062', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(63, 1, 'test0063', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(64, 1, 'test0064', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(65, 1, 'test0065', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(66, 1, 'test0066', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(67, 1, 'test0067', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(68, 1, 'test0068', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(69, 1, 'test0069', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(70, 1, 'test0070', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(71, 1, 'test0071', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(72, 1, 'test0072', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(73, 1, 'test0073', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(74, 1, 'test0074', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(75, 1, 'test0075', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(76, 1, 'test0076', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(77, 1, 'test0077', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(78, 1, 'test0078', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(79, 1, 'test0079', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(80, 1, 'test0080', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(81, 1, 'test0081', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(82, 1, 'test0082', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(83, 1, 'test0083', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(84, 1, 'test0084', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(85, 1, 'test0085', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(86, 1, 'test0086', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(87, 1, 'test0087', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(88, 1, 'test0088', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(89, 1, 'test0089', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(90, 1, 'test0090', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(91, 1, 'test0091', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(92, 1, 'test0092', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(93, 1, 'test0093', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(94, 1, 'test0094', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(95, 1, 'test0095', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(96, 1, 'test0096', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(97, 1, 'test0097', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(98, 1, 'test0098', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(99, 1, 'test0099', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0),
	(100, 1, 'test0100', '$2y$10$elorX60dGEdj50HVHxJqE.aigfqxUu86tPKCCYmDyIdWoDHUL3JVy', 0, 0, UNIX_TIMESTAMP(), 0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
