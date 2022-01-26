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


-- 傾印 koa_main 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_main` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_main`;

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
	('AmountRoomPeopleMax', '8', '開房最大人數'),
	('TimezoneDefault', '8', '預設時區，數值範圍 -11~12');
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
	('KoaMain', 'db', 'root', '1111', 'koa_main', 3306),
	('KoaStatic', 'db', 'root', '1111', 'koa_static', 3306);
/*!40000 ALTER TABLE `DatabaseInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_main.PlayerHolder 結構
CREATE TABLE IF NOT EXISTS `PlayerHolder` (
  `PlayerID` bigint(20) unsigned NOT NULL,
  `UserID` int(10) unsigned NOT NULL DEFAULT 0,
  `Nickname` varchar(50) DEFAULT NULL COMMENT '角色暱稱',
  `SyncRate` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '同步率',
  PRIMARY KEY (`PlayerID`) USING BTREE,
  UNIQUE KEY `CharacterID_UserID` (`PlayerID`,`UserID`) USING BTREE,
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色持有資訊';

-- 正在傾印表格  koa_main.PlayerHolder 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `PlayerHolder` DISABLE KEYS */;
INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES
	(1010000000000005, 1, NULL, 456),
	(1010000000000015, 1, NULL, 5347);
/*!40000 ALTER TABLE `PlayerHolder` ENABLE KEYS */;

