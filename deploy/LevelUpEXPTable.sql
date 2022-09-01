
USE `koa_static`;

-- 傾印  資料表 koa_static.LevelUpEXP 結構
CREATE TABLE IF NOT EXISTS `LevelUpEXP` (
  `Level` int(10) unsigned NOT NULL DEFAULT 1,
  `RequireEXP` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='各等級升級所需經驗值';

-- 正在傾印表格  koa_static.LevelUpEXP 的資料：~99 rows (近似值)
INSERT INTO `LevelUpEXP` (`Level`, `RequireEXP`) VALUES
	(1, 20),
	(2, 30),
	(3, 45),
	(4, 60),
	(5, 70),
	(6, 85),
	(7, 95),
	(8, 105),
	(9, 115),
	(10, 125),
	(11, 140),
	(12, 165),
	(13, 170),
	(14, 185),
	(15, 292),
	(16, 325),
	(17, 361),
	(18, 400),
	(19, 442),
	(20, 487),
	(21, 535),
	(22, 589),
	(23, 646),
	(24, 708),
	(25, 775),
	(26, 828),
	(27, 885),
	(28, 947),
	(29, 1014),
	(30, 1086),
	(31, 1164),
	(32, 1249),
	(33, 1341),
	(34, 1441),
	(35, 1549),
	(36, 1667),
	(37, 1795),
	(38, 1934),
	(39, 2086),
	(40, 2251),
	(41, 2431),
	(42, 2628),
	(43, 2843),
	(44, 3078),
	(45, 3335),
	(46, 3616),
	(47, 3924),
	(48, 4262),
	(49, 4633),
	(50, 5041),
	(51, 5314),
	(52, 5604),
	(53, 5912),
	(54, 6239),
	(55, 6586),
	(56, 6955),
	(57, 7347),
	(58, 7763),
	(59, 8206),
	(60, 8677),
	(61, 9178),
	(62, 9712),
	(63, 10280),
	(64, 10885),
	(65, 11530),
	(66, 12218),
	(67, 12952),
	(68, 13735),
	(69, 14570),
	(70, 16462),
	(71, 16414),
	(72, 17432),
	(73, 18520),
	(74, 19683),
	(75, 20927),
	(76, 22350),
	(77, 21560),
	(78, 21885),
	(79, 22215),
	(80, 22551),
	(81, 22892),
	(82, 23239),
	(83, 23592),
	(84, 23951),
	(85, 24316),
	(86, 24687),
	(87, 25064),
	(88, 25447),
	(89, 25837),
	(90, 26233),
	(91, 26636),
	(92, 27046),
	(93, 27463),
	(94, 27887),
	(95, 28318),
	(96, 28756),
	(97, 29202),
	(98, 29655),
	(99, 30116);

-- 傾印  資料表 koa_static.ItemCharge 結構
CREATE TABLE IF NOT EXISTS `ItemCharge` (
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '物品ID',
  `Charge` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '使用手續費',
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用物品所需的手續費';

-- 正在傾印表格  koa_static.ItemCharge 的資料：~2 rows (近似值)
INSERT INTO `ItemCharge` (`ItemID`, `Charge`) VALUES
	(1001, 1000),
	(1002, 5000),
	(1003, 14000);

CREATE TABLE IF NOT EXISTS `UpgradeBonus` (
  `BonusID` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號',
  `Multiplier` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '加成倍率',
  PRIMARY KEY (`BonusID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='升級加成倍率';

-- 正在傾印表格  koa_static.UpgradeBonus 的資料：~0 rows (近似值)
INSERT INTO `UpgradeBonus` (`BonusID`, `Multiplier`) VALUES
	(1, 150),
	(2, 200);

CREATE TABLE IF NOT EXISTS `UpgradeMode` (
  `Mode` tinyint(4) NOT NULL DEFAULT 0 COMMENT '強化模式',
  `ChargeMultiply` int(11) NOT NULL DEFAULT 0 COMMENT '手續費倍率',
  `BigBonusProbability` int(11) NOT NULL DEFAULT 0 COMMENT '大成功機率',
  `UltimateBonusProbability` int(11) NOT NULL DEFAULT 0 COMMENT '大成功機率',
  PRIMARY KEY (`Mode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='提升角色等級的模式';

-- 正在傾印表格  koa_static.UpgradeMode 的資料：~3 rows (近似值)
INSERT INTO `UpgradeMode` (`Mode`, `ChargeMultiply`, `BigBonusProbability`, `UltimateBonusProbability`) VALUES
	(0, 90, 0, 0),
	(1, 100, 20, 5),
	(2, 130, 60, 5);