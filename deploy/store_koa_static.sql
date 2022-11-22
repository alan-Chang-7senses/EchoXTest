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


-- 傾印 koa_static 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_static` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_static`;

-- 傾印  資料表 koa_static.StoreCounters 結構
CREATE TABLE IF NOT EXISTS `StoreCounters` (
  `CIndex` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '索引值',
  `GroupID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '群組',
  `CounterID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '專櫃Id',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '商品Id',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品數量',
  `Inventory` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '庫存',
  `Price` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '售價',
  `Currency` int(11) NOT NULL DEFAULT 0 COMMENT '售價貨幣',
  `Promotion` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '促銷類型',
  PRIMARY KEY (`CIndex`),
  KEY `GroupID` (`GroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='一般商店';

-- 正在傾印表格  koa_static.StoreCounters 的資料：~15 rows (近似值)
/*!40000 ALTER TABLE `StoreCounters` DISABLE KEYS */;
INSERT INTO `StoreCounters` (`CIndex`, `GroupID`, `CounterID`, `ItemID`, `Amount`, `Inventory`, `Price`, `Currency`, `Promotion`) VALUES
	(1, 2001, 2000, 5100, 1, 3, 600, -2, 0),
	(2, 2001, 2000, -2, 1800, 1, 0, -2, 0),
	(3, 2001, 2000, 4012, 1, 1, 0, -2, 0),
	(4, 2001, 2001, 4008, 1, 99, 2373, -2, 3),
	(5, 2001, 2001, 4009, 1, 99, 678, -2, 3),
	(6, 2001, 2001, 4010, 1, 99, 7119, -2, 3),
	(7, 2001, 2001, 4011, 1, 99, 12542, -2, 3),
	(8, 2001, 2001, 4008, 1, 99, 2373, -2, 5),
	(9, 2001, 2001, 4009, 1, 99, 678, -2, 5),
	(10, 2001, 2001, 4010, 1, 99, 7119, -2, 5),
	(11, 2001, 2001, 4011, 1, 99, 12542, -2, 5),
	(12, 2001, 2001, 4008, 1, 99, 2373, -2, 0),
	(13, 2001, 2001, 4009, 1, 99, 678, -2, 0),
	(14, 2001, 2001, 4010, 1, 99, 7119, -2, 0),
	(15, 2001, 2001, 4011, 1, 99, 12542, -2, 0),
	(16, 2002, 2002, -2, 1000, 99, 240, -3, 0),
	(17, 2002, 2002, -2, 10000, 99, 2200, -3, 0),
	(18, 2002, 2002, -2, 100000, 99, 20000, -3, 0),
	(19, 2002, 2003, 1001, 1, 99, 3, -3, 0),
	(20, 2002, 2003, 1002, 1, 99, 17, -3, 0),
	(21, 2002, 2003, 1003, 1, 99, 47, -3, 0),
	(22, 2002, 2003, 1111, 1, 99, 7, -3, 0),
	(23, 2002, 2003, 1112, 1, 99, 71, -3, 0),
	(24, 2002, 2003, 1121, 1, 99, 7, -3, 0),
	(25, 2002, 2003, 1122, 1, 99, 71, -3, 0),
	(26, 2002, 2003, 1131, 1, 99, 7, -3, 0),
	(27, 2002, 2003, 1132, 1, 99, 71, -3, 0),
	(28, 2002, 2003, 2000, 1, 99, 125, -3, 0),
	(29, 2002, 2003, 2011, 1, 99, 125, -3, 0),
	(30, 2002, 2003, 2014, 1, 99, 125, -3, 0),
	(31, 2002, 2003, 2014, 1, 99, 125, -3, 0),
	(32, 2002, 2003, 2017, 1, 99, 125, -3, 0),
	(33, 2002, 2003, 2016, 1, 99, 125, -3, 0),
	(34, 2002, 2003, 2015, 1, 99, 125, -3, 0),
	(35, 2002, 2003, 2002, 1, 99, 125, -3, 0),
	(36, 2002, 2003, 2014, 1, 99, 125, -3, 0);
/*!40000 ALTER TABLE `StoreCounters` ENABLE KEYS */;

-- 傾印  資料表 koa_static.StoreData 結構
CREATE TABLE IF NOT EXISTS `StoreData` (
  `StoreID` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '商店編號',
  `IsOpen` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '是否開放',
  `MultiName` varchar(20) NOT NULL DEFAULT '' COMMENT '商店名稱(多國語言編號)',
  `StoreType` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '商店類型',
  `UIStyle` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '介面類型',
  `FixedGroup` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '固定商品專櫃群組',
  `StochasticGroup` int(10) unsigned DEFAULT 0 COMMENT '隨機商品專櫃群組',
  `RefreshCount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '每日刷新次數',
  `RefreshCost` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '刷新費用',
  `RefreshCostCurrency` int(11) NOT NULL DEFAULT 0 COMMENT '刷新費用之貨幣',
  PRIMARY KEY (`StoreID`),
  KEY `IsOpen` (`IsOpen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商店資訊';

-- 正在傾印表格  koa_static.StoreData 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `StoreData` DISABLE KEYS */;
INSERT INTO `StoreData` (`StoreID`, `IsOpen`, `MultiName`, `StoreType`, `UIStyle`, `FixedGroup`, `StochasticGroup`, `RefreshCount`, `RefreshCost`, `RefreshCostCurrency`) VALUES
	(1, 1, '', 1, 3, 2000, 2001, 10, 100, -2),
	(2, 1, '', 1, 4, 2002, 2003, 10, 10, -3),
	(3, 1, '', 2, 1, 2004, NULL, 0, 0, 0),
	(4, 1, '', 3, 1, 2005, NULL, 0, 0, 0),
	(5, 1, '', 4, 1, 2006, NULL, 0, 0, 0);
/*!40000 ALTER TABLE `StoreData` ENABLE KEYS */;

-- 傾印  資料表 koa_static.StoreProductInfo 結構
CREATE TABLE IF NOT EXISTS `StoreProductInfo` (
  `Serial` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `ProductID` varchar(50) NOT NULL DEFAULT '' COMMENT '商品Key',
  `MultiNo` varchar(50) DEFAULT '' COMMENT '產品名稱(多語系編號)',
  `Price` float(10,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '售價',
  `ISOCurrency` varchar(10) NOT NULL DEFAULT '' COMMENT '貨幣',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `ProductID_ISOCurrency` (`ProductID`,`ISOCurrency`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='儲值商店品項資訊';

-- 正在傾印表格  koa_static.StoreProductInfo 的資料：~8 rows (近似值)
/*!40000 ALTER TABLE `StoreProductInfo` DISABLE KEYS */;
INSERT INTO `StoreProductInfo` (`Serial`, `ProductID`, `MultiNo`, `Price`, `ISOCurrency`) VALUES
	(1, 'mycard_1', '品項_1_台幣', 30.00, 'TWD'),
	(2, 'mycard_2', '品項_2_台幣', 300.00, 'TWD'),
	(3, 'mycard_3', '品項_3_台幣', 600.00, 'TWD'),
	(4, 'mycard_4', '品項_4_台幣', 1800.00, 'TWD');
/*!40000 ALTER TABLE `StoreProductInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.StorePurchase 結構
CREATE TABLE IF NOT EXISTS `StorePurchase` (
  `PIndex` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '索引值',
  `GroupID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '群組',
  `PurchaseID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '課金Id',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '商品Id',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品數量',
  `ProductID` varchar(20) NOT NULL DEFAULT '0' COMMENT '商品ProductID',
  PRIMARY KEY (`PIndex`),
  KEY `GroupID` (`GroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='儲值商店';

-- 正在傾印表格  koa_static.StorePurchase 的資料：~11 rows (近似值)
/*!40000 ALTER TABLE `StorePurchase` DISABLE KEYS */;
INSERT INTO `StorePurchase` (`PIndex`, `GroupID`, `PurchaseID`, `ItemID`, `Amount`, `ProductID`) VALUES
	(1, 1, 1001, -3, 800, 'mycard_1'),
	(2, 1, 1002, -3, 7000, 'mycard_2'),
	(3, 1, 1003, -3, 15000, 'mycard_3'),
	(4, 1, 1004, -3, 50000, 'mycard_4'),
	(5, 2, 1011, -3, 800, 'APPSTORE字串'),
	(6, 2, 1012, -3, 7000, 'APPSTORE字串'),
	(7, 2, 1013, -3, 15000, 'APPSTORE字串'),
	(8, 2, 1014, -3, 50000, 'APPSTORE字串'),
	(9, 3, 1021, -3, 800, 'GOOGLEPLAY字串'),
	(10, 3, 1022, -3, 7000, 'GOOGLEPLAY字串'),
	(11, 3, 1023, -3, 15000, 'GOOGLEPLAY字串'),
	(12, 3, 1024, -3, 50000, 'GOOGLEPLAY字串');
/*!40000 ALTER TABLE `StorePurchase` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
