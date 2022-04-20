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

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
