-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.9.3-MariaDB-1:10.9.3+maria~ubu2204 - mariadb.org binary distribution
-- 伺服器作業系統:                      debian-linux-gnu
-- HeidiSQL 版本:                  11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

USE `koa_main`;

INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES
	('MyCardRestoreQueryTime', '1200', 'MyCard補儲查詢時間(秒),小於31天');	
	
-- 傾印  資料表 koa_main.StorePurchaseOrders 結構
DROP TABLE IF EXISTS `StorePurchaseOrders`;
CREATE TABLE IF NOT EXISTS `StorePurchaseOrders` (
  `OrderID` varchar(250) NOT NULL DEFAULT '' COMMENT '訂單編號',
  `UserID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `TradeID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '交易序號',
  `ProductID` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品Key',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '商品物品Id',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品數量',
  `Plat` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '平台',
  `Status` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '狀態',
  `Message` varchar(250) NOT NULL DEFAULT '' COMMENT '狀態資訊',
  `Receipt` varchar(256) DEFAULT NULL COMMENT '收據或MyCard序號',
  `AuthCode` varchar(256) DEFAULT NULL COMMENT 'MyCard AuthCode',
  `CreateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`OrderID`),
  KEY `UserID` (`UserID`),
  KEY `Status` (`Status`),
  KEY `Plat` (`Plat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='儲值訂單資訊';

-- 取消選取資料匯出。

ALTER TABLE `RaceRooms`
	CHANGE COLUMN `Version` `Version` VARCHAR(100) NOT NULL DEFAULT '0' COMMENT 'Photon版本' COLLATE 'utf8mb4_general_ci' AFTER `Lobby`;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
