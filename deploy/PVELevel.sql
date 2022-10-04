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
  `FirstItemIDs` varchar(50) NOT NULL DEFAULT '0' COMMENT '初次過關獎勵(前端顯示需求)',
  `SustainItemIDs` varchar(50) NOT NULL DEFAULT '0' COMMENT '固定過關獎勵(前端顯示需求)',
  `FirstAI` int(11) DEFAULT NULL COMMENT '第一隻機器人',
  `FirstAITrackNumber` tinyint(4) DEFAULT NULL COMMENT '第一隻機器人所在跑道',
  `SecondAI` int(11) DEFAULT NULL COMMENT '第二隻機器人',
  `SecondAITrackNumber` tinyint(4) unsigned DEFAULT NULL COMMENT '第二隻機器人所在跑道',
  `ThirdAI` int(11) DEFAULT NULL COMMENT '第三隻機器人',
  `ThirdAITrackNumber` tinyint(4) DEFAULT NULL COMMENT '第三隻機器人所在跑道',
  PRIMARY KEY (`LevelID`),
  KEY `SceneID` (`SceneID`),
  KEY `ChapterID` (`ChapterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PVE關卡資訊';

CREATE TABLE IF NOT EXISTS `PVEChapter` (
  `ChapterID` int(11) NOT NULL COMMENT '章節編號',
  `Name` varchar(50) NOT NULL DEFAULT '' COMMENT '章節名稱',
  `Icon` varchar(50) NOT NULL DEFAULT '' COMMENT '章節圖示',
  `PreChapter` varchar(50) NOT NULL DEFAULT '' COMMENT '前置關卡',
  `MedalAmountFirst` smallint(6) NOT NULL DEFAULT 0 COMMENT '第一階段獎勵獎牌數量',
  `RewardIDFirst` int(11) NOT NULL DEFAULT 0 COMMENT '第一階段獎勵編號',
  `MedalAmountSecond` smallint(6) NOT NULL DEFAULT 0 COMMENT '第二階段獎勵獎牌數量',
  `RewardIDSecond` int(11) NOT NULL DEFAULT 0 COMMENT '第二階段獎勵編號',
  `MedalAmountThird` smallint(6) NOT NULL DEFAULT 0 COMMENT '第三階段獎勵獎牌數量',
  `RewardIDThrid` int(11) NOT NULL DEFAULT 0 COMMENT '第三階段獎勵編號',
  PRIMARY KEY (`ChapterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PVE章節資訊';

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
  KEY `MedalAmount` (`MedalAmount`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COMMENT='使用者PVE通關狀態';

