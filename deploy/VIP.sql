
USE `koa_static`;

-- 傾印  資料表 koa_static.VIPPrivilege 結構
CREATE TABLE IF NOT EXISTS `VIPPrivilege` (
  `VIPRank` int(11) NOT NULL DEFAULT 0 COMMENT 'VIP等級',
  `Diamond` int(11) NOT NULL DEFAULT 0 COMMENT '儲值鑽石數',
  `PowerLimit` int(11) NOT NULL DEFAULT 0 COMMENT '體力上限',
  `PowerRate` int(11) NOT NULL DEFAULT 0 COMMENT '每一點體力回復秒數',
  `UCGChain` tinyint(4) NOT NULL DEFAULT 0 COMMENT '可否上鍊匯出',
  `TicketCoin` tinyint(4) NOT NULL DEFAULT 0 COMMENT '每日發PVP練習券(晉級金幣賽)',
  `TicketCoinLimit` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'PVP練習券上限(晉級金幣賽)',
  `TicketQualifying` tinyint(4) NOT NULL DEFAULT 0 COMMENT '每日發PVP邀請券(晉級UCG賽)',
  `TicketQualifyingLimit` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'PVP邀請券上限(晉級UCG賽)',
  `TicketMaxLevel` int(11) NOT NULL DEFAULT 0 COMMENT '每日發PVP門票(滿等技術賽)',
  `TicketMaxLevelLimit` int(11) NOT NULL DEFAULT 0 COMMENT 'PVP門票上限(滿等技術賽)',
  `FreePlayer` int(11) DEFAULT NULL COMMENT '免費Peta領取',
  `PVESpeedRun` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'PVE掃蕩權限',
  PRIMARY KEY (`VIPRank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='VIP用戶權限表';

INSERT INTO `VIPPrivilege` (`VIPRank`, `Diamond`, `PowerLimit`, `PowerRate`, `UCGChain`, `TicketCoin`, `TicketCoinLimit`, `TicketQualifying`, `TicketQualifyingLimit`, `TicketMaxLevel`, `TicketMaxLevelLimit`, `FreePlayer`, `PVESpeedRun`) VALUES
	(0, 0, 100, 1200, 0, 1, 3, 0, 0, 0, 0, NULL, 0),
	(1, 1, 100, 1200, 0, 1, 4, 0, 0, 0, 0, NULL, 1),
	(2, 100, 100, 1150, 0, 1, 5, 0, 0, 0, 0, NULL, 1),
	(3, 600, 100, 1100, 0, 2, 6, 0, 0, 0, 0, NULL, 1),
	(4, 3000, 100, 1050, 0, 2, 7, 0, 0, 0, 0, NULL, 1),
	(5, 6000, 100, 1000, 0, 2, 8, 0, 0, 0, 0, NULL, 1),
	(6, 10000, 150, 900, 1, 3, 9, 1, 3, 0, 0, NULL, 1),
	(7, 30000, 150, 850, 1, 4, 10, 2, 5, 0, 0, NULL, 1),
	(8, 50000, 200, 800, 1, 5, 15, 3, 10, 1, 3, NULL, 1),
	(9, 100000, 200, 750, 1, 6, 20, 4, 12, 1, 4, NULL, 1),
	(10, 150000, 200, 700, 1, 7, 25, 5, 15, 1, 5, NULL, 1),
	(11, 180000, 250, 650, 1, 8, 30, 6, 20, 2, 6, NULL, 1),
	(12, 210000, 250, 600, 1, 9, 35, 7, 25, 2, 7, NULL, 1),
	(13, 240000, 250, 500, 1, 10, 40, 8, 30, 2, 8, NULL, 1),
	(14, 270000, 250, 400, 1, 11, 45, 9, 40, 3, 9, NULL, 1),
	(15, 300000, 300, 300, 1, 12, 50, 10, 50, 4, 10, NULL, 1);

USE `koa_main`;

-- 傾印  資料表 koa_main.UserDiamond 結構
CREATE TABLE IF NOT EXISTS `UserDiamond` (
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `AccumulateDiamond` int(11) NOT NULL DEFAULT 0 COMMENT '累計儲值鑽石(透過金流儲值)',
  `UpdateTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='玩家鑽石儲值訊息';



