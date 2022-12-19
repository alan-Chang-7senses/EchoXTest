USE `koa_static`;

-- 傾印  資料表 koa_static.RankUpItems 結構
CREATE TABLE IF NOT EXISTS `RankUpItems` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `RankUpLevel` tinyint(4) NOT NULL COMMENT '升階階級',
  `Attribute` tinyint(4) NOT NULL COMMENT '升階角色屬性',
  `DustItemID` int(11) DEFAULT NULL COMMENT '粉塵道具ID',
  `DustAmount` int(11) DEFAULT NULL COMMENT '粉塵數量',
  `CrystalItemID` int(11) DEFAULT NULL COMMENT '晶石道具ID',
  `CrystalAmount` int(11) DEFAULT NULL COMMENT '晶石道具數量',
  `CoinCost` int(11) NOT NULL DEFAULT 0 COMMENT '火星幣消耗',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `RankUpLevel_Attribute` (`RankUpLevel`,`Attribute`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色升階所需道具表';

-- 正在傾印表格  koa_static.RankUpItems 的資料：~9 rows (近似值)
INSERT INTO `RankUpItems` (`Serial`, `RankUpLevel`, `Attribute`, `DustItemID`, `DustAmount`, `CrystalItemID`, `CrystalAmount`, `CoinCost`) VALUES
	(1, 2, 1, 1111, 250, 1112, 0, 2500),
	(2, 3, 1, 1111, 1000, 1112, 120, 8500),
	(3, 4, 1, 1111, 1500, 1112, 150, 5950),
	(4, 2, 2, 1121, 250, 1122, 0, 2500),
	(5, 3, 2, 1121, 1000, 1122, 120, 8500),
	(6, 4, 2, 1121, 1500, 1122, 150, 5950),
	(7, 2, 3, 1131, 250, 1132, 0, 2500),
	(8, 3, 3, 1131, 1000, 1132, 120, 8500),
	(9, 4, 3, 1131, 1500, 1132, 150, 5950);

CREATE TABLE IF NOT EXISTS `SkillUpgradeItems` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UpgradeLevel` tinyint(4) NOT NULL COMMENT '升技等級',
  `SpecieCode` int(11) NOT NULL COMMENT '物種NFT編號',
  `BaseItemID` int(11) DEFAULT 0 COMMENT '基礎道具編號',
  `BaseItemAmount` int(11) NOT NULL DEFAULT 0 COMMENT '基礎道具數量',
  `ChipItemID` int(11) DEFAULT 0 COMMENT '晶片道具編號',
  `ChipAmount` int(11) NOT NULL DEFAULT 0 COMMENT '晶片數量',
  `CoinCost` int(11) NOT NULL DEFAULT 0 COMMENT '火星幣花費',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='技能升級所需物品資訊。';

-- 正在傾印表格  koa_static.SkillUpgradeItems 的資料：~32 rows (近似值)
INSERT INTO `SkillUpgradeItems` (`Serial`, `UpgradeLevel`, `SpecieCode`, `BaseItemID`, `BaseItemAmount`, `ChipItemID`, `ChipAmount`, `CoinCost`) VALUES
	(1, 2, 11, 2000, 10, 2011, 0, 250),
	(2, 3, 11, 2000, 15, 2011, 0, 2750),
	(3, 4, 11, 2000, 0, 2011, 10, 5950),
	(4, 5, 11, 2000, 0, 2011, 15, 6250),
	(5, 2, 13, 2000, 10, 2013, 0, 250),
	(6, 3, 13, 2000, 15, 2013, 0, 2750),
	(7, 4, 13, 2000, 0, 2013, 10, 5950),
	(8, 5, 13, 2000, 0, 2013, 15, 6250),
	(9, 2, 14, 2000, 10, 2014, 0, 250),
	(10, 3, 14, 2000, 15, 2014, 0, 2750),
	(11, 4, 14, 2000, 0, 2014, 10, 5950),
	(12, 5, 14, 2000, 0, 2014, 15, 6250),
	(13, 2, 15, 2000, 10, 2015, 0, 250),
	(14, 3, 15, 2000, 15, 2015, 0, 2750),
	(15, 4, 15, 2000, 0, 2015, 10, 5950),
	(16, 5, 15, 2000, 0, 2015, 15, 6250),
	(17, 2, 16, 2000, 10, 2016, 0, 250),
	(18, 3, 16, 2000, 15, 2016, 0, 2750),
	(19, 4, 16, 2000, 0, 2016, 10, 5950),
	(20, 5, 16, 2000, 0, 2016, 15, 6250),
	(21, 2, 17, 2000, 10, 2017, 0, 250),
	(22, 3, 17, 2000, 15, 2017, 0, 2750),
	(23, 4, 17, 2000, 0, 2017, 10, 5950),
	(24, 5, 17, 2000, 0, 2017, 15, 6250),
	(25, 2, 0, 2000, 10, 2001, 0, 250),
	(26, 3, 0, 2000, 15, 2001, 0, 2750),
	(27, 4, 0, 2000, 0, 2001, 10, 5950),
	(28, 5, 0, 2000, 0, 2001, 15, 6250),
	(29, 2, 18, 2000, 10, 2002, 0, 250),
	(30, 3, 18, 2000, 15, 2002, 0, 2750),
	(31, 4, 18, 2000, 0, 2002, 10, 5950),
	(32, 5, 18, 2000, 0, 2002, 15, 6250);