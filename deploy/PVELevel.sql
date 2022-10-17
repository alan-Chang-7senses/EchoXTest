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
  `PreChapter` varchar(50) NOT NULL DEFAULT '' COMMENT '前置章節',
  `Name` varchar(50) NOT NULL DEFAULT '' COMMENT '章節名稱',
  `Icon` varchar(50) NOT NULL DEFAULT '' COMMENT '章節圖示',
  `MedalAmountFirst` smallint(6) NOT NULL DEFAULT 0 COMMENT '第一階段獎勵獎牌數量',
  `RewardIDFirst` int(11) NOT NULL DEFAULT 0 COMMENT '第一階段獎勵編號',
  `MedalAmountSecond` smallint(6) NOT NULL DEFAULT 0 COMMENT '第二階段獎勵獎牌數量',
  `RewardIDSecond` int(11) NOT NULL DEFAULT 0 COMMENT '第二階段獎勵編號',
  `MedalAmountThird` smallint(6) NOT NULL DEFAULT 0 COMMENT '第三階段獎勵獎牌數量',
  `RewardIDThrid` int(11) NOT NULL DEFAULT 0 COMMENT '第三階段獎勵編號',
  PRIMARY KEY (`ChapterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PVE章節資訊';

INSERT INTO `PVELevel` (`LevelID`, `ChapterID`, `PreLevel`, `RecommendLevel`, `Power`, `LevelName`, `Description`, `SceneID`, `UserTrackNumber`, `FirstRewardID`, `SustainRewardID`, `FirstItemIDs`, `SustainItemIDs`, `FirstAI`, `FirstAITrackNumber`, `SecondAI`, `SecondAITrackNumber`, `ThirdAI`, `ThirdAITrackNumber`) VALUES
	(101, 1, '', 1, 0, '101', '', 1001, 1, 1, 2, '-3', '2013,   2017', -1, 1, -2, 2, -3, 3),
	(102, 1, '101', 1, 0, '102', '', 1001, 1, 1, 2, '-3', '2013,   2017', -1, 1, -2, 2, -3, 3),
	(103, 1, '101,102', 1, 0, '103', '', 1001, 1, 1, 2, '-3', '2013,   2017', -1, 1, -2, 2, -3, 3),
	(203, 2, '', 1, 0, '203', '', 1001, 1, 1, 2, '-3', '2013,   2017', -1, 1, -2, 2, -3, 3),
	(204, 2, '203', 1, 0, '204', '', 1001, 1, 1, 2, '-3', '2013,   2017', -1, 1, -2, 2, -3, 3),
	(3003, 3, '203', 1, 0, '3003', '', 1001, 1, 1, 2, '-3', '2013,   2017', -1, 1, -2, 2, -3, 3),
	(3004, 3, '3003', 1, 0, '3004', '', 1001, 1, 1, 2, '-3', '2013,   2017', -1, 1, -2, 2, -3, 3);
  
INSERT INTO `PVEChapter` (`ChapterID`, `PreChapter`, `Name`, `Icon`, `MedalAmountFirst`, `RewardIDFirst`, `MedalAmountSecond`, `RewardIDSecond`, `MedalAmountThird`, `RewardIDThrid`) VALUES
	(1, '', '1111', '', 3, 1, 6, 1, 9, 1),
	(2, '1', '2222', '', 3, 1, 6, 1, 9, 1),
	(3, '1,2', '3333', '', 3, 1, 6, 1, 9, 1);


USE `koa_main`;

CREATE TABLE IF NOT EXISTS `UserPVELevel` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者ID',
  `LevelID` int(11) NOT NULL DEFAULT 0 COMMENT '關卡ID',
  `MedalAmount` tinyint(4) NOT NULL DEFAULT 0 COMMENT '獲得獎牌數量',
  `Status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '關卡進度。0閒置、1進行中',
  `RaceRoomID` int(11) NOT NULL DEFAULT 0 COMMENT '競賽房間編號',
  `UpdateTime` int(11) NOT NULL DEFAULT 0 COMMENT '通關時間',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `UserID_LevelID` (`UserID`,`LevelID`) USING BTREE,
  KEY `MedalAmount` (`MedalAmount`),
  KEY `Process` (`Status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COMMENT='使用者PVE通關狀態';

CREATE TABLE IF NOT EXISTS `UserPVEChapterReward` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `ChapterID` int(11) NOT NULL DEFAULT 0 COMMENT '章節編號',
  `ChapterRewardID` tinyint(4) NOT NULL DEFAULT 0 COMMENT '章節獎勵編號',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `ChapterID` (`ChapterID`,`ChapterRewardID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='玩家PVE章節領獎資訊';

ALTER TABLE `PlayerNFT`
ADD COLUMN `TradeCount` smallint(6) NOT NULL DEFAULT 0 COMMENT '被交易次數';

INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES
	('LobbyPVEPlayerLevel', '0', 'PVE指定角色等級(0=不指定)'),
	('LobbyPVESkillLevel', '0', 'PVE指定技能等級(0=不指定)');

USE `koa_log`;

-- 傾印  資料表 koa_log.PVECleared 結構
CREATE TABLE IF NOT EXISTS `PVECleared` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT 0,
  `PlayerID` int(11) NOT NULL DEFAULT 0,
  `LevelID` int(11) NOT NULL DEFAULT 0,
  `Items` text NOT NULL DEFAULT '',
  `SyncRate` int(11) NOT NULL DEFAULT 0,
  `Ranking` tinyint(4) NOT NULL DEFAULT 0,
  `ClearCount` smallint(6) NOT NULL DEFAULT 0,
  `StartTime` int(11) NOT NULL DEFAULT 0,
  `FinishTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='PVE通關紀錄。';