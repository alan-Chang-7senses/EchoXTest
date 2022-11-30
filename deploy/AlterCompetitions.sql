
USE `koa_static`;

DROP TABLE `CompetitionsInfo`;

CREATE TABLE IF NOT EXISTS `CompetitionsInfo` (
  `Lobby` tinyint(4) NOT NULL COMMENT '賽制編號',
  `Explain` varchar(50) DEFAULT NULL COMMENT '賽制識別字串(企劃用)',
  `MinRatingReset` smallint(6) NOT NULL DEFAULT 0 COMMENT '重置評分下限',
  `ResetRate` smallint(6) NOT NULL DEFAULT 0 COMMENT '重置比例(千分比)',
  `MatchingRange` smallint(6) NOT NULL DEFAULT 0 COMMENT '匹配範圍',
  `NewRoomRate` smallint(6) NOT NULL DEFAULT 0 COMMENT '新房間機率(千分比)',
  `MaxMatchSecond` smallint(6) NOT NULL DEFAULT 0 COMMENT '最大匹配秒數',
  `ExtraMatchSecond` smallint(6) NOT NULL DEFAULT 0 COMMENT '延長匹配秒數',
  `MinMatchPlayers` tinyint(4) NOT NULL DEFAULT 0 COMMENT '最少匹配人數',
  `BaseRating` smallint(6) NOT NULL DEFAULT 0 COMMENT '起始評分',
  `MinRating` smallint(6) NOT NULL DEFAULT 0 COMMENT '評分下限',
  `MaxRating` smallint(6) NOT NULL DEFAULT 0 COMMENT '評分上限',
  `Score_1` smallint(6) NOT NULL DEFAULT 0 COMMENT '第一名基礎分數',
  `Score_2` smallint(6) NOT NULL DEFAULT 0 COMMENT '第二名基礎分數',
  `Score_3` smallint(6) NOT NULL DEFAULT 0 COMMENT '第三名基礎分數',
  `Score_4` smallint(6) NOT NULL DEFAULT 0 COMMENT '第四名基礎分數',
  `Score_5` smallint(6) NOT NULL DEFAULT 0 COMMENT '第五名基礎分數',
  `Score_6` smallint(6) NOT NULL DEFAULT 0 COMMENT '第六名基礎分數',
  `Score_7` smallint(6) NOT NULL DEFAULT 0 COMMENT '第七名基礎分數',
  `Score_8` smallint(6) NOT NULL DEFAULT 0 COMMENT '第八名基礎分數',
  `XValue` smallint(6) NOT NULL DEFAULT 0 COMMENT 'X值',
  `YValue` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Y值',
  `KValue` smallint(6) NOT NULL DEFAULT 0 COMMENT 'K值',
  `Delta` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Delta值',
  `BOT` tinyint(4) NOT NULL DEFAULT 0 COMMENT '最多加入機器人數',
  `TicketId` int(11) NOT NULL DEFAULT 0 COMMENT '門票物品編號',
  `TicketCost` smallint(6) NOT NULL DEFAULT 0 COMMENT '單局消耗門票數量',
  `Treshold` smallint(6) NOT NULL DEFAULT 0 COMMENT '上榜門檻',
  PRIMARY KEY (`Lobby`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='個人賽排名規則集合';

-- 正在傾印表格  koa_static.CompetitionsInfo 的資料：~2 rows (近似值)
INSERT INTO `CompetitionsInfo` (`Lobby`, `Explain`, `MinRatingReset`, `ResetRate`, `MatchingRange`, `NewRoomRate`, `MaxMatchSecond`, `ExtraMatchSecond`, `MinMatchPlayers`, `BaseRating`, `MinRating`, `MaxRating`, `Score_1`, `Score_2`, `Score_3`, `Score_4`, `Score_5`, `Score_6`, `Score_7`, `Score_8`, `XValue`, `YValue`, `KValue`, `Delta`, `BOT`, `TicketId`, `TicketCost`, `Treshold`) VALUES
	(1, '火星幣賽(A)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 8, 5100, 1, 1),
	(2, 'PT幣賽(A)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 0, 5201, 1, 1),
	(3, '練習賽', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(4, '火星幣賽(A)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 8, 5100, 1, 1),
	(5, 'PT幣賽(B)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 0, 5201, 1, 1),
	(6, 'PVE', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

	