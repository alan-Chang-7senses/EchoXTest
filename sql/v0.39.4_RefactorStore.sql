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

-- 傾印  資料表 koa_main.StoreTrades 結構
CREATE TABLE IF NOT EXISTS `StoreTrades` (
  `TradeID` int(11) NOT NULL AUTO_INCREMENT COMMENT '交易序號',
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `StoreID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店編號',
  `Status` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '狀態',
  `StoreType` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店類型',
  `IsFix` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '是否為固定商品',
  `CPIndex` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商店索引',
  `RemainInventory` int(11) NOT NULL DEFAULT 0 COMMENT '剩餘庫存量',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`TradeID`),
  KEY `Status` (`Status`),
  KEY `IsFix` (`IsFix`),
  KEY `StoreType` (`StoreType`),
  KEY `StoreID` (`StoreID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易資訊';

-- 取消選取資料匯出。

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
