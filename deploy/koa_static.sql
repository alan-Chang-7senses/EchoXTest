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

-- 傾印  資料表 koa_static.SkillAffixAlias 結構
CREATE TABLE IF NOT EXISTS `SkillAffixAlias` (
  `SkillAffixID` int(11) unsigned NOT NULL,
  `Level1` varchar(50) DEFAULT NULL COMMENT 'Level1 AliasCode',
  `Level2` varchar(50) DEFAULT NULL COMMENT 'Level2 AliasCode',
  `Level3` varchar(50) DEFAULT NULL COMMENT 'Level3 AliasCode',
  `Level4` varchar(50) DEFAULT NULL COMMENT 'Level4 AliasCode',
  `Level5` varchar(50) DEFAULT NULL COMMENT 'Level5 AliasCode',
  `Level6` varchar(50) DEFAULT NULL COMMENT 'Level6 AliasCode'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='詞綴技能識別碼';

-- 正在傾印表格  koa_static.SkillAffixAlias 的資料：~5 rows (近似值)
/*!40000 ALTER TABLE `SkillAffixAlias` DISABLE KEYS */;
INSERT INTO `SkillAffixAlias` (`SkillAffixID`, `Level1`, `Level2`, `Level3`, `Level4`, `Level5`, `Level6`) VALUES
	(1, NULL, NULL, 'Norn_3', 'Norn_4', 'Norn_5', 'Norn_6'),
	(2, NULL, NULL, 'Bil_3', 'Bil_4', 'Bil_5', 'Bil_6'),
	(3, NULL, NULL, 'Demeter_3', 'Demeter_4', 'Demeter_5', 'Demeter_6'),
	(4, NULL, NULL, 'Artemis_3', 'Artemis_4', 'Artemis_5', 'Artemis_6'),
	(5, NULL, NULL, 'Eir_3', 'Eir_4', 'Eir_5', 'Eir_6');
/*!40000 ALTER TABLE `SkillAffixAlias` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillEffect 結構
CREATE TABLE IF NOT EXISTS `SkillEffect` (
  `SkillEffectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EffectName` varchar(50) DEFAULT NULL COMMENT '效果名稱或標籤',
  `EffectType` smallint(6) NOT NULL DEFAULT 0 COMMENT '效果類型',
  `Duration` smallint(6) NOT NULL DEFAULT 0 COMMENT '時效性',
  `Formula` text DEFAULT NULL COMMENT '公式',
  PRIMARY KEY (`SkillEffectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能效果表';

-- 正在傾印表格  koa_static.SkillEffect 的資料：~70 rows (近似值)
/*!40000 ALTER TABLE `SkillEffect` DISABLE KEYS */;
INSERT INTO `SkillEffect` (`SkillEffectID`, `EffectName`, `EffectType`, `Duration`, `Formula`) VALUES
	(1, 'effect1', 101, -1, 'H-H*N%'),
	(2, 'effect2', 102, -1, 'S+S*N%'),
	(3, 'effect3', 103, 0, 'HP+HP*N%'),
	(4, 'effect4', 201, -1, 'SPD+SPD*N%'),
	(5, 'effect5', 201, -1, 'SPD+N'),
	(20100, 'Spd_up_00', 201, -1, 'SPD+SPD*N%'),
	(20101, 'Spd_up_01', 201, 150, 'SPD+SPD*N%'),
	(20102, 'Spd_up_02', 201, 330, 'SPD+SPD*N%'),
	(20103, 'Spd_up_03', 201, 540, 'SPD+SPD*N%'),
	(20104, 'Spd_up_04', 201, 780, 'SPD+SPD*N%'),
	(20105, 'Spd_up_05', 201, 1050, 'SPD+SPD*N%'),
	(20106, 'Spd_up_06', 201, 1350, 'SPD+SPD*N%'),
	(20111, 'Spd_up_01', 201, 225, 'SPD+SPD*N%'),
	(20112, 'Spd_up_02', 201, 495, 'SPD+SPD*N%'),
	(20113, 'Spd_up_03', 201, 810, 'SPD+SPD*N%'),
	(20114, 'Spd_up_04', 201, 1170, 'SPD+SPD*N%'),
	(20115, 'Spd_up_05', 201, 1575, 'SPD+SPD*N%'),
	(20116, 'Spd_up_06', 201, 2026, 'SPD+SPD*N%'),
	(20200, 'Pow_up_00', 202, -1, 'POW+POW*N%'),
	(20201, 'Pow_up_01', 202, 150, 'POW+POW*N%'),
	(20202, 'Pow_up_02', 202, 330, 'POW+POW*N%'),
	(20203, 'Pow_up_03', 202, 540, 'POW+POW*N%'),
	(20204, 'Pow_up_04', 202, 780, 'POW+POW*N%'),
	(20205, 'Pow_up_05', 202, 1050, 'POW+POW*N%'),
	(20206, 'Pow_up_06', 202, 1350, 'POW+POW*N%'),
	(20211, 'Pow_up_01', 202, 225, 'POW+POW*N%'),
	(20212, 'Pow_up_02', 202, 495, 'POW+POW*N%'),
	(20213, 'Pow_up_03', 202, 810, 'POW+POW*N%'),
	(20214, 'Pow_up_04', 202, 1170, 'POW+POW*N%'),
	(20215, 'Pow_up_05', 202, 1575, 'POW+POW*N%'),
	(20216, 'Pow_up_06', 202, 2026, 'POW+POW*N%'),
	(20300, 'Fig_up_00', 203, -1, 'FIG+FIG*N%'),
	(20301, 'Fig_up_01', 203, 150, 'FIG+FIG*N%'),
	(20302, 'Fig_up_02', 203, 330, 'FIG+FIG*N%'),
	(20303, 'Fig_up_03', 203, 540, 'FIG+FIG*N%'),
	(20304, 'Fig_up_04', 203, 780, 'FIG+FIG*N%'),
	(20305, 'Fig_up_05', 203, 1050, 'FIG+FIG*N%'),
	(20306, 'Fig_up_06', 203, 1350, 'FIG+FIG*N%'),
	(20311, 'Fig_up_01', 203, 225, 'FIG+FIG*N%'),
	(20312, 'Fig_up_02', 203, 495, 'FIG+FIG*N%'),
	(20313, 'Fig_up_03', 203, 810, 'FIG+FIG*N%'),
	(20314, 'Fig_up_04', 203, 1170, 'FIG+FIG*N%'),
	(20315, 'Fig_up_05', 203, 1575, 'FIG+FIG*N%'),
	(20316, 'Fig_up_06', 203, 2026, 'FIG+FIG*N%'),
	(20400, 'int_up_00', 204, -1, 'INT+INT*N%'),
	(20401, 'int_up_01', 204, 150, 'INT+INT*N%'),
	(20402, 'int_up_02', 204, 330, 'INT+INT*N%'),
	(20403, 'int_up_03', 204, 540, 'INT+INT*N%'),
	(20404, 'int_up_04', 204, 780, 'INT+INT*N%'),
	(20405, 'int_up_05', 204, 1050, 'INT+INT*N%'),
	(20406, 'int_up_06', 204, 1350, 'INT+INT*N%'),
	(20411, 'int_up_01', 204, 225, 'INT+INT*N%'),
	(20412, 'int_up_02', 204, 495, 'INT+INT*N%'),
	(20413, 'int_up_03', 204, 810, 'INT+INT*N%'),
	(20414, 'int_up_04', 204, 1170, 'INT+INT*N%'),
	(20415, 'int_up_05', 204, 1575, 'INT+INT*N%'),
	(20416, 'int_up_06', 204, 2026, 'INT+INT*N%'),
	(20500, 'Sta_up_00', 205, -1, 'STA+STA*N%'),
	(20501, 'Sta_up_01', 205, 150, 'STA+STA*N%'),
	(20502, 'Sta_up_02', 205, 330, 'STA+STA*N%'),
	(20503, 'Sta_up_03', 205, 540, 'STA+STA*N%'),
	(20504, 'Sta_up_04', 205, 780, 'STA+STA*N%'),
	(20505, 'Sta_up_05', 205, 1050, 'STA+STA*N%'),
	(20506, 'Sta_up_06', 205, 1350, 'STA+STA*N%'),
	(20511, 'Sta_up_01', 205, 225, 'STA+STA*N%'),
	(20512, 'Sta_up_02', 205, 495, 'STA+STA*N%'),
	(20513, 'Sta_up_03', 205, 810, 'STA+STA*N%'),
	(20514, 'Sta_up_04', 205, 1170, 'STA+STA*N%'),
	(20515, 'Sta_up_05', 205, 1575, 'STA+STA*N%'),
	(20516, 'Sta_up_06', 205, 2026, 'STA+STA*N%');
/*!40000 ALTER TABLE `SkillEffect` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillInfo 結構
CREATE TABLE IF NOT EXISTS `SkillInfo` (
  `SkillID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AliasCode` varchar(50) DEFAULT NULL COMMENT '識別碼(企劃用)',
  `SkillName` varchar(50) DEFAULT NULL COMMENT '技能名稱標籤',
  `Description` varchar(50) DEFAULT NULL COMMENT '技能描述標籤',
  `Energy` varchar(50) DEFAULT NULL COMMENT '能量條件 紅,黃,藍,綠',
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

-- 正在傾印表格  koa_static.SkillInfo 的資料：~56 rows (近似值)
/*!40000 ALTER TABLE `SkillInfo` DISABLE KEYS */;
INSERT INTO `SkillInfo` (`SkillID`, `AliasCode`, `SkillName`, `Description`, `Energy`, `Effect`, `Cooldown`, `Level1`, `Level2`, `Level3`, `Level4`, `Level5`, `MaxDescription`, `MaxCondition`, `MaxConditionValue`, `MaxEffect`) VALUES
	(1, 'Test001', '21001', '22002', '0,0,2,1', '2', 600, 5, 10, 15, 20, 25, '23001', 11, 0, '1'),
	(2, 'Test002', '21002', '22001', '1,1,1,1', '1', 800, 10, 20, 30, 40, 50, '23002', 22, 0, '2'),
	(3, 'Test003', '21003', '22002', '3,0,0,0', '2', 600, 5, 10, 15, 20, 25, '23003', 31, 0, '3'),
	(4, 'Test004', '21004', '22003', '0,1,1,0', '3', 400, 2, 5, 7, 10, 12, '23004', 41, 0, '4'),
	(5, 'Test005', '21005', '22004', '0,0,0,1', '4', 200, 1, 2, 3, 4, 5, '23005', 12, 0, '5'),
	(6, 'Test006', '21006', '22005', '2,2,1,1', '5', 1200, 10, 15, 20, 25, 30, '23006', 51, 0, '5'),
	(7, 'Lion011', '21001', '22204', '1,1,1,0', '20403', 300, 5, 10, 15, 20, 25, '23007', 2, 3, '7'),
	(8, 'Lion012', '21002', '22205', '2,0,1,1', '20504', 400, 5, 10, 15, 20, 25, '23008', 4, 1, '8'),
	(9, 'Lion013', '21003', '22202', '0,1,1,1', '20203', 300, 5, 10, 15, 20, 25, '23011', 1, 1, '11'),
	(10, 'Lion014', '21004', '22201', '0,1,1,2', '20104', 400, 5, 10, 15, 20, 25, '23012', 41, 0, '12'),
	(11, 'Lion015', '21005', '22203', '1,0,1,1', '20303', 300, 5, 10, 15, 20, 25, '23012', 33, 0, '12'),
	(12, 'Lion016', '21006', '22204', '1,2,1,0', '20404', 400, 5, 10, 15, 20, 25, '23013', 11, 0, '13'),
	(13, 'Lion021', '21007', '22204', '1,0,0,0', '20400', 100, 1, 2, 3, 4, 5, '23014', 32, 0, '14'),
	(14, 'Lion022', '21008', '22205', '0,1,1,0', '20500', 200, 2, 3, 4, 6, 7, '23015', 2, 3, '15'),
	(15, 'Lion023', '21009', '22202', '1,1,0,1', '20200', 300, 3, 4, 6, 8, 10, '23016', 11, 0, '16'),
	(16, 'Lion024', '21010', '22201', '1,1,1,1', '20100', 400, 2, 5, 7, 10, 12, '23017', 1, 1, '17'),
	(17, 'Lion025', '21011', '22203', '1,1,1,2', '20300', 500, 3, 6, 9, 12, 15, '23013', 22, 0, '13'),
	(18, 'Lion026', '21012', '22204', '1,2,2,1', '20400', 600, 3, 7, 10, 14, 17, '23019', 51, 0, '19'),
	(19, 'Lion031', '21013', '22204', '0,0,0,2', '20402', 200, 5, 10, 15, 20, 25, '23021', 23, 0, '21'),
	(20, 'Lion032', '21014', '22205', '1,2,0,1', '20504', 400, 5, 10, 15, 20, 25, '23009', 1, 1, '9'),
	(21, 'Lion033', '21015', '22202', '0,0,1,0', '20201', 100, 5, 10, 15, 20, 25, '23022', 11, 0, '22'),
	(22, 'Lion034', '21016', '22203', '1,0,0,1', '20302', 200, 5, 10, 15, 20, 25, '23013', 12, 0, '13'),
	(23, 'Lion035', '21017', '22204', '1,0,0,0', '20401', 100, 5, 10, 15, 20, 25, '23023', 2, 3, '23'),
	(24, 'Lion036', '21018', '22204', '0,1,1,0', '20402', 200, 5, 10, 15, 20, 25, '23023', 43, 0, '23'),
	(25, 'Lion041', '21019', '22204', '2,1,1,2', '20406', 600, 5, 10, 15, 20, 25, '23024', 23, 0, '24'),
	(26, 'Lion042', '21020', '22205', '1,2,2,1', '20506', 600, 5, 10, 15, 20, 25, '23010', 2, 2, '10'),
	(27, 'Lion043', '21021', '22202', '3,3,0,0', '20206', 600, 5, 10, 15, 20, 25, '23025', 51, 0, '25'),
	(28, 'Lion044', '21022', '22201', '1,1,1,2', '20105', 500, 5, 10, 15, 20, 25, '23026', 12, 0, '26'),
	(29, 'Lion045', '21023', '22203', '0,0,2,3', '20305', 500, 5, 10, 15, 20, 25, '23027', 2, 3, '27'),
	(30, 'Lion046', '21024', '22203', '2,2,1,0', '20315', 500, 5, 10, 15, 20, 25, '23028', 42, 0, '28'),
	(31, 'deer011', '21025', '22203', '0,0,2,2', '20314', 400, 3, 6, 10, 13, 16, '23013', 22, 0, '13'),
	(32, 'deer012', '21026', '22204', '1,2,0,0', '20413', 300, 3, 6, 10, 13, 16, '23022', 23, 0, '22'),
	(33, 'deer013', '21027', '22205', '0,2,2,0', '20514', 400, 3, 6, 10, 13, 16, '23029', 3, 4, '29'),
	(34, 'deer014', '21028', '22202', '1,0,0,2', '20213', 300, 3, 6, 10, 13, 16, '23022', 3, 3, '22'),
	(35, 'deer015', '21029', '22201', '2,2,0,0', '20114', 400, 3, 6, 10, 13, 16, '23012', 1, 8, '12'),
	(36, 'deer016', '21030', '22205', '0,2,0,1', '20513', 300, 3, 6, 10, 13, 16, '23014', 5, 1, '14'),
	(37, 'Norn_3', '21031', '22201', '1,1,1,0', '20103', 300, 5, 10, 15, 20, 25, '23030', 41, 0, '30'),
	(38, 'Norn_4', '21032', '22201', '2,1,1,0', '20104', 400, 6, 12, 18, 24, 30, '23031', 41, 0, '31'),
	(39, 'Norn_5', '21033', '22201', '3,1,1,0', '20105', 500, 7, 14, 21, 28, 35, '23032', 41, 0, '32'),
	(40, 'Norn_6', '21034', '22201', '3,2,1,0', '20106', 600, 8, 16, 24, 32, 40, '23033', 41, 0, '33'),
	(41, 'Bil_3', '21035', '22202', '0,0,1,2', '20200', 300, 3, 4, 6, 8, 10, '23034', 32, 0, '34'),
	(42, 'Bil_4', '21036', '22202', '0,0,1,2', '20200', 300, 4, 5, 7, 10, 13, '23035', 32, 0, '35'),
	(43, 'Bil_5', '21037', '22202', '0,0,1,2', '20200', 300, 5, 6, 9, 13, 16, '23036', 32, 0, '36'),
	(44, 'Bil_6', '21038', '22202', '0,0,1,2', '20200', 300, 6, 7, 11, 16, 20, '23037', 32, 0, '37'),
	(45, 'Demeter_3', '21039', '22203', '0,2,1,0', '20303', 300, 5, 10, 15, 20, 25, '23038', 43, 0, '38'),
	(46, 'Demeter_4', '21040', '22203', '0,2,1,0', '20303', 400, 6, 12, 18, 24, 30, '23039', 43, 0, '39'),
	(47, 'Demeter_5', '21041', '22203', '0,2,1,0', '20303', 500, 7, 14, 21, 28, 36, '23040', 43, 0, '40'),
	(48, 'Demeter_6', '21042', '22203', '0,2,1,0', '20303', 600, 8, 16, 25, 33, 43, '23041', 43, 0, '41'),
	(49, 'Artemis_3', '21043', '22204', '0,0,3,0', '20403', 300, 5, 10, 15, 20, 25, '23042', 42, 0, '42'),
	(50, 'Artemis_4', '21044', '22204', '1,0,3,0', '20404', 400, 6, 12, 18, 24, 30, '23043', 42, 0, '43'),
	(51, 'Artemis_5', '21045', '22204', '1,0,3,1', '20405', 500, 7, 14, 21, 28, 35, '23044', 42, 0, '44'),
	(52, 'Artemis_6', '21046', '22204', '1,1,3,1', '20406', 600, 8, 16, 24, 32, 40, '23045', 42, 0, '45'),
	(53, 'Eir_3', '21047', '22205', '0,1,1,1', '20503', 300, 5, 10, 15, 20, 25, '23046', 4, 1, '46'),
	(54, 'Eir_4', '21048', '22205', '0,1,1,2', '20504', 400, 6, 12, 18, 24, 30, '23047', 4, 1, '47'),
	(55, 'Eir_5', '21049', '22205', '0,1,2,2', '20505', 500, 7, 14, 21, 28, 35, '23048', 4, 1, '48'),
	(56, 'Eir_6', '21050', '22205', '0,1,2,3', '20506', 600, 8, 16, 24, 32, 40, '23049', 4, 1, '49');
/*!40000 ALTER TABLE `SkillInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillMaxEffect 結構
CREATE TABLE IF NOT EXISTS `SkillMaxEffect` (
  `MaxEffectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EffectName` varchar(50) DEFAULT NULL COMMENT '效果名稱或標籤',
  `EffectType` smallint(6) NOT NULL DEFAULT 0 COMMENT '效果類型',
  `Target` tinyint(4) NOT NULL DEFAULT 0 COMMENT '作用對象',
  `TypeValue` tinyint(4) NOT NULL DEFAULT 0 COMMENT '效果類型值',
  `Formula` text DEFAULT NULL COMMENT '公式(或值)',
  PRIMARY KEY (`MaxEffectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='滿等級技能效果表';

-- 正在傾印表格  koa_static.SkillMaxEffect 的資料：~49 rows (近似值)
/*!40000 ALTER TABLE `SkillMaxEffect` DISABLE KEYS */;
INSERT INTO `SkillMaxEffect` (`MaxEffectID`, `EffectName`, `EffectType`, `Target`, `TypeValue`, `Formula`) VALUES
	(1, 'Max001', 1, 0, 1, NULL),
	(2, 'Max002', 12, 0, 1, 'Climate+20'),
	(3, 'Max003', 10, 0, 1, 'Env+20'),
	(4, 'Max004', 103, 0, 0, 'HP+HP*10%'),
	(5, 'Max005', 402, 0, 0, 'S+S*10%'),
	(6, 'Max006', 402, 0, 0, 'S+S*20%'),
	(7, 'S_ADD_01', 402, 0, 0, 'S+0.5'),
	(8, 'change_c01', 2, 0, 1, NULL),
	(9, 'change_c02', 2, 0, 2, NULL),
	(10, 'change_c03', 2, 0, 3, NULL),
	(11, 'Sunny_add_01', 12, 0, 1, 'climate+10'),
	(12, 'S_UP_01', 402, 0, 0, 'S+S*20%'),
	(13, 'Dune_add_01', 10, 0, 1, 'shield+10'),
	(14, 'S_ADD_02', 402, 0, 0, 'S+0.1'),
	(15, 'S_UP_02', 402, 0, 0, 'S+S*5%'),
	(16, 'agwind_add_1', 11, 0, 2, 'Wind+10'),
	(17, 'S_UP_03', 402, 0, 0, 'S+S*10%'),
	(18, 'change_w01', 1, 0, 1, NULL),
	(19, 'change_w02', 1, 0, 2, NULL),
	(20, 'change_w03', 1, 0, 3, NULL),
	(21, 'dust_add_01', 12, 0, 2, 'climate+10'),
	(22, 'H_red_01', 101, 0, 0, 'H-0.1'),
	(23, 'H_down_01', 101, 0, 0, 'H-20%*H'),
	(24, 'att_HP_01', 401, 1, 0, 'HP-6'),
	(25, 'Dune_add_02', 10, 0, 1, 'shield+20'),
	(26, 'att_S_01', 401, 3, 0, 'S-0.5'),
	(27, 'att_H_01', 401, 3, 0, 'H+0.2'),
	(28, 'att_INT_01', 401, 4, 0, 'INT-10'),
	(29, 'HP_ADD_01', 103, 0, 0, 'HP+5'),
	(30, 'Norn_S_up_1', 402, 0, 0, 'S+S*15%'),
	(31, 'Norn_S_up_2', 402, 0, 0, 'S+S*20%'),
	(32, 'Norn_S_up_3', 402, 0, 0, 'S+S*25%'),
	(33, 'Norn_S_up_4', 402, 0, 0, 'S+S*30%'),
	(34, 'Bil_S_UP_3', 402, 0, 0, 'S+S*5%'),
	(35, 'Bil_S_up_2', 402, 0, 0, 'S+S*10%'),
	(36, 'Bil_S_up_3', 402, 0, 0, 'S+S*15%'),
	(37, 'Bil_S_up_4', 402, 0, 0, 'S+S*20%'),
	(38, 'Demeter_H_01', 101, 0, 0, 'H-20%*H'),
	(39, 'Demeter_H_02', 101, 0, 0, 'H-30%*H'),
	(40, 'Demeter_H_03', 101, 0, 0, 'H-40%*H'),
	(41, 'Demeter_H_04', 101, 0, 0, 'H-50%*H'),
	(42, 'Artemis_att_HP_01', 401, 5, 0, 'HP-1'),
	(43, 'Artemis_att_HP_02', 401, 5, 0, 'HP-2'),
	(44, 'Artemis_att_HP_03', 401, 5, 0, 'HP-3'),
	(45, 'Artemis_att_HP_04', 401, 5, 0, 'HP-4'),
	(46, 'Eir_ADD_HP_01', 103, 0, 0, 'HP+5'),
	(47, 'Eir_ADD_HP_02', 103, 0, 0, 'HP+6'),
	(48, 'Eir_ADD_HP_03', 103, 0, 0, 'HP+7'),
	(49, 'Eir_ADD_HP_04', 103, 0, 0, 'HP+8');
/*!40000 ALTER TABLE `SkillMaxEffect` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillPart 結構
CREATE TABLE IF NOT EXISTS `SkillPart` (
  `SkillPartID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PartCode` varchar(50) DEFAULT NULL COMMENT '部位外觀碼',
  `PartType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '部位',
  `AliasCode1` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `AliasCode2` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `AliasCode3` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `SkillAffixID` int(11) unsigned NOT NULL COMMENT '詞綴技能ID',
  PRIMARY KEY (`SkillPartID`),
  UNIQUE KEY `PartCode_PartType` (`PartCode`,`PartType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='部位技能對照表';

-- 正在傾印表格  koa_static.SkillPart 的資料：~30 rows (近似值)
/*!40000 ALTER TABLE `SkillPart` DISABLE KEYS */;
INSERT INTO `SkillPart` (`SkillPartID`, `PartCode`, `PartType`, `AliasCode1`, `AliasCode2`, `AliasCode3`, `SkillAffixID`) VALUES
	(1, '110101', 1, 'Lion011', NULL, NULL, 1),
	(2, '110101', 2, 'Lion012', NULL, NULL, 1),
	(3, '110101', 3, 'Lion013', NULL, NULL, 1),
	(4, '110101', 4, 'Lion014', NULL, NULL, 1),
	(5, '110101', 5, 'Lion015', NULL, NULL, 1),
	(6, '110101', 6, 'Lion016', NULL, NULL, 1),
	(7, '110102', 1, 'Lion021', NULL, NULL, 2),
	(8, '110102', 2, 'Lion022', NULL, NULL, 2),
	(9, '110102', 3, 'Lion023', NULL, NULL, 2),
	(10, '110102', 4, 'Lion024', NULL, NULL, 2),
	(11, '110102', 5, 'Lion025', NULL, NULL, 2),
	(12, '110102', 6, 'Lion026', NULL, NULL, 2),
	(13, '110103', 1, 'Lion031', NULL, NULL, 3),
	(14, '110103', 2, 'Lion032', NULL, NULL, 3),
	(15, '110103', 3, 'Lion033', NULL, NULL, 3),
	(16, '110103', 4, 'Lion034', NULL, NULL, 3),
	(17, '110103', 5, 'Lion035', NULL, NULL, 3),
	(18, '110103', 6, 'Lion036', NULL, NULL, 3),
	(19, '110104', 1, 'Lion041', NULL, NULL, 4),
	(20, '110104', 2, 'Lion042', NULL, NULL, 4),
	(21, '110104', 3, 'Lion043', NULL, NULL, 4),
	(22, '110104', 4, 'Lion044', NULL, NULL, 4),
	(23, '110104', 5, 'Lion045', NULL, NULL, 4),
	(24, '110104', 6, 'Lion046', NULL, NULL, 4),
	(25, '120301', 1, 'deer011', NULL, NULL, 5),
	(26, '120301', 2, 'deer012', NULL, NULL, 5),
	(27, '120301', 3, 'deer013', NULL, NULL, 5),
	(28, '120301', 4, 'deer014', NULL, NULL, 5),
	(29, '120301', 5, 'deer015', NULL, NULL, 5),
	(30, '120301', 6, 'deer016', NULL, NULL, 5);
/*!40000 ALTER TABLE `SkillPart` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
