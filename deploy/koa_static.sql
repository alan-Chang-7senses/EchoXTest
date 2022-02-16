-- --------------------------------------------------------
-- 主機:                           192.168.1.103
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
	(1, 1, 1, 1, 100, 0, 2),
	(2, 1, 1, 2, 100, 28800, 1),
	(3, 1, 1, 3, 100, 64800, 2);
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
	(1, 'CloseBeta', 7, 1);
/*!40000 ALTER TABLE `SceneInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillEffect 結構
CREATE TABLE IF NOT EXISTS `SkillEffect` (
  `SkillEffectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EffectName` varchar(50) DEFAULT NULL COMMENT '效果名稱或標籤',
  `EffectType` smallint(6) NOT NULL DEFAULT 0 COMMENT '效果類型',
  `Target` tinyint(4) NOT NULL DEFAULT 0 COMMENT '作用對象',
  `Duration` tinyint(4) NOT NULL DEFAULT 0 COMMENT '時效性',
  `Formula` text DEFAULT NULL COMMENT '公式',
  PRIMARY KEY (`SkillEffectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能效果表';

-- 正在傾印表格  koa_static.SkillEffect 的資料：~5 rows (近似值)
/*!40000 ALTER TABLE `SkillEffect` DISABLE KEYS */;
INSERT INTO `SkillEffect` (`SkillEffectID`, `EffectName`, `EffectType`, `Target`, `Duration`, `Formula`) VALUES
	(1, 'effect1', 101, 0, -1, 'H-H*N%'),
	(2, 'effect2', 102, 0, -1, 'S+S*N%'),
	(3, 'effect3', 103, 0, 0, 'HP+HP*N%'),
	(4, 'effect4', 201, 0, -1, 'SPD+SPD*N%'),
	(5, 'effect5', 102, 0, -1, 'SPD+N');
/*!40000 ALTER TABLE `SkillEffect` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillInfo 結構
CREATE TABLE IF NOT EXISTS `SkillInfo` (
  `SkillID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AliasCode` varchar(50) DEFAULT NULL COMMENT '識別碼(企劃用)',
  `SkillName` varchar(50) DEFAULT NULL COMMENT '技能名稱標籤',
  `Description` varchar(50) DEFAULT NULL COMMENT '技能描述標籤',
  `TriggerType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '觸發類型',
  `EnergyCondition` varchar(50) DEFAULT NULL COMMENT '能量條件',
  `Effect` varchar(50) DEFAULT NULL COMMENT '效果',
  `Cooldown` smallint(6) NOT NULL DEFAULT 100 COMMENT '冷卻時間',
  `Level1` int(11) NOT NULL DEFAULT 0 COMMENT '1級N值',
  `Level2` int(11) NOT NULL DEFAULT 0 COMMENT '2級N值',
  `Level3` int(11) NOT NULL DEFAULT 0 COMMENT '3級N值',
  `Level4` int(11) NOT NULL DEFAULT 0 COMMENT '4級N值',
  `Level5` int(11) NOT NULL DEFAULT 0 COMMENT '5級N值',
  `MaxDescription` varchar(50) DEFAULT NULL COMMENT '滿等級敘述標籤',
  `MaxCondition` tinyint(4) NOT NULL DEFAULT 0 COMMENT '滿等級技能條件',
  `MaxConditionValue` tinyint(4) NOT NULL DEFAULT 0 COMMENT '滿等級技能條件值',
  `MaxEffect` varchar(50) DEFAULT NULL COMMENT '滿等技能效果',
  PRIMARY KEY (`SkillID`),
  UNIQUE KEY `AliasCode` (`AliasCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能資訊表';

-- 正在傾印表格  koa_static.SkillInfo 的資料：~6 rows (近似值)
/*!40000 ALTER TABLE `SkillInfo` DISABLE KEYS */;
INSERT INTO `SkillInfo` (`SkillID`, `AliasCode`, `SkillName`, `Description`, `TriggerType`, `EnergyCondition`, `Effect`, `Cooldown`, `Level1`, `Level2`, `Level3`, `Level4`, `Level5`, `MaxDescription`, `MaxCondition`, `MaxConditionValue`, `MaxEffect`) VALUES
	(1, 'Test001', '21001', '22002', 1, '111', '2', 600, 5, 10, 15, 20, 25, '23001', 11, 0, '1'),
	(2, 'Test002', '21002', '22001', 1, '1234', '1', 800, 10, 20, 30, 40, 50, '23002', 22, 0, '2'),
	(3, 'Test003', '21003', '22002', 1, '111', '2', 600, 5, 10, 15, 20, 25, '23003', 31, 0, '3'),
	(4, 'Test004', '21004', '22003', 1, '23', '3', 400, 2, 5, 7, 10, 12, '23004', 41, 0, '4'),
	(5, 'Test005', '21005', '22004', 1, '4', '4', 200, 1, 2, 3, 4, 5, '23005', 11, 0, '5'),
	(6, 'Test006', '21006', '22005', 1, '112234', '5', 1200, 10, 15, 20, 25, 30, '23006', 51, 0, '5');
