
USE `koa_main`;

CREATE TABLE IF NOT EXISTS `EnergyRunOutBonus` (
  `RacePlayerID` int(11) NOT NULL DEFAULT 0 COMMENT '競賽角色編號',
  `BonusID` int(11) NOT NULL DEFAULT 0 COMMENT '獲得獎勵編號',
  `UpdateTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`RacePlayerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='能量耗盡時。取得之效果紀錄。';

