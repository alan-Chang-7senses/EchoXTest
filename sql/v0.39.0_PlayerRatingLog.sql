USE `koa_log`;

-- 傾印  資料表 koa_log.PlayerRating 結構
CREATE TABLE IF NOT EXISTS `PlayerRating` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT 0,
  `PlayerID` bigint(20) NOT NULL DEFAULT 0,
  `SeasonID` int(11) NOT NULL DEFAULT 0,
  `Lobby` tinyint(4) NOT NULL DEFAULT 0,
  `RaceRank` tinyint(4) NOT NULL DEFAULT 0 COMMENT '該比賽名次',
  `RaceID` int(11) NOT NULL DEFAULT 0,
  `RatingPrevious` smallint(6) NOT NULL DEFAULT 0 COMMENT '賽前積分',
  `RatingCurrent` smallint(6) NOT NULL DEFAULT 0 COMMENT '賽後積分',
  `LogTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`),
  KEY `SeasonID` (`SeasonID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='玩家積分紀錄';