USE `koa_main`;

-- 傾印  資料表 koa_main.LeaderboardRating 結構
CREATE TABLE IF NOT EXISTS `LeaderboardRating` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `SeasonID` int(11) NOT NULL DEFAULT 0 COMMENT '賽季編號',
  `Lobby` tinyint(4) NOT NULL COMMENT '大廳(競賽種類)',
  `PlayerID` bigint(20) NOT NULL COMMENT '角色編號',
  `Rating` smallint(6) NOT NULL COMMENT '評分',
  `UpdateTime` int(11) NOT NULL COMMENT '更新時間',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `PlayerID_CompetitionType_SeasonID` (`PlayerID`,`Lobby`,`SeasonID`) USING BTREE,
  KEY `UpdateTime` (`UpdateTime`),
  KEY `Rating` (`Rating`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='積分排行榜。';

USE `koa_static`;

-- 傾印  資料表 koa_static.CompetitionsInfo 結構
CREATE TABLE IF NOT EXISTS `CompetitionsInfo` (
  `ID` int(11) NOT NULL DEFAULT 0 COMMENT '賽制編號',
  `Explain` varchar(50) DEFAULT NULL COMMENT '賽制識別字串(企劃用)',
  `WeeksPerSeason` tinyint(4) DEFAULT 0 COMMENT '賽季時長(單位為週)',
  `MinRatingReset` smallint(6) DEFAULT 0 COMMENT '重置評分下限',
  `ResetRate` smallint(6) DEFAULT 0 COMMENT '重置比例(千分比)',
  `MatchingRange` smallint(6) DEFAULT 0 COMMENT '匹配範圍',
  `NewRoomRate` smallint(6) DEFAULT 0 COMMENT '新房間機率(千分比)',
  `MaxMatchSecond` smallint(6) NOT NULL DEFAULT 0 COMMENT '最大匹配秒數',
  `ExtraMatchSecond` smallint(6) NOT NULL DEFAULT 0 COMMENT '延長匹配秒數',
  `MinMatchPlayers` tinyint(4) NOT NULL DEFAULT 0 COMMENT '最少匹配人數',
  `BaseRating` smallint(6) DEFAULT 0 COMMENT '起始評分',
  `MinRating` smallint(6) DEFAULT 0 COMMENT '評分下限',
  `MaxRating` smallint(6) DEFAULT 0 COMMENT '評分上限',
  `Score_1` smallint(6) DEFAULT 0 COMMENT '第一名基礎分數',
  `Score_2` smallint(6) DEFAULT 0 COMMENT '第二名基礎分數',
  `Score_3` smallint(6) DEFAULT 0 COMMENT '第三名基礎分數',
  `Score_4` smallint(6) DEFAULT 0 COMMENT '第四名基礎分數',
  `Score_5` smallint(6) DEFAULT 0 COMMENT '第五名基礎分數',
  `Score_6` smallint(6) DEFAULT 0 COMMENT '第六名基礎分數',
  `Score_7` smallint(6) DEFAULT 0 COMMENT '第七名基礎分數',
  `Score_8` smallint(6) DEFAULT 0 COMMENT '第八名基礎分數',
  `XValue` smallint(6) DEFAULT 0 COMMENT 'X值',
  `YValue` smallint(6) DEFAULT 0 COMMENT 'Y值',
  `KValue` smallint(6) DEFAULT 0 COMMENT 'K值',
  `Delta` smallint(6) DEFAULT 0 COMMENT 'Delta值',
  `BOT` tinyint(4) NOT NULL DEFAULT 0 COMMENT '最多加入機器人數',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='個人賽排名規則集合';

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='排行榜榜單資訊。';

CREATE TABLE IF NOT EXISTS `SeasonRankingRewardNew` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `SeasonID` int(11) NOT NULL DEFAULT 0 COMMENT '賽季ID',
  `SeasonName` varchar(50) NOT NULL DEFAULT '0' COMMENT '賽季名稱',
  `Rank` smallint(6) NOT NULL DEFAULT 0 COMMENT '排名',
  `RewarID` int(11) NOT NULL DEFAULT 0 COMMENT '獎勵編號',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `SeasonID_Rank` (`SeasonID`,`Rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新版賽季獎勵表。';