/*!40000 ALTER TABLE `SkillInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillMaxEffect 結構
CREATE TABLE IF NOT EXISTS `SkillMaxEffect` (
  `MaxEffectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EffectName` varchar(50) DEFAULT NULL COMMENT '效果名稱或標籤',
  `EffectType` smallint(6) NOT NULL DEFAULT 0 COMMENT '效果類型',
  `TypeValue` tinyint(4) NOT NULL DEFAULT 0 COMMENT '效果類型值',
  `Formula` text DEFAULT NULL COMMENT '公式(或值)',
  PRIMARY KEY (`MaxEffectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='滿等級技能效果表';

-- 正在傾印表格  koa_static.SkillMaxEffect 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `SkillMaxEffect` DISABLE KEYS */;
INSERT INTO `SkillMaxEffect` (`MaxEffectID`, `EffectName`, `EffectType`, `TypeValue`, `Formula`) VALUES
	(1, 'Max001', 1, 1, NULL),
	(2, 'Max002', 12, 1, 'Climate+20'),
	(3, 'Max003', 10, 1, 'Env+20'),
	(4, 'Max004', 103, 0, 'HP+HP*10%'),
	(5, 'Max005', 402, 0, 'S+S*10%'),
	(6, 'Max006', 402, 0, 'S+S*20%');
/*!40000 ALTER TABLE `SkillMaxEffect` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillPart 結構
CREATE TABLE IF NOT EXISTS `SkillPart` (
  `SkillPartID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PartCode` varchar(50) DEFAULT NULL COMMENT '部位外觀碼',
  `PartType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '部位',
  `AliasCode1` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `AliasCode2` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `AliasCode3` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  PRIMARY KEY (`SkillPartID`),
  UNIQUE KEY `PartCode_PartType` (`PartCode`,`PartType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='部位技能對照表';

-- 正在傾印表格  koa_static.SkillPart 的資料：~6 rows (近似值)
/*!40000 ALTER TABLE `SkillPart` DISABLE KEYS */;
INSERT INTO `SkillPart` (`SkillPartID`, `PartCode`, `PartType`, `AliasCode1`, `AliasCode2`, `AliasCode3`) VALUES
	(1, '110104', 1, 'Origin00001', 'Origin00002', NULL),
	(2, '110101', 2, 'Origin00001', 'Origin00001', NULL),
	(3, '110101', 3, 'Origin00001', 'Origin00001', 'Origin00001'),
	(4, '110101', 4, 'Origin00001', 'Origin00001', 'Origin00001'),
	(5, '110101', 5, 'Origin00001', 'Origin00001', 'Origin00001'),
	(6, '110101', 6, 'Origin00001', 'Origin00001', NULL);
/*!40000 ALTER TABLE `SkillPart` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
