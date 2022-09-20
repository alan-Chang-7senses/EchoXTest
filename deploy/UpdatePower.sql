USE `koa_main`;

-- 傾印  資料表 koa_main.UserPower 結構
CREATE TABLE IF NOT EXISTS `UserPower` (
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者ID',
  `PowerUpdateTime` int(11) NOT NULL DEFAULT 0 COMMENT '電力更新時間',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者電力資訊';

USE `koa_log`;

-- 傾印  資料表 koa_log.PowerLog 結構
CREATE TABLE IF NOT EXISTS `PowerLog` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者ID',
  `BeforeChange` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '變化前電力',
  `AfterChange` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '變化後電力',
  `Cause` int(11) NOT NULL DEFAULT 0 COMMENT '使用原由',
  `PVELevel` int(11) DEFAULT NULL COMMENT '在哪個關卡使用',
  `LogTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`) USING BTREE,
  KEY `UserID` (`UserID`),
  KEY `Cause` (`Cause`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='電力消耗log';