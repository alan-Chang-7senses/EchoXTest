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


-- 傾印 koa_static 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_static` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_static`;

-- 傾印  資料表 koa_static.SceneClimate 結構
CREATE TABLE IF NOT EXISTS `SceneClimate` (
  `SceneClimateID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SceneID` int(10) unsigned NOT NULL DEFAULT 0,
  `Weather` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '天氣',
  `WindDirection` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '風向',
  `WindSpeed` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '風速',
  `StartTime` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT '起始時間（當日秒數）',
  `Lighting` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '明暗（日照與背光）',
  PRIMARY KEY (`SceneClimateID`),
  KEY `SceneID` (`SceneID`),
  KEY `StartTime` (`StartTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='場景氣候';

-- 正在傾印表格  koa_static.SceneClimate 的資料：~3 rows (近似值)
/*!40000 ALTER TABLE `SceneClimate` DISABLE KEYS */;
INSERT INTO `SceneClimate` (`SceneClimateID`, `SceneID`, `Weather`, `WindDirection`, `WindSpeed`, `StartTime`, `Lighting`) VALUES
	(1, 1, 0, 0, 100, 0, 1),
	(2, 1, 0, 1, 100, 28800, 0),
	(3, 1, 0, 2, 100, 64800, 1);
/*!40000 ALTER TABLE `SceneClimate` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SceneInfo 結構
CREATE TABLE IF NOT EXISTS `SceneInfo` (
  `SceneID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SceneName` varchar(50) NOT NULL DEFAULT '' COMMENT '場景代號（名稱）',
  `ReadyToStart` tinyint(3) unsigned NOT NULL DEFAULT 7 COMMENT '起跑準備（秒）',
  `SceneEnv` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '場景環境',
  PRIMARY KEY (`SceneID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='場景主要資訊';

-- 正在傾印表格  koa_static.SceneInfo 的資料：~1 rows (近似值)
/*!40000 ALTER TABLE `SceneInfo` DISABLE KEYS */;
INSERT INTO `SceneInfo` (`SceneID`, `SceneName`, `ReadyToStart`, `SceneEnv`) VALUES
	(1, 'CloseBeta', 7, 0);
/*!40000 ALTER TABLE `SceneInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.ScenePitStop 結構
CREATE TABLE IF NOT EXISTS `ScenePitStop` (
  `ScenePitStopID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SceneID` int(10) unsigned NOT NULL DEFAULT 0,
  `SortOrder` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '排列順序',
  `Length` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '長度（秒）',
  PRIMARY KEY (`ScenePitStopID`),
  UNIQUE KEY `SceneID_SortOrder` (`SceneID`,`SortOrder`),
  KEY `SceneID` (`SceneID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='場景休息站';

-- 正在傾印表格  koa_static.ScenePitStop 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `ScenePitStop` DISABLE KEYS */;
INSERT INTO `ScenePitStop` (`ScenePitStopID`, `SceneID`, `SortOrder`, `Length`) VALUES
	(1, 1, 0, 7),
	(2, 1, 1, 7);
/*!40000 ALTER TABLE `ScenePitStop` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SceneTracks 結構
CREATE TABLE IF NOT EXISTS `SceneTracks` (
  `SceneTrackID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SceneID` int(10) unsigned NOT NULL DEFAULT 0,
  `SortOrder` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '排列順序',
  `TrackType` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '賽道類別',
  `Step` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '段位',
  `Length` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '長度',
  `Shape` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '賽道形狀',
  `Direction` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '方向',
  PRIMARY KEY (`SceneTrackID`),
  UNIQUE KEY `SceneID_SortOrder` (`SceneID`,`SortOrder`),
  KEY `SceneID` (`SceneID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='場景賽道';

-- 正在傾印表格  koa_static.SceneTracks 的資料：~6 rows (近似值)
/*!40000 ALTER TABLE `SceneTracks` DISABLE KEYS */;
INSERT INTO `SceneTracks` (`SceneTrackID`, `SceneID`, `SortOrder`, `TrackType`, `Step`, `Length`, `Shape`, `Direction`) VALUES
	(1, 1, 0, 2, 0, 300, 0, 2),
	(2, 1, 1, 0, 0, 400, 1, 1),
	(3, 1, 2, 1, 1, 300, 0, 0),
	(4, 1, 3, 2, 1, 300, 0, 0),
	(5, 1, 4, 0, 2, 400, 1, 3),
	(6, 1, 5, 1, 2, 300, 0, 2);
/*!40000 ALTER TABLE `SceneTracks` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
