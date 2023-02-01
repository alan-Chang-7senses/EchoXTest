-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.8.3-MariaDB-1:10.8.3+maria~jammy - mariadb.org binary distribution
-- 伺服器作業系統:                      debian-linux-gnu
-- HeidiSQL 版本:                  11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- 傾印 koa_log 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_log` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_log`;

-- 傾印  資料表 koa_log.BaseProcess 結構
CREATE TABLE IF NOT EXISTS `BaseProcess` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(10) unsigned NOT NULL COMMENT '使用者ID',
  `UserIP` varchar(255) NOT NULL DEFAULT '' COMMENT '使用者IP',
  `RedirectURL` varchar(255) NOT NULL COMMENT '執行網址',
  `Content` longtext NOT NULL COMMENT '內容',
  `Result` tinyint(4) NOT NULL DEFAULT 0 COMMENT '處理結果(1=成功)',
  `ResultData` longtext DEFAULT NULL COMMENT '處理結果資料',
  `HttpCode` varchar(5) DEFAULT NULL COMMENT 'HTTP回應狀態碼',
  `Message` text DEFAULT NULL COMMENT '處理結果訊息',
  `BeginTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '起始時間',
  `RecordTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`,`RecordTime`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='基礎處理 log';

-- 傾印  資料表 koa_log.MyCardPayment 結構
CREATE TABLE IF NOT EXISTS `MyCardPayment` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `PaymentType` varchar(50) NOT NULL DEFAULT '' COMMENT '付費方式',
  `TradeSeq` varchar(50) NOT NULL DEFAULT '' COMMENT 'MyCard 交易序',
  `MyCardTradeNo` varchar(50) NOT NULL DEFAULT '' COMMENT '交易序號',
  `FacTradeSeq` varchar(250) NOT NULL DEFAULT '' COMMENT '廠商交易序號',
  `CustomerId` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '支付金額',
  `Currency` varchar(50) NOT NULL DEFAULT '' COMMENT '支付的幣種',
  `TradeDateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  `CreateAccountDateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '創立帳號時間',
  `CreateAccountIP` varchar(50) NOT NULL DEFAULT '' COMMENT '創立帳號 IP',
  PRIMARY KEY (`Serial`),
  KEY `TradeDateTime` (`TradeDateTime`),
  KEY `MyCardTradeNo` (`MyCardTradeNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='儲值資訊';

-- 傾印  資料表 koa_log.NFTCreatePlayer 結構
CREATE TABLE IF NOT EXISTS `NFTCreatePlayer` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `PlayerID` bigint(20) NOT NULL COMMENT '角色編號',
  `UserID` int(11) NOT NULL COMMENT '使用者編號',
  `MetadataURL` varchar(255) NOT NULL DEFAULT '' COMMENT '角色Metadata連結',
  `LogTime` int(11) NOT NULL COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='NFT角色創建紀錄';

-- 傾印  資料表 koa_log.NFTItemLog 結構
CREATE TABLE IF NOT EXISTS `NFTItemLog` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `Email` varchar(255) NOT NULL DEFAULT '0' COMMENT '電子信箱',
  `Payload` text NOT NULL COMMENT '平台Payload',
  `IsCompleted` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否完成交易,完成為1',
  `Message` text DEFAULT NULL COMMENT '交易未完成原因訊息',
  `Timestamp` int(10) unsigned NOT NULL COMMENT '交易平台的時間戳',
  `CreateTime` int(10) unsigned NOT NULL COMMENT '紀錄建立時間',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 傾印  資料表 koa_log.NFTOwnershipTransfer 結構
CREATE TABLE IF NOT EXISTS `NFTOwnershipTransfer` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `NewOnwerUserID` int(11) NOT NULL DEFAULT 0 COMMENT '轉入使用者編號(0為未知使用者)',
  `OldOnwerUserID` int(11) NOT NULL DEFAULT 0 COMMENT '轉出使用者編號(0為未知使用者)',
  `PlayerID` bigint(20) NOT NULL COMMENT '角色編號',
  `LogTime` int(11) NOT NULL COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='NFT所有權轉移紀錄';