-- 傾印  資料表 koa_main.PlayerLevel 結構
CREATE TABLE IF NOT EXISTS `PlayerLevel` (
  `PlayerID` bigint(20) unsigned NOT NULL,
  `Level` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '等級',
  `Rank` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '階級',
  `Exp` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '經驗值',
  PRIMARY KEY (`PlayerID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色養成數值';

-- 正在傾印表格  koa_main.PlayerLevel 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `PlayerLevel` DISABLE KEYS */;
INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `Rank`, `Exp`) VALUES
	(1010000000000005, 1, 1, 0),
	(1010000000000015, 1, 1, 0);
/*!40000 ALTER TABLE `PlayerLevel` ENABLE KEYS */;

-- 傾印  資料表 koa_main.PlayerNFT 結構
CREATE TABLE IF NOT EXISTS `PlayerNFT` (
  `PlayerID` bigint(20) unsigned NOT NULL,
  `Constitution` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '體力',
  `Strength` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '力量',
  `Dexterity` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '技巧',
  `Agility` smallint(5) unsigned NOT NULL DEFAULT 100 COMMENT '敏捷',
  `Attribute` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '屬性',
  `HeadDNA` varchar(50) NOT NULL COMMENT '頭部 DNA 編碼',
  `BodyDNA` varchar(50) NOT NULL COMMENT '身體 DNA 編碼',
  `HandDNA` varchar(50) NOT NULL COMMENT '手部 DNA 編碼',
  `LegDNA` varchar(50) NOT NULL COMMENT '腿部 DNA 編碼',
  `BackDNA` varchar(50) NOT NULL COMMENT '背脊 DNA 編碼',
  `HatDNA` varchar(50) NOT NULL COMMENT '頭冠 DNA 編碼',
  `Achievement` varchar(50) NOT NULL DEFAULT '0000000000000000' COMMENT '成就標籤',
  `Native` tinyint(2) unsigned zerofill NOT NULL DEFAULT 00 COMMENT '原生種標記',
  PRIMARY KEY (`PlayerID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='來自 NFT 角色資訊';

-- 正在傾印表格  koa_main.PlayerNFT 的資料：~38 rows (近似值)
/*!40000 ALTER TABLE `PlayerNFT` DISABLE KEYS */;
INSERT INTO `PlayerNFT` (`PlayerID`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`) VALUES
	(1010000000000001, 3990, 3713, 4358, 3639, 1, '110101701101027012030170', '110101701101047011020530', '110101701101027011010470', '110101701101037021030170', '110101701203017011010270', '110101703102017011010370', '0000000000000000', 00),
	(1010000000000002, 3392, 3457, 4383, 3243, 1, '110101701101037021030170', '110102701203017031020170', '110101701101027012030170', '110101701101047011020530', '110101702103017012030170', '110101701101027011010470', '0000000000000000', 00),
	(1010000000000003, 4416, 3629, 4425, 3104, 1, '110101701101047031020170', '110205301101037021030170', '110101701101037021030170', '110101701203017011010270', '110101703102017011010270', '110101701101027012030170', '0000000000000000', 00),
	(1010000000000004, 3395, 3344, 3778, 3253, 1, '110205301203017011010170', '110104703102017012030170', '110101701101047031020170', '110101702103017011010370', '110101701101047011020530', '110101701101037021030170', '0000000000000000', 00),
	(1010000000000005, 3365, 4345, 4479, 3570, 1, '110205302103017011010270', '110101701203017011010470', '110101703102017011010470', '110101703102017011010470', '110103701101027011010170', '110101701101047031020170', '0000000000000000', 00),
	(1010000000000006, 3217, 4470, 3945, 3134, 1, '110102703102017011010370', '110102702103017011010370', '110101701102053011010270', '110101701203017011010270', '110101701101037011010470', '110101701203017011020530', '0000000000000000', 00),
	(1010000000000007, 3569, 3696, 4423, 4101, 1, '110102701101017011010470', '110103703102017011010270', '110205301101017011010370', '110101701101027031020170', '110101701101047012030170', '310201702103017011010170', '0000000000000000', 00),
	(1010000000000008, 4310, 3964, 3230, 4281, 1, '110102701101047012030170', '110205301101047011010170', '110101702103017011010470', '110101701101037012030170', '110101701203017021030170', '110101703102017011010270', '0000000000000000', 00),
	(1010000000000009, 3744, 3251, 3250, 4328, 1, '110103701101027021030170', '110101701203017011020530', '110101701101027012030170', '110101701101047021030170', '110101702103017011010270', '110101701101037011020530', '0000000000000000', 00),
	(1010000000000010, 3277, 4318, 3967, 3848, 1, '110103701101047031020170', '110102702103017031020170', '110101701203017021030170', '110101701203017011020530', '110101703102017012030170', '110101701101027011010370', '0000000000000000', 00),
	(1010000000000011, 4103, 4138, 3549, 3908, 1, '110103701203017011010170', '110103703102017021030170', '110101701101037031020170', '110101702103017011010370', '110101701101047031020170', '110101701101037011010470', '0000000000000000', 00),
	(1010000000000012, 3093, 4278, 3204, 3890, 1, '110103702103017011010270', '110104701101017012030170', '110101701101047012030170', '110101703102017011010370', '110101701101027011020530', '110101701101047012030170', '0000000000000000', 00),
	(1010000000000013, 3158, 3236, 3921, 3280, 1, '110104703102017011010370', '110205301101017011010470', '110101703102017011010270', '110101702103017011010470', '210301701101037011010170', '110101701203017021030170', '0000000000000000', 00),
	(1010000000000014, 3662, 4365, 3335, 4063, 1, '110104701101017011020530', '110205301101027011010370', '110101701102053011010370', '110101702103017011010270', '110101701101047011010370', '110101702103017031020170', '0000000000000000', 00),
	(1010000000000015, 3137, 4453, 3883, 3395, 1, '110104701101027012030170', '110103701101047011010270', '310201701101017011010470', '110101701101037031020170', '110101701203017011010470', '110101703102017011020530', '0000000000000000', 00),
	(1010000000000016, 4107, 4474, 3567, 3876, 1, '110104701101037021030170', '110104701203017011010170', '110101702103017012030170', '110101701101047012030170', '110101702103017031020170', '110103701101027011010170', '0000000000000000', 00),
	(1010000000000017, 3827, 4413, 4076, 3925, 1, '110101701101047031020170', '110101702103017011020530', '110101701101027021030170', '110101701203017021030170', '110101703102017011010270', '110104701101017011010270', '0000000000000000', 00),
	(1010000000000018, 4182, 3748, 3383, 4151, 1, '110205301203017011010170', '110205301101027031020170', '110101701203017031020170', '110101702103017011020530', '110101701101047012030170', '110102701101037012030170', '0000000000000000', 00),
	(1010000000000019, 3116, 4047, 3111, 3171, 1, '110101702103017011010270', '110103701101017021030170', '110101701101037021030170', '110101703102017011010470', '110101701101027031020170', '110102701101047011010370', '0000000000000000', 00),
	(1010000000000020, 3632, 4471, 3164, 4441, 1, '110101703102017011010370', '110104701101027012030170', '110101701101047011010270', '110101702103017011010370', '110101701101037011020530', '110102701203017011010470', '0000000000000000', 00),
	(1010000000000021, 3610, 4435, 4404, 3640, 1, '110102701101017011010470', '110101701101037011010470', '110101703102017011010370', '110101701101027011010470', '110205301101047011010170', '110102702103017012030170', '0000000000000000', 00),
	(1010000000000022, 4485, 4171, 3879, 4482, 1, '110102701101017012030170', '110102701101047011010370', '110101701102053011010470', '110101701101037011010270', '110101701203017011010370', '110102703102017021030170', '0000000000000000', 00),
	(1010000000000023, 3471, 3019, 3928, 4340, 1, '110102701101037021030170', '110103701203017011010270', '210301701101017012030170', '110101701101047031020170', '110101702103017011010470', '110102701101017031020170', '0000000000000000', 00),
	(1010000000000024, 3323, 4477, 3013, 4205, 1, '110102701101047031020170', '110104702103017011010170', '110101701101037021030170', '110101703102017012030170', '110101703102017021030170', '110102701101037011020530', '0000000000000000', 00),
	(1010000000000025, 3821, 3095, 4311, 3344, 1, '110103701203017011010170', '110101703102017011020530', '110101701101027031020170', '110101701102053021030170', '110101701101047011010270', '110102701101037011010170', '0000000000000000', 00),
	(1010000000000026, 3684, 3902, 4290, 3066, 1, '110103702103017011010270', '110102701101017031020170', '110101701203017031020170', '110101703102017011020530', '110101701101027012030170', '210301701101047011010270', '0000000000000000', 00),
	(1010000000000027, 3822, 3549, 4280, 3167, 1, '110205303102017011010370', '110103701101027021030170', '110101701101037011010270', '110101702103017012030170', '110101701101037031020170', '110102701203017011010370', '0000000000000000', 00),
	(1010000000000028, 4323, 3854, 4292, 4474, 1, '110103701101017011010470', '110104701101037012030170', '110101701101047011010370', '110101701101027011010370', '110101701101047011020530', '110102702103017011010370', '0000000000000000', 00),
	(1010000000000029, 3687, 4061, 3503, 4487, 1, '110104701101027012030170', '110205301101017011010470', '110101703102017011010470', '110101701101037011010470', '110103701203017011010170', '110102703102017011010470', '0000000000000000', 00),
	(1010000000000030, 3923, 4439, 4251, 4400, 1, '110104701101037021030170', '110102701203017011010370', '110101701102053012030170', '110101701101047011010270', '110101702103017011010370', '110102701101017012030170', '0000000000000000', 00),
	(1010000000000031, 3257, 3667, 4404, 3643, 1, '110104701101027031020170', '110103702103017011010270', '120301701101017021030170', '110101701203017031020170', '110101703102017011010470', '110102701101047021030170', '0000000000000000', 00),
	(1010000000000032, 3219, 3408, 3102, 4055, 1, '110104701203017011010170', '110104703102017011010170', '110101702103017031020170', '110101702103017012030170', '110101701101047021030170', '110102701101037031020170', '0000000000000000', 00),
	(1010000000000033, 3203, 4146, 4151, 3570, 1, '110101702103017011010270', '110101701203017011020530', '110101701101027011020530', '110101703102017021030170', '110101701101027011010370', '110103701101047011020530', '0000000000000000', 00),
	(1010000000000034, 3854, 4397, 3319, 4487, 1, '110101703102017011010370', '110102702103017031020170', '110101701203017011010270', '110101701101027011020530', '110101701101037012030170', '110103701203017011010170', '0000000000000000', 00),
	(1010000000000035, 3470, 3144, 3282, 4469, 1, '110101701101037011010470', '110103703102017021030170', '110101701101047011010370', '110101701101027021030170', '110101701101047031020170', '110103702103017011010270', '0000000000000000', 00),
	(1010000000000036, 3634, 3841, 4165, 4071, 1, '110101701101027012030170', '110104701101017012030170', '110101701203017011010470', '110101701101047011010370', '110101701203017011020530', '110205303102017011010370', '0000000000000000', 00),
	(1010000000000037, 4492, 4308, 4178, 4153, 1, '110102701101037021030170', '110101701203017011010470', '110101703102017012030170', '110101701101027011010470', '110104702103017011010170', '110103701101017011010470', '0000000000000000', 00),
	(1010000000000038, 3494, 3669, 3086, 3655, 1, '110102701101047031020170', '110102702103017011010370', '110101701102053021030170', '110101701203017011010270', '110101703102017011010370', '110103701101027012030170', '0000000000000000', 00);
/*!40000 ALTER TABLE `PlayerNFT` ENABLE KEYS */;

-- 傾印  資料表 koa_main.PlayerrCounts 結構
CREATE TABLE IF NOT EXISTS `PlayerrCounts` (
  `PlayerID` bigint(20) unsigned NOT NULL,
  `PVPPlay` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 比賽次數',
  `PVPFirst` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 第一名次數',
  `PVPTopThree` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 前三名次數',
  `PVPMiddle` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 中間名次次數',
  `PVPLastThree` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PvP 後三名次數',
  `TeamPlay` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '團體戰次數',
  `TeamWin` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '團體戰優勝次數',
  PRIMARY KEY (`PlayerID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色計量數值';

-- 正在傾印表格  koa_main.PlayerrCounts 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `PlayerrCounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `PlayerrCounts` ENABLE KEYS */;

-- 傾印  資料表 koa_main.PlayerSkill 結構
CREATE TABLE IF NOT EXISTS `PlayerSkill` (
  `PlayerID` bigint(20) unsigned NOT NULL,
  `SkillID` bigint(20) unsigned NOT NULL,
  `Level` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '星級',
  `Slot` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '裝備插槽',
  PRIMARY KEY (`PlayerID`,`SkillID`) USING BTREE,
  KEY `CharacterID` (`PlayerID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色技能等級\r\n只記錄該角色所具備的技能';

-- 正在傾印表格  koa_main.PlayerSkill 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `PlayerSkill` DISABLE KEYS */;
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `Slot`) VALUES
	(1010000000000015, 1, 1, 2),
	(1010000000000015, 2, 2, 4);
/*!40000 ALTER TABLE `PlayerSkill` ENABLE KEYS */;

-- 傾印  資料表 koa_main.Sessions 結構
CREATE TABLE IF NOT EXISTS `Sessions` (
  `SessionID` varchar(255) NOT NULL,
  `SessionExpires` int(10) unsigned NOT NULL DEFAULT 0,
  `SessionData` text DEFAULT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`SessionID`),
  KEY `SessionExpires` (`SessionExpires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 傾印  資料表 koa_main.Users 結構
CREATE TABLE IF NOT EXISTS `Users` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Status` tinyint(4) DEFAULT 1 COMMENT '狀態(1=啟用)',
  `Username` varchar(255) NOT NULL COMMENT '帳號',
  `Nickname` varchar(255) NOT NULL COMMENT '暱稱',
  `Password` varchar(255) NOT NULL COMMENT '密碼',
  `Level` smallint(5) unsigned NOT NULL DEFAULT 1 COMMENT '等級',
  `Exp` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '經驗值',
  `Vitality` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '體力',
  `Money` bigint(20) NOT NULL DEFAULT 0 COMMENT '金錢',
  `CreateTime` int(11) NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(11) NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者資料';

-- 正在傾印表格  koa_main.Users 的資料：~1 rows (近似值)
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Level`, `Exp`, `Vitality`, `Money`, `CreateTime`, `UpdateTime`) VALUES
	(1, 1, 'zhiwei', 'Zhiwei', '$2y$10$YmrheBdMXp2mUcLCuHOu7e6u6tkik7C7qzTp1R1CcGLqU5eyqtAQ2', 1, 0, 0, 0, 0, 0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
