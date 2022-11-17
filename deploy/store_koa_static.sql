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
	(1, 1, 1001, 1001, 1, 3, 100, 1, 3),
	(2, 1, 1001, 1001, 1, 3, 100, 1, 3),
	(3, 1, 1001, 1001, 1, 3, 100, 1, 3),
	(4, 1, 1002, 1001, 1, 3, 100, 1, 3),
	(5, 1, 1003, 1001, 1, 3, 100, 1, 3),
	(6, 2, 2001, 1001, 2, 3, 100, 1, 3),
	(7, 2, 2001, 1001, 3, 3, 100, 1, 3),
	(8, 2, 2001, 1001, 4, 3, 100, 1, 3),
	(9, 2, 2002, 1001, 1, 3, 100, 1, 3),
	(10, 2, 2003, 1001, 1, 3, 100, 1, 3),
	(11, 2, 2005, 1001, 1, 3, 100, 1, 3),
	(12, 2, 2005, 1001, 1, 3, 100, 1, 3),
	(13, 2, 2005, 1001, 1, 3, 100, 1, 3),
	(14, 2, 2005, 1001, 1, 3, 100, 1, 3),
	(15, 2, 2005, 1001, 1, 3, 100, 1, 3);
/*!40000 ALTER TABLE `StoreCounters` ENABLE KEYS */;

-- 傾印  資料表 koa_static.StoreData 結構
CREATE TABLE IF NOT EXISTS `StoreData` (
  `StoreID` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '商店編號',
  `IsOpen` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '是否開放',
  `MultiName` varchar(20) NOT NULL DEFAULT '' COMMENT '商店名稱(多國語言編號)',
  `StoreType` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '商店類型',
  `UIStyle` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '介面類型',
  `FixedGroup` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '固定商品專櫃群組',
  `StochasticGroup` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '隨機商品專櫃群組',
  `RefreshCount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '每日刷新次數',
  `RefreshCost` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '刷新費用',
  `RefreshCostCurrency` int(11) NOT NULL DEFAULT 0 COMMENT '刷新費用之貨幣',
  PRIMARY KEY (`StoreID`),
  KEY `IsOpen` (`IsOpen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商店資訊';

-- 正在傾印表格  koa_static.StoreData 的資料：~6 rows (近似值)
/*!40000 ALTER TABLE `StoreData` DISABLE KEYS */;
INSERT INTO `StoreData` (`StoreID`, `IsOpen`, `MultiName`, `StoreType`, `UIStyle`, `FixedGroup`, `StochasticGroup`, `RefreshCount`, `RefreshCost`, `RefreshCostCurrency`) VALUES
	(3, 1, 'testA', 2, 3, 2, 5, 3, 100, 2),
	(4, 1, '一般商店', 1, 3, 1, 6, 3, 200, 2),
	(9, 0, '一般商店', 1, 0, 0, 0, 0, 0, 0),
	(100, 0, 'Mycard商店', 4, 1, 1, 0, 0, 0, 0),
	(200, 1, 'Mycard商店', 4, 1, 1, 0, 0, 0, 0),
	(300, 1, 'testA', 3, 2, 1, 2, 6, 50, 1);
/*!40000 ALTER TABLE `StoreData` ENABLE KEYS */;

-- 傾印  資料表 koa_static.StoreProductInfo 結構
CREATE TABLE IF NOT EXISTS `StoreProductInfo` (
  `Serial` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `ProductID` varchar(50) NOT NULL DEFAULT '' COMMENT '商品Key',
  `MultiNo` varchar(50) DEFAULT '' COMMENT '產品名稱(多語系編號)',
  `Price` float unsigned NOT NULL DEFAULT 0 COMMENT '售價',
  `ISOCurrency` varchar(10) NOT NULL DEFAULT '' COMMENT '貨幣',
  PRIMARY KEY (`Serial`),
  UNIQUE KEY `ProductID_ISOCurrency` (`ProductID`,`ISOCurrency`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='儲值商店品項資訊';

-- 正在傾印表格  koa_static.StoreProductInfo 的資料：~8 rows (近似值)
/*!40000 ALTER TABLE `StoreProductInfo` DISABLE KEYS */;
INSERT INTO `StoreProductInfo` (`Serial`, `ProductID`, `MultiNo`, `Price`, `ISOCurrency`) VALUES
	(1, '003', '測試003', 1.001, 'TWD'),
	(2, '005', '測試005', 1, 'TWD'),
	(3, '008', '測試008', 1, 'TWD'),
	(5, '006', '測試006', 1, 'TWD'),
	(7, '001', '測試001', 1.01, 'TWD'),
	(8, '004', '測試004', 1, 'TWD'),
	(9, '002', '測試002', 1, 'TWD'),
	(10, '007', '測試007', 1, 'TWD');
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
	(1, 1, 1001, -3, 1, '001'),
	(2, 1, 1001, -3, 1, '002'),
	(3, 1, 1002, -3, 1, '003'),
	(4, 1, 1002, -3, 1, '004'),
	(5, 1, 1003, -3, 1, '005'),
	(6, 1, 1004, -3, 1, '006'),
	(7, 1, 1005, -3, 1, '007'),
	(8, 1, 1006, -3, 1, '008'),
	(9, 1, 1006, -3, 1, '008'),
	(10, 1, 1006, -3, 1, '008'),
	(11, 1, 1006, -3, 1, '008'),
	(12, 1, 1006, -3, 1, '008');
/*!40000 ALTER TABLE `StorePurchase` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