-- 傾印  資料表 koa_log.PlatPayment 結構
CREATE TABLE IF NOT EXISTS `PlatPayment` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `PlatType` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '付費平台',
  `TransactionID` varchar(255) NOT NULL DEFAULT '' COMMENT '平台交易訊號',
  `PlatOrderID` varchar(255) NOT NULL DEFAULT '' COMMENT '平台商品交易序號',
  `OrderID` varchar(255) NOT NULL DEFAULT '' COMMENT '商品下單序號',
  `Amount` float unsigned NOT NULL DEFAULT 0 COMMENT '支付金額',
  `Currency` varchar(255) NOT NULL DEFAULT '' COMMENT '支付的幣種',
  `TradeDateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '時間',
  PRIMARY KEY (`Serial`),
  KEY `OrderID` (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='一般儲值資訊';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='玩家積分紀錄';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='點數系統：加點、扣點等紀錄。';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='電力消耗log';

-- 傾印  資料表 koa_log.PVECleared 結構
CREATE TABLE IF NOT EXISTS `PVECleared` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT 0,
  `PlayerID` bigint(20) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `LevelID` int(11) NOT NULL DEFAULT 0,
  `Items` text NOT NULL DEFAULT '',
  `SyncRate` int(11) NOT NULL DEFAULT 0,
  `Ranking` tinyint(4) NOT NULL DEFAULT 0,
  `ClearCount` smallint(6) NOT NULL DEFAULT 0,
  `StartTime` int(11) NOT NULL DEFAULT 0,
  `FinishTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PVE通關紀錄。';

-- 傾印  資料表 koa_log.SeasonRankingReward 結構
CREATE TABLE IF NOT EXISTS `SeasonRankingReward` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `SeasonID` int(11) NOT NULL COMMENT '賽季編號',
  `Lobby` tinyint(4) NOT NULL DEFAULT 0 COMMENT '賽制大廳',
  `Ranking` mediumint(9) NOT NULL DEFAULT 0 COMMENT '排名',
  `UserID` int(10) NOT NULL DEFAULT 0,
  `PlayerID` bigint(20) NOT NULL,
  `Content` text NOT NULL COMMENT '內容',
  `LogTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`,`LogTime`),
  KEY `SeasonID` (`SeasonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='賽季排名獎勵紀錄';

-- 傾印  資料表 koa_log.Upgrade 結構
CREATE TABLE IF NOT EXISTS `Upgrade` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `PlayerID` bigint(20) NOT NULL DEFAULT 0,
  `SkillID` int(11) DEFAULT NULL COMMENT '技能ID',
  `SkillLevelDelta` int(11) NOT NULL DEFAULT 0 COMMENT '技能等級變化量',
  `CoinDelta` int(11) NOT NULL DEFAULT 0 COMMENT '金幣變化量',
  `BonusType` int(11) DEFAULT NULL COMMENT '強化加成類型',
  `ExpDelta` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色經驗值變化量',
  `RankDelta` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色階級變化量',
  `LogTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`,`LogTime`),
  KEY `PlayerID` (`PlayerID`),
  KEY `SkillID` (`SkillID`),
  KEY `RankDelta` (`RankDelta`),
  KEY `ExpDelta` (`ExpDelta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='升級、升階、升技能LOG';

-- 傾印  資料表 koa_log.UpgradeLevel 結構
CREATE TABLE IF NOT EXISTS `UpgradeLevel` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `PlayerID` bigint(20) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `CoinCost` mediumint(9) NOT NULL DEFAULT 0 COMMENT '金幣減少量',
  `BonusType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '經驗加成模式',
  `ExpAdd` mediumint(9) NOT NULL DEFAULT 0 COMMENT '經驗值獲得量',
  `Time` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色等級升級Log';

-- 傾印  資料表 koa_log.UpgradeRank 結構
CREATE TABLE IF NOT EXISTS `UpgradeRank` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `PlayerID` bigint(20) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `RankAdd` tinyint(4) NOT NULL DEFAULT 0 COMMENT '階級提升量',
  `CoinCost` mediumint(9) NOT NULL DEFAULT 0 COMMENT '金幣減少量',
  `Time` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='升階資訊Log';

-- 傾印  資料表 koa_log.UpgradeSkill 結構
CREATE TABLE IF NOT EXISTS `UpgradeSkill` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `PlayerID` bigint(20) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `SkillID` int(11) NOT NULL DEFAULT 0 COMMENT '技能編號',
  `SkillRankAdd` tinyint(4) NOT NULL DEFAULT 0 COMMENT '技能階級提升量',
  `CoinCost` mediumint(9) NOT NULL DEFAULT 0 COMMENT '金幣減少量',
  `Time` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能升級紀錄';

-- 傾印  資料表 koa_log.UserItemsLog 結構
CREATE TABLE IF NOT EXISTS `UserItemsLog` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserItemID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者物品編號',
  `UserID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '物品編號',
  `Cause` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '原因',
  `Action` tinyint(4) NOT NULL DEFAULT 0 COMMENT '動作',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '數量',
  `Remain` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '剩餘數量',
  `LogTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`,`LogTime`),
  KEY `UserItemID` (`UserItemID`),
  KEY `UserID` (`UserID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者物品紀錄';

-- 傾印  資料表 koa_log.UserLogin 結構
CREATE TABLE IF NOT EXISTS `UserLogin` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `UserIP` varchar(255) NOT NULL DEFAULT '' COMMENT '使用者IP',
  `LogTime` int(11) NOT NULL DEFAULT 0 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='使用者登入紀錄';

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
