USE `koa_static`;

-- 傾印  資料表 koa_static.PVELevel 結構
CREATE TABLE IF NOT EXISTS `PVELevel` (
  `LevelID` int(11) NOT NULL DEFAULT 0 COMMENT '關卡ID',
  `RecommendLevel` int(11) NOT NULL DEFAULT 0 COMMENT '推薦角色等級',
  `Power` int(11) NOT NULL DEFAULT 0 COMMENT '需求電力',
  `LevelName` varchar(50) NOT NULL DEFAULT '' COMMENT '關卡名稱字碼',
  `Description` varchar(50) NOT NULL DEFAULT '' COMMENT '關卡簡介字碼',
  `SceneID` int(11) NOT NULL DEFAULT 0 COMMENT '使用場景ID',
  `TrackAttribute` tinyint(4) NOT NULL DEFAULT 0 COMMENT '賽道屬性代碼',
  `Weather` tinyint(4) NOT NULL DEFAULT 0 COMMENT '天氣代碼',
  `Wind` tinyint(4) NOT NULL DEFAULT 0 COMMENT '風向代碼',
  `WindSpeed` smallint(6) NOT NULL DEFAULT 0 COMMENT '風速',
  `DayNight` tinyint(4) NOT NULL DEFAULT 0 COMMENT '日夜代碼',
  `UserTrackNumber` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用戶所在跑道',
  `FirstRewardID` int(11) NOT NULL DEFAULT 0 COMMENT '初次過關獎勵',
  `SustainRewardID` int(11) NOT NULL DEFAULT 0 COMMENT '固定過關獎勵',
  PRIMARY KEY (`LevelID`),
  UNIQUE KEY `LevelID` (`LevelID`),
  KEY `SceneID` (`SceneID`),
  KEY `TrackAttribute` (`TrackAttribute`),
  KEY `DayNight` (`DayNight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PVE關卡資訊';

USE `koa_static`;

-- 傾印  資料表 koa_static.PVELevelAI 結構
CREATE TABLE IF NOT EXISTS `PVELevelAI` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `AIID` int(11) NOT NULL COMMENT 'AI編號',
  `LevelID` int(11) NOT NULL COMMENT '關卡編號',
  `TrackNumber` int(11) NOT NULL COMMENT '跑道編號',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `Track` (`TrackNumber`,`LevelID`) USING BTREE,
  KEY `AIID` (`AIID`),
  KEY `Level` (`LevelID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;