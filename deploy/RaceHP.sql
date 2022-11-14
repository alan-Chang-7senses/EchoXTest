USE `koa_main`;

CREATE TABLE IF NOT EXISTS `RaceHP` (
  `RacePlayerID` int(11) NOT NULL DEFAULT 0,
  `HValue` float(10,2) NOT NULL DEFAULT 0.00,
  `UpdateTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`RacePlayerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='紀錄比賽每次更新之H值';