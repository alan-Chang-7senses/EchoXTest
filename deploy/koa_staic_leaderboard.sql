-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.9.3-MariaDB-1:10.9.3+maria~ubu2204 - mariadb.org binary distribution
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

-- 傾印  資料表 koa_static.Leaderboard 結構
CREATE TABLE IF NOT EXISTS `Leaderboard` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `Group` tinyint(4) DEFAULT NULL COMMENT '排行榜主項群組編號',
  `MainLeaderboradName` varchar(50) DEFAULT NULL COMMENT '主榜單字串',
  `SubLeaderboardName` varchar(50) DEFAULT NULL COMMENT '子榜單字串',
  `CompetitionRuleHint` varchar(50) DEFAULT NULL COMMENT '榜單規則字串',
  `SeasonID` int(11) DEFAULT NULL COMMENT '該榜單賽季識別碼',
  `SeasonName` varchar(50) DEFAULT NULL COMMENT '該榜單賽季企劃識別碼',
  `RecordType` tinyint(4) DEFAULT NULL COMMENT '計分類型。0：角色, 1：玩家',
  `RankRuleHint` varchar(50) DEFAULT NULL COMMENT '排名基準提示字串',
  PRIMARY KEY (`Serial`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='排行榜榜單資訊。';

TRUNCATE `Leaderboard`;
-- 正在傾印表格  koa_static.Leaderboard 的資料：~5 rows (近似值)
/*!40000 ALTER TABLE `Leaderboard` DISABLE KEYS */;
INSERT INTO `Leaderboard` (`Serial`, `Group`, `MainLeaderboradName`, `SubLeaderboardName`, `CompetitionRuleHint`, `SeasonID`, `SeasonName`, `RecordType`, `RankRuleHint`) VALUES
	(1, 0, '', '', '', 0, '玩家排行榜 ', 1, ''),
	(2, 1, '342', '68', '', 1, '火星幣賽(A)', 0, ''),
	(3, 1, '342', '69', '', 3, 'PT幣賽(A)', 0, ''),
	(4, 2, '343', '69', '', 4, 'PT幣賽(B)', 0, ''),
	(5, 2, '343', '68', '', 2, '火星幣賽(B)', 0, '');
/*!40000 ALTER TABLE `Leaderboard` ENABLE KEYS */;

-- 傾印  資料表 koa_static.QualifyingData 結構
CREATE TABLE IF NOT EXISTS `QualifyingData` (
  `SeasonID` int(11) NOT NULL AUTO_INCREMENT COMMENT '賽季編號',
  `SeasonName` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '企劃註解',
  `Lobby` int(11) NOT NULL DEFAULT 0 COMMENT '賽制大廳',
  `Scene` int(10) unsigned DEFAULT 0 COMMENT '場地編號',
  `StartTime` int(11) NOT NULL DEFAULT 0 COMMENT '開始時間',
  `EndTime` int(11) NOT NULL DEFAULT 0 COMMENT '結束時間',
  `CreateTime` int(11) NOT NULL DEFAULT 0 COMMENT '建立時間',
  PRIMARY KEY (`SeasonID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='賽季設定表';

-- 正在傾印表格  koa_static.QualifyingData 的資料：~5 rows (近似值)
/*!40000 ALTER TABLE `QualifyingData` DISABLE KEYS */;
TRUNCATE `QualifyingData`;
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (1, '練習賽', 3, 1001, 1640966400, 1988035200, 0);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (2, '火星幣賽(A) - S1', 1, 1002, 1669996800, 1670342400, 0);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (3, 'PT幣賽(A) - S1', 2, 1001, 1669996800, 1670342400, 0);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (4, '火星幣賽(B) - S1', 4, 1002, 1669996800, 1670342400, 0);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (5, 'PT幣賽(B) - S1', 5, 1001, 1669996800, 1670342400, 0);

/*!40000 ALTER TABLE `QualifyingData` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SeasonRankingRewardNew 結構
CREATE TABLE IF NOT EXISTS `SeasonRankingRewardNew` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `SeasonID` int(11) NOT NULL DEFAULT 0 COMMENT '賽季ID',
  `SeasonName` varchar(50) NOT NULL DEFAULT '0' COMMENT '賽季名稱',
  `Rank` smallint(6) NOT NULL DEFAULT 0 COMMENT '排名',
  `RewarID` int(11) NOT NULL DEFAULT 0 COMMENT '獎勵編號',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `SeasonID_Rank` (`SeasonID`,`Rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新版賽季獎勵表。';

TRUNCATE `SeasonRankingRewardNew`;
-- 正在傾印表格  koa_static.SeasonRankingRewardNew 的資料：~200 rows (近似值)
/*!40000 ALTER TABLE `SeasonRankingRewardNew` DISABLE KEYS */;
INSERT INTO `SeasonRankingRewardNew` (`Serial`, `SeasonID`, `SeasonName`, `Rank`, `RewarID`) VALUES
	(1, 1, '火星幣賽(A) - S1', 1, 2000401),
	(2, 1, '火星幣賽(A) - S1', 2, 2000402),
	(3, 1, '火星幣賽(A) - S1', 3, 2000403),
	(4, 1, '火星幣賽(A) - S1', 4, 2000404),
	(5, 1, '火星幣賽(A) - S1', 5, 2000405),
	(6, 1, '火星幣賽(A) - S1', 6, 2000406),
	(7, 1, '火星幣賽(A) - S1', 7, 2000407),
	(8, 1, '火星幣賽(A) - S1', 8, 2000408),
	(9, 1, '火星幣賽(A) - S1', 9, 2000409),
	(10, 1, '火星幣賽(A) - S1', 10, 2000410),
	(11, 1, '火星幣賽(A) - S1', 11, 2000411),
	(12, 1, '火星幣賽(A) - S1', 12, 2000412),
	(13, 1, '火星幣賽(A) - S1', 13, 2000413),
	(14, 1, '火星幣賽(A) - S1', 14, 2000414),
	(15, 1, '火星幣賽(A) - S1', 15, 2000415),
	(16, 1, '火星幣賽(A) - S1', 16, 2000416),
	(17, 1, '火星幣賽(A) - S1', 17, 2000417),
	(18, 1, '火星幣賽(A) - S1', 18, 2000418),
	(19, 1, '火星幣賽(A) - S1', 19, 2000419),
	(20, 1, '火星幣賽(A) - S1', 20, 2000420),
	(21, 1, '火星幣賽(A) - S1', 21, 2000421),
	(22, 1, '火星幣賽(A) - S1', 22, 2000422),
	(23, 1, '火星幣賽(A) - S1', 23, 2000423),
	(24, 1, '火星幣賽(A) - S1', 24, 2000424),
	(25, 1, '火星幣賽(A) - S1', 25, 2000425),
	(26, 1, '火星幣賽(A) - S1', 26, 2000426),
	(27, 1, '火星幣賽(A) - S1', 27, 2000427),
	(28, 1, '火星幣賽(A) - S1', 28, 2000428),
	(29, 1, '火星幣賽(A) - S1', 29, 2000429),
	(30, 1, '火星幣賽(A) - S1', 30, 2000430),
	(31, 1, '火星幣賽(A) - S1', 31, 2000431),
	(32, 1, '火星幣賽(A) - S1', 32, 2000432),
	(33, 1, '火星幣賽(A) - S1', 33, 2000433),
	(34, 1, '火星幣賽(A) - S1', 34, 2000434),
	(35, 1, '火星幣賽(A) - S1', 35, 2000435),
	(36, 1, '火星幣賽(A) - S1', 36, 2000436),
	(37, 1, '火星幣賽(A) - S1', 37, 2000437),
	(38, 1, '火星幣賽(A) - S1', 38, 2000438),
	(39, 1, '火星幣賽(A) - S1', 39, 2000439),
	(40, 1, '火星幣賽(A) - S1', 40, 2000440),
	(41, 1, '火星幣賽(A) - S1', 41, 2000441),
	(42, 1, '火星幣賽(A) - S1', 42, 2000442),
	(43, 1, '火星幣賽(A) - S1', 43, 2000443),
	(44, 1, '火星幣賽(A) - S1', 44, 2000444),
	(45, 1, '火星幣賽(A) - S1', 45, 2000445),
	(46, 1, '火星幣賽(A) - S1', 46, 2000446),
	(47, 1, '火星幣賽(A) - S1', 47, 2000447),
	(48, 1, '火星幣賽(A) - S1', 48, 2000448),
	(49, 1, '火星幣賽(A) - S1', 49, 2000449),
	(50, 1, '火星幣賽(A) - S1', 50, 2000450),
	(51, 2, '火星幣賽(B) - S1', 1, 2000601),
	(52, 2, '火星幣賽(B) - S1', 2, 2000602),
	(53, 2, '火星幣賽(B) - S1', 3, 2000603),
	(54, 2, '火星幣賽(B) - S1', 4, 2000604),
	(55, 2, '火星幣賽(B) - S1', 5, 2000605),
	(56, 2, '火星幣賽(B) - S1', 6, 2000606),
	(57, 2, '火星幣賽(B) - S1', 7, 2000607),
	(58, 2, '火星幣賽(B) - S1', 8, 2000608),
	(59, 2, '火星幣賽(B) - S1', 9, 2000609),
	(60, 2, '火星幣賽(B) - S1', 10, 2000610),
	(61, 2, '火星幣賽(B) - S1', 11, 2000611),
	(62, 2, '火星幣賽(B) - S1', 12, 2000612),
	(63, 2, '火星幣賽(B) - S1', 13, 2000613),
	(64, 2, '火星幣賽(B) - S1', 14, 2000614),
	(65, 2, '火星幣賽(B) - S1', 15, 2000615),
	(66, 2, '火星幣賽(B) - S1', 16, 2000616),
	(67, 2, '火星幣賽(B) - S1', 17, 2000617),
	(68, 2, '火星幣賽(B) - S1', 18, 2000618),
	(69, 2, '火星幣賽(B) - S1', 19, 2000619),
	(70, 2, '火星幣賽(B) - S1', 20, 2000620),
	(71, 2, '火星幣賽(B) - S1', 21, 2000621),
	(72, 2, '火星幣賽(B) - S1', 22, 2000622),
	(73, 2, '火星幣賽(B) - S1', 23, 2000623),
	(74, 2, '火星幣賽(B) - S1', 24, 2000624),
	(75, 2, '火星幣賽(B) - S1', 25, 2000625),
	(76, 2, '火星幣賽(B) - S1', 26, 2000626),
	(77, 2, '火星幣賽(B) - S1', 27, 2000627),
	(78, 2, '火星幣賽(B) - S1', 28, 2000628),
	(79, 2, '火星幣賽(B) - S1', 29, 2000629),
	(80, 2, '火星幣賽(B) - S1', 30, 2000630),
	(81, 2, '火星幣賽(B) - S1', 31, 2000631),
	(82, 2, '火星幣賽(B) - S1', 32, 2000632),
	(83, 2, '火星幣賽(B) - S1', 33, 2000633),
	(84, 2, '火星幣賽(B) - S1', 34, 2000634),
	(85, 2, '火星幣賽(B) - S1', 35, 2000635),
	(86, 2, '火星幣賽(B) - S1', 36, 2000636),
	(87, 2, '火星幣賽(B) - S1', 37, 2000637),
	(88, 2, '火星幣賽(B) - S1', 38, 2000638),
	(89, 2, '火星幣賽(B) - S1', 39, 2000639),
	(90, 2, '火星幣賽(B) - S1', 40, 2000640),
	(91, 2, '火星幣賽(B) - S1', 41, 2000641),
	(92, 2, '火星幣賽(B) - S1', 42, 2000642),
	(93, 2, '火星幣賽(B) - S1', 43, 2000643),
	(94, 2, '火星幣賽(B) - S1', 44, 2000644),
	(95, 2, '火星幣賽(B) - S1', 45, 2000645),
	(96, 2, '火星幣賽(B) - S1', 46, 2000646),
	(97, 2, '火星幣賽(B) - S1', 47, 2000647),
	(98, 2, '火星幣賽(B) - S1', 48, 2000648),
	(99, 2, '火星幣賽(B) - S1', 49, 2000649),
	(100, 2, '火星幣賽(B) - S1', 50, 2000650),
	(101, 3, 'PT幣賽(A) - S1', 1, 2000501),
	(102, 3, 'PT幣賽(A) - S1', 2, 2000502),
	(103, 3, 'PT幣賽(A) - S1', 3, 2000503),
	(104, 3, 'PT幣賽(A) - S1', 4, 2000504),
	(105, 3, 'PT幣賽(A) - S1', 5, 2000505),
	(106, 3, 'PT幣賽(A) - S1', 6, 2000506),
	(107, 3, 'PT幣賽(A) - S1', 7, 2000507),
	(108, 3, 'PT幣賽(A) - S1', 8, 2000508),
	(109, 3, 'PT幣賽(A) - S1', 9, 2000509),
	(110, 3, 'PT幣賽(A) - S1', 10, 2000510),
	(111, 3, 'PT幣賽(A) - S1', 11, 2000511),
	(112, 3, 'PT幣賽(A) - S1', 12, 2000512),
	(113, 3, 'PT幣賽(A) - S1', 13, 2000513),
	(114, 3, 'PT幣賽(A) - S1', 14, 2000514),
	(115, 3, 'PT幣賽(A) - S1', 15, 2000515),
	(116, 3, 'PT幣賽(A) - S1', 16, 2000516),
	(117, 3, 'PT幣賽(A) - S1', 17, 2000517),
	(118, 3, 'PT幣賽(A) - S1', 18, 2000518),
	(119, 3, 'PT幣賽(A) - S1', 19, 2000519),
	(120, 3, 'PT幣賽(A) - S1', 20, 2000520),
	(121, 3, 'PT幣賽(A) - S1', 21, 2000521),
	(122, 3, 'PT幣賽(A) - S1', 22, 2000522),
	(123, 3, 'PT幣賽(A) - S1', 23, 2000523),
	(124, 3, 'PT幣賽(A) - S1', 24, 2000524),
	(125, 3, 'PT幣賽(A) - S1', 25, 2000525),
	(126, 3, 'PT幣賽(A) - S1', 26, 2000526),
	(127, 3, 'PT幣賽(A) - S1', 27, 2000527),
	(128, 3, 'PT幣賽(A) - S1', 28, 2000528),
	(129, 3, 'PT幣賽(A) - S1', 29, 2000529),
	(130, 3, 'PT幣賽(A) - S1', 30, 2000530),
	(131, 3, 'PT幣賽(A) - S1', 31, 2000531),
	(132, 3, 'PT幣賽(A) - S1', 32, 2000532),
	(133, 3, 'PT幣賽(A) - S1', 33, 2000533),
	(134, 3, 'PT幣賽(A) - S1', 34, 2000534),
	(135, 3, 'PT幣賽(A) - S1', 35, 2000535),
	(136, 3, 'PT幣賽(A) - S1', 36, 2000536),
	(137, 3, 'PT幣賽(A) - S1', 37, 2000537),
	(138, 3, 'PT幣賽(A) - S1', 38, 2000538),
	(139, 3, 'PT幣賽(A) - S1', 39, 2000539),
	(140, 3, 'PT幣賽(A) - S1', 40, 2000540),
	(141, 3, 'PT幣賽(A) - S1', 41, 2000541),
	(142, 3, 'PT幣賽(A) - S1', 42, 2000542),
	(143, 3, 'PT幣賽(A) - S1', 43, 2000543),
	(144, 3, 'PT幣賽(A) - S1', 44, 2000544),
	(145, 3, 'PT幣賽(A) - S1', 45, 2000545),
	(146, 3, 'PT幣賽(A) - S1', 46, 2000546),
	(147, 3, 'PT幣賽(A) - S1', 47, 2000547),
	(148, 3, 'PT幣賽(A) - S1', 48, 2000548),
	(149, 3, 'PT幣賽(A) - S1', 49, 2000549),
	(150, 3, 'PT幣賽(A) - S1', 50, 2000550),
	(151, 4, 'PT幣賽(B) - S1', 1, 2000701),
	(152, 4, 'PT幣賽(B) - S1', 2, 2000702),
	(153, 4, 'PT幣賽(B) - S1', 3, 2000703),
	(154, 4, 'PT幣賽(B) - S1', 4, 2000704),
	(155, 4, 'PT幣賽(B) - S1', 5, 2000705),
	(156, 4, 'PT幣賽(B) - S1', 6, 2000706),
	(157, 4, 'PT幣賽(B) - S1', 7, 2000707),
	(158, 4, 'PT幣賽(B) - S1', 8, 2000708),
	(159, 4, 'PT幣賽(B) - S1', 9, 2000709),
	(160, 4, 'PT幣賽(B) - S1', 10, 2000710),
	(161, 4, 'PT幣賽(B) - S1', 11, 2000711),
	(162, 4, 'PT幣賽(B) - S1', 12, 2000712),
	(163, 4, 'PT幣賽(B) - S1', 13, 2000713),
	(164, 4, 'PT幣賽(B) - S1', 14, 2000714),
	(165, 4, 'PT幣賽(B) - S1', 15, 2000715),
	(166, 4, 'PT幣賽(B) - S1', 16, 2000716),
	(167, 4, 'PT幣賽(B) - S1', 17, 2000717),
	(168, 4, 'PT幣賽(B) - S1', 18, 2000718),
	(169, 4, 'PT幣賽(B) - S1', 19, 2000719),
	(170, 4, 'PT幣賽(B) - S1', 20, 2000720),
	(171, 4, 'PT幣賽(B) - S1', 21, 2000721),
	(172, 4, 'PT幣賽(B) - S1', 22, 2000722),
	(173, 4, 'PT幣賽(B) - S1', 23, 2000723),
	(174, 4, 'PT幣賽(B) - S1', 24, 2000724),
	(175, 4, 'PT幣賽(B) - S1', 25, 2000725),
	(176, 4, 'PT幣賽(B) - S1', 26, 2000726),
	(177, 4, 'PT幣賽(B) - S1', 27, 2000727),
	(178, 4, 'PT幣賽(B) - S1', 28, 2000728),
	(179, 4, 'PT幣賽(B) - S1', 29, 2000729),
	(180, 4, 'PT幣賽(B) - S1', 30, 2000730),
	(181, 4, 'PT幣賽(B) - S1', 31, 2000731),
	(182, 4, 'PT幣賽(B) - S1', 32, 2000732),
	(183, 4, 'PT幣賽(B) - S1', 33, 2000733),
	(184, 4, 'PT幣賽(B) - S1', 34, 2000734),
	(185, 4, 'PT幣賽(B) - S1', 35, 2000735),
	(186, 4, 'PT幣賽(B) - S1', 36, 2000736),
	(187, 4, 'PT幣賽(B) - S1', 37, 2000737),
	(188, 4, 'PT幣賽(B) - S1', 38, 2000738),
	(189, 4, 'PT幣賽(B) - S1', 39, 2000739),
	(190, 4, 'PT幣賽(B) - S1', 40, 2000740),
	(191, 4, 'PT幣賽(B) - S1', 41, 2000741),
	(192, 4, 'PT幣賽(B) - S1', 42, 2000742),
	(193, 4, 'PT幣賽(B) - S1', 43, 2000743),
	(194, 4, 'PT幣賽(B) - S1', 44, 2000744),
	(195, 4, 'PT幣賽(B) - S1', 45, 2000745),
	(196, 4, 'PT幣賽(B) - S1', 46, 2000746),
	(197, 4, 'PT幣賽(B) - S1', 47, 2000747),
	(198, 4, 'PT幣賽(B) - S1', 48, 2000748),
	(199, 4, 'PT幣賽(B) - S1', 49, 2000749),
	(200, 4, 'PT幣賽(B) - S1', 50, 2000750);
/*!40000 ALTER TABLE `SeasonRankingRewardNew` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
