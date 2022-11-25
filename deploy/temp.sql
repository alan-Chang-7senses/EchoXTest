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

-- 正在傾印表格  koa_main.LeaderboardRating 的資料：~8 rows (近似值)
INSERT INTO `LeaderboardRating` (`Serial`, `SeasonID`, `Lobby`, `PlayerID`, `Rating`, `UpdateTime`) VALUES
	(2, 1, 1, 204, 1502, 1669024730),
	(3, 1, 1, 103, 1504, 1669024730),
	(4, 1, 1, 101, 1703, 1669025133),
	(5, 1, 1, 301, 1703, 1669025133),
	(6, 1, 1, 201, 1502, 1669025133),
	(7, 1, 2, 301, 1800, 0),
	(8, 1, 2, 201, 1400, 0),
	(9, 1, 2, 101, 1501, 0),
	(11, 1, 1, 401, 1501, 0),
	(12, 1, 1, 701, 1501, 0),
	(13, 2, 1, 103, 1507, 0);