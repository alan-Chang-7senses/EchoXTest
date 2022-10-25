USE `koa_log`;

-- 傾印  資料表 koa_log.UpgradeLevel 結構
CREATE TABLE IF NOT EXISTS `UpgradeLevel` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `PlayerID` int(11) NOT NULL DEFAULT 0 COMMENT '角色編號',
  `CoinCost` mediumint(9) NOT NULL DEFAULT 0 COMMENT '金幣減少量',
  `BonusType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '經驗加成模式',
  `ExpAdd` mediumint(9) NOT NULL DEFAULT 0 COMMENT '經驗值獲得量',
  `Time` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色等級升級Log';

-- 傾印  資料表 koa_log.UpgradeRank 結構
CREATE TABLE IF NOT EXISTS `UpgradeRank` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `PlayerID` int(11) NOT NULL DEFAULT 0 COMMENT '角色編號',
  `RankAdd` tinyint(4) NOT NULL DEFAULT 0 COMMENT '階級提升量',
  `CoinCost` mediumint(9) NOT NULL DEFAULT 0 COMMENT '金幣減少量',
  `Time` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='升階資訊Log';

CREATE TABLE IF NOT EXISTS `UpgradeSkill` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `PlayerID` int(11) NOT NULL DEFAULT 0 COMMENT '角色編號',
  `SkillID` int(11) NOT NULL DEFAULT 0 COMMENT '技能編號',
  `SkillRankAdd` tinyint(4) NOT NULL DEFAULT 0 COMMENT '技能階級提升量',
  `CoinCost` mediumint(9) NOT NULL DEFAULT 0 COMMENT '金幣減少量',
  `Time` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能升級紀錄';