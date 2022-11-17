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


-- 傾印 koa_log 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_log` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_log`;

-- 傾印  資料表 koa_log.MyCardPayment 結構
CREATE TABLE IF NOT EXISTS `MyCardPayment` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `PaymentType` varchar(50) NOT NULL DEFAULT '' COMMENT '付費方式',
  `TradeSeq` varchar(50) NOT NULL DEFAULT '' COMMENT 'MyCard 交易序',
  `MyCardTradeNo` varchar(50) NOT NULL DEFAULT '' COMMENT '交易序號',
  `FacTradeSeq` varchar(50) NOT NULL DEFAULT '' COMMENT '廠商交易序號',
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


/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
