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


-- 傾印 koa_main 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_main` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_main`;

-- 傾印  資料表 koa_main.StoreInfos 結構
CREATE TABLE IF NOT EXISTS `StoreInfos` (
  `StoreInfoID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商店資訊編號',
  `UserID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `StoreID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店編號',
  `FixTradIDs` varchar(50) DEFAULT NULL COMMENT '固定商品',
  `RandomTradIDs` varchar(50) DEFAULT NULL COMMENT '隨機商品',
  `RefreshRemainAmounts` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '剩餘刷新次數',
  `CreateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`StoreInfoID`),
  UNIQUE KEY `UserID_StoreID` (`UserID`,`StoreID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易商店資訊';

-- 正在傾印表格  koa_main.StoreInfos 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `StoreInfos` DISABLE KEYS */;
/*!40000 ALTER TABLE `StoreInfos` ENABLE KEYS */;

-- 傾印  資料表 koa_main.StorePurchaseOrders 結構
CREATE TABLE IF NOT EXISTS `StorePurchaseOrders` (
  `OrderID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '訂單編號',
  `UserID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `TradeID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '交易序號',
  `ProductID` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品Key',
  `ItemID` int(10) NOT NULL DEFAULT 0 COMMENT '商品物品Id',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品數量',
  `Plat` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '平台',
  `Status` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '狀態',
  `Receipt` varchar(256) DEFAULT NULL COMMENT '收據',
  `CreateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`OrderID`),
  KEY `UserID` (`UserID`),
  KEY `Status` (`Status`),
  KEY `Plat` (`Plat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='儲值訂單資訊';

-- 正在傾印表格  koa_main.StorePurchaseOrders 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `StorePurchaseOrders` DISABLE KEYS */;
/*!40000 ALTER TABLE `StorePurchaseOrders` ENABLE KEYS */;

-- 傾印  資料表 koa_main.StoreTrades 結構
CREATE TABLE IF NOT EXISTS `StoreTrades` (
  `TradeID` int(11) NOT NULL AUTO_INCREMENT COMMENT '交易序號',
  `UserID` int(10) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `StoreID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店編號',
  `Status` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '狀態',
  `StoreType` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店類型',
  `CPIndex` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店索引',
  `RemainInventory` int(10) NOT NULL DEFAULT 0 COMMENT '剩餘庫存量',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`TradeID`),
  KEY `Status` (`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易資訊';

-- 正在傾印表格  koa_main.StoreTrades 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `StoreTrades` DISABLE KEYS */;
/*!40000 ALTER TABLE `StoreTrades` ENABLE KEYS */;

-- 傾印  資料表 koa_main.StoreUserInfos 結構
CREATE TABLE IF NOT EXISTS `StoreUserInfos` (
  `UserID` int(10) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `Device` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '裝置',
  `Currency` varchar(10) NOT NULL DEFAULT '' COMMENT '使用幣別(ISO)',
  `Plat` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '儲值平台',
  `AutoRefreshTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店自動刷新時間',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易商店資訊';

-- 正在傾印表格  koa_main.StoreUserInfos 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `StoreUserInfos` DISABLE KEYS */;
/*!40000 ALTER TABLE `StoreUserInfos` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
