-- --------------------------------------------------------
-- 主機:                           172.20.100.85
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


-- 傾印 koa_log 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_log` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_log`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='一般儲值資訊';

-- 正在傾印表格  koa_log.PlatPayment 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `PlatPayment` DISABLE KEYS */;
REPLACE INTO `PlatPayment` (`Serial`, `UserID`, `PlatType`, `TransactionID`, `PlatOrderID`, `OrderID`, `Amount`, `Currency`, `TradeDateTime`) VALUES
	(1, 11, 2, 'kiebcecodnlpidfbbbkkbefb.AO-J1Ow6QhbINFbBfAR5Wiltp4ufiz0MmJYf3Fxk9gsogiisj3KDBrnuYbbaHJ33rKBY0QlEki4a_JM9Jppm9cNrxbjSTqobng', 'GPA.3361-3388-9570-57464', '9b1517c9-cdde-406f-8799-c3f7cb304549', 2, 'TWD', 1674110899);
/*!40000 ALTER TABLE `PlatPayment` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
