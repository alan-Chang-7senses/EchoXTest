USE `koa_main`;

-- 傾印  資料表 koa_main.PointOrderIncomplete 結構
CREATE TABLE IF NOT EXISTS `PointOrderIncomplete` (
  `OrderID` bigint(20) unsigned zerofill NOT NULL DEFAULT 00000000000000000000 COMMENT '訂單表流水號',
  `UserID` int(11) NOT NULL COMMENT '使用者編號',
  `ProcessStatus` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0.未處理, 1.處理中, 2.已完成',
  PRIMARY KEY (`OrderID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='未成功之訂單紀錄，用於每次刷新點數時重新發送訂單。';


CREATE TABLE IF NOT EXISTS `UserPointOrder` (
  `OrderID` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT '訂單編號',
  `Symbol` varchar(50) NOT NULL DEFAULT '' COMMENT '點數種類代號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `Username` varchar(50) NOT NULL DEFAULT '' COMMENT 'Metasens使用者編號',
  `Amount` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '愈修改點數數量',
  `LogTime` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  `OrderType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '訂單種類。1.加點, 2.扣點',
  `CallbackStatus` varchar(50) DEFAULT NULL COMMENT 'Metasens回呼狀態(僅扣點需求)',
  `RedirectURL` text DEFAULT NULL COMMENT '完成訂單導頁連結(僅扣點需求)',
  PRIMARY KEY (`OrderID`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='點數系統：加點、扣點等紀錄。';

USE `koa_log`;

-- 傾印  資料表 koa_log.PointOrder 結構
CREATE TABLE IF NOT EXISTS `PointOrder` (
  `SerialNumber` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `OrderID` varchar(50) NOT NULL DEFAULT '' COMMENT '訂單編號',
  `Symbol` varchar(50) NOT NULL DEFAULT '' COMMENT '點數種類代號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `Username` varchar(50) NOT NULL DEFAULT '' COMMENT 'Metasens使用者編號',
  `Amount` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '愈修改點數數量',
  `LogTime` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  `OrderType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '訂單種類。1.加點, 2.扣點',
  `OrderStatus` varchar(50) DEFAULT NULL COMMENT '訂單狀態(回應值)',
  `RespondCode` varchar(50) DEFAULT NULL COMMENT '回應代號',
  `Message` varchar(50) DEFAULT NULL COMMENT '回應訊息',
  `RespondOrderID` varchar(255) DEFAULT NULL COMMENT 'Metasens訂單編號',
  `RespondAmount` varchar(50) DEFAULT NULL COMMENT '回應點數修改數量',
  `CallbackStatus` varchar(50) DEFAULT NULL COMMENT 'Metasens回呼狀態(僅扣點需求)',
  `RedirectURL` text DEFAULT NULL COMMENT '完成訂單導頁連結(僅扣點需求)',
  PRIMARY KEY (`SerialNumber`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='點數系統：加點、扣點等紀錄。';