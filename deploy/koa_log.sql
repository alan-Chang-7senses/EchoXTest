-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.8.3-MariaDB-1:10.8.3+maria~jammy - mariadb.org binary distribution
-- 伺服器作業系統:                      debian-linux-gnu
-- HeidiSQL 版本:                  11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- 傾印 koa_log 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_log` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_log`;

-- 傾印  資料表 koa_log.BaseProcess 結構
CREATE TABLE IF NOT EXISTS `BaseProcess` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(10) unsigned NOT NULL COMMENT '使用者ID',
  `UserIP` varchar(255) NOT NULL DEFAULT '' COMMENT '使用者IP',
  `RedirectURL` varchar(255) NOT NULL COMMENT '執行網址',
  `Content` longtext NOT NULL COMMENT '內容',
  `Result` tinyint(4) NOT NULL DEFAULT 0 COMMENT '處理結果(1=成功)',
  `ResultData` longtext DEFAULT NULL COMMENT '處理結果資料',
  `HttpCode` varchar(5) DEFAULT NULL COMMENT 'HTTP回應狀態碼',
  `Message` text DEFAULT NULL COMMENT '處理結果訊息',
  `BeginTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '起始時間',
  `RecordTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`,`RecordTime`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='基礎處理 log';

-- 傾印  資料表 koa_log.PowerLog 結構
CREATE TABLE IF NOT EXISTS `PowerLog` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者ID',
  `BeforeChange` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '變化前電力',
  `AfterChange` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '變化後電力',
  `Cause` int(11) NOT NULL DEFAULT 0 COMMENT '使用原由',
  `PVELevel` int(11) DEFAULT NULL COMMENT '在哪個關卡使用',
  `LogTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`) USING BTREE,
  KEY `UserID` (`UserID`),
  KEY `Cause` (`Cause`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='電力消耗log';

-- 傾印  資料表 koa_log.PVECleared 結構
CREATE TABLE IF NOT EXISTS `PVECleared` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT 0,
  `PlayerID` int(11) NOT NULL DEFAULT 0,
  `LevelID` int(11) NOT NULL DEFAULT 0,
  `Items` text NOT NULL DEFAULT '',
  `SyncRate` int(11) NOT NULL DEFAULT 0,
  `Ranking` tinyint(4) NOT NULL DEFAULT 0,
  `ClearCount` smallint(6) NOT NULL DEFAULT 0,
  `StartTime` int(11) NOT NULL DEFAULT 0,
  `FinishTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PVE通關紀錄。';

-- 傾印  資料表 koa_log.SeasonRankingReward 結構
CREATE TABLE IF NOT EXISTS `SeasonRankingReward` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `SeasonID` int(11) NOT NULL COMMENT '賽季編號',
  `Lobby` tinyint(4) NOT NULL DEFAULT 0 COMMENT '賽制大廳',
  `Ranking` mediumint(9) NOT NULL DEFAULT 0 COMMENT '排名',
  `UserID` int(10) NOT NULL DEFAULT 0,
  `PlayerID` bigint(20) NOT NULL,
  `Content` text NOT NULL COMMENT '內容',
  `LogTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`,`LogTime`),
  KEY `SeasonID` (`SeasonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='賽季排名獎勵紀錄';

-- 傾印  資料表 koa_log.Upgrade 結構
CREATE TABLE IF NOT EXISTS `Upgrade` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `PlayerID` bigint(20) NOT NULL DEFAULT 0,
  `SkillID` int(11) DEFAULT NULL COMMENT '技能ID',
  `SkillLevelDelta` int(11) NOT NULL DEFAULT 0 COMMENT '技能等級變化量',
  `CoinDelta` int(11) NOT NULL DEFAULT 0 COMMENT '金幣變化量',
  `BonusType` int(11) DEFAULT NULL COMMENT '強化加成類型',
  `ExpDelta` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色經驗值變化量',
  `RankDelta` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色階級變化量',
  `LogTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`,`LogTime`),
  KEY `PlayerID` (`PlayerID`),
  KEY `SkillID` (`SkillID`),
  KEY `RankDelta` (`RankDelta`),
  KEY `ExpDelta` (`ExpDelta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='升級、升階、升技能LOG';

-- 傾印  資料表 koa_log.UserItemsLog 結構
CREATE TABLE IF NOT EXISTS `UserItemsLog` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserItemID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者物品編號',
  `UserID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '物品編號',
  `Cause` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '原因',
  `Action` tinyint(4) NOT NULL DEFAULT 0 COMMENT '動作',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '數量',
  `Remain` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '剩餘數量',
  `LogTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`,`LogTime`),
  KEY `UserItemID` (`UserItemID`),
  KEY `UserID` (`UserID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者物品紀錄';

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
