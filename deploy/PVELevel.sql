USE `koa_static`;

-- 傾印  資料表 koa_static.PVELevel 結構
CREATE TABLE IF NOT EXISTS `PVELevel` (
  `LevelID` int(11) NOT NULL DEFAULT 0 COMMENT '關卡ID',
  `ChapterID` int(11) NOT NULL DEFAULT 0 COMMENT '關卡所在章節編號',
  `PreLevel` varchar(50) NOT NULL DEFAULT '' COMMENT '前置關卡',
  `RecommendLevel` int(11) NOT NULL DEFAULT 0 COMMENT '推薦角色等級',
  `Power` int(11) NOT NULL DEFAULT 0 COMMENT '需求電力',
  `LevelName` varchar(50) NOT NULL DEFAULT '' COMMENT '關卡名稱字碼',
  `Description` varchar(50) NOT NULL DEFAULT '' COMMENT '關卡簡介字碼',
  `SceneID` int(11) NOT NULL DEFAULT 0 COMMENT '使用場景ID',
  `UserTrackNumber` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用戶所在跑道',
  `FirstRewardID` int(11) NOT NULL DEFAULT 0 COMMENT '初次過關獎勵',
  `SustainRewardID` int(11) NOT NULL DEFAULT 0 COMMENT '固定過關獎勵',
  `FirstAI` int(11) DEFAULT NULL,
  `FirstAITrackNumber` tinyint(4) DEFAULT NULL,
  `SecondAI` int(11) DEFAULT NULL,
  `SecondAITrackNumber` tinyint(4) unsigned DEFAULT NULL,
  `ThirdAI` int(11) DEFAULT NULL,
  `ThirdAITrackNumber` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`LevelID`),
  KEY `SceneID` (`SceneID`),
  KEY `ChapterID` (`ChapterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PVE關卡資訊';

USE `koa_main`;

-- 傾印  資料表 koa_main.UserPVELevel 結構
CREATE TABLE IF NOT EXISTS `UserPVELevel` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者ID',
  `LevelID` int(11) NOT NULL DEFAULT 0 COMMENT '關卡ID',
  `MedalAmount` tinyint(4) NOT NULL DEFAULT 0 COMMENT '獲得獎牌數量',
  `Time` int(11) NOT NULL DEFAULT 0 COMMENT '通關時間',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `UserID_LevelID` (`UserID`,`LevelID`) USING BTREE,
  KEY `MedalAmount` (`MedalAmount`),
  KEY `UserID` (`UserID`),
  KEY `LevelID` (`LevelID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COMMENT='使用者PVE通關狀態';

