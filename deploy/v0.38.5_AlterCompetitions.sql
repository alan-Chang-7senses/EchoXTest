
USE `koa_static`;


ALTER TABLE `CompetitionsInfo`
	ADD COLUMN `TicketId` INT(11) NOT NULL DEFAULT '0' COMMENT '門票物品編號' AFTER `BOT`,
	ADD COLUMN `TicketCost` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '單局消耗門票數量' AFTER `TicketId`,
	ADD COLUMN `Treshold` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '上榜門檻' AFTER `TicketCost`;

  

-- 正在傾印表格  koa_static.CompetitionsInfo 的資料：~2 rows (近似值)
INSERT INTO `CompetitionsInfo` (`ID`, `Explain`, `MinRatingReset`, `ResetRate`, `MatchingRange`, `NewRoomRate`, `MaxMatchSecond`, `ExtraMatchSecond`, `MinMatchPlayers`, `BaseRating`, `MinRating`, `MaxRating`, `Score_1`, `Score_2`, `Score_3`, `Score_4`, `Score_5`, `Score_6`, `Score_7`, `Score_8`, `XValue`, `YValue`, `KValue`, `Delta`, `BOT`, `TicketId`, `TicketCost`, `Treshold`) VALUES
	(1, '火星幣賽(A)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 8, 5100, 1, 1),
	(2, 'PT幣賽(A)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 0, 5201, 1, 1),
	(3, '練習賽', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(4, '火星幣賽(A)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 8, 5100, 1, 1),
	(5, 'PT幣賽(B)', 750, 500, 500, 250, 200, 120, 2, 1500, 500, 5000, 8, 7, 6, 5, 4, 3, 2, 1, 2000, 10, 2, 2, 0, 5201, 1, 1),
	(6, 'PVE', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

	