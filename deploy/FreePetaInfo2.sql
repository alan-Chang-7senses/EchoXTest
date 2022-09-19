


USE `koa_static`;

DROP TABLE `FreePetaInfo`;

-- 傾印  資料表 koa_static.FreePetaInfo 結構
CREATE TABLE IF NOT EXISTS `FreePetaInfo` (
  `ID` int(10) unsigned NOT NULL,
  `Type` tinyint(1) DEFAULT NULL COMMENT '免費Peta種類',
  `Constitution` smallint(5) NOT NULL DEFAULT 0 COMMENT '體力',
  `Strength` smallint(5) NOT NULL DEFAULT 0 COMMENT '力量',
  `Dexterity` smallint(5) NOT NULL DEFAULT 0 COMMENT '技巧',
  `Agility` smallint(5) NOT NULL DEFAULT 0 COMMENT '敏捷',
  `Attribute` tinyint(3) NOT NULL DEFAULT 0 COMMENT '屬性',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='免費Peta 基礎數值合集';

-- 正在傾印表格  koa_static.FreePetaInfo 的資料：~15 rows (近似值)
INSERT INTO `FreePetaInfo` (`ID`, `Type`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`) VALUES
	(1, 0, 4500, 7200, 5100, 5200, 1),
	(2, 0, 4500, 7300, 4900, 5300, 1),
	(3, 0, 4400, 7200, 5100, 5300, 1),
	(4, 0, 4500, 5800, 5600, 6100, 1),
	(5, 0, 4500, 5200, 4800, 7500, 1),
	(6, 1, 5800, 5500, 5400, 5300, 1),
	(7, 1, 5900, 5300, 5300, 5500, 1),
	(8, 1, 6000, 5200, 5600, 5200, 1),
	(9, 1, 5200, 5300, 5400, 6100, 1),
	(10, 1, 5400, 5300, 5600, 5700, 1),
	(11, 2, 6100, 4400, 6700, 4800, 1),
	(12, 2, 5500, 4900, 6800, 4800, 1),
	(13, 2, 5300, 4800, 6900, 5000, 1),
	(14, 2, 6700, 4500, 6000, 4800, 1),
	(15, 2, 6200, 4900, 5900, 5000, 1);

