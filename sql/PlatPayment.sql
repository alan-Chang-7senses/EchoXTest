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

