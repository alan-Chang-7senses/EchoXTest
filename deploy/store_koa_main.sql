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

-- 傾印  資料表 koa_main.StorePurchaseOrders 結構
CREATE TABLE IF NOT EXISTS `StorePurchaseOrders` (
  `OrderID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '訂單編號',
  `UserID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `TradeID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '交易序號',
  `ProductID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `ItemID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品物品Id',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品數量',
  `Plat` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '平台',
  `Status` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '狀態',
  `Receipt` varchar(50) DEFAULT NULL COMMENT '收據',
  `CreateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`OrderID`),
  KEY `UserID` (`UserID`),
  KEY `Receipt` (`Receipt`),
  KEY `Status` (`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='儲值訂單資訊';

-- 正在傾印表格  koa_main.StorePurchaseOrders 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `StorePurchaseOrders` DISABLE KEYS */;
/*!40000 ALTER TABLE `StorePurchaseOrders` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
