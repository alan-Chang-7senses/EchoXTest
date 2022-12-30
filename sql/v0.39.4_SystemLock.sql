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

-- 傾印  資料表 koa_main.SystemLock 結構
CREATE TABLE IF NOT EXISTS `SystemLock` (
  `UserID` int(11) NOT NULL DEFAULT 0 COMMENT '使用者編號',
  `APIName` varchar(255) NOT NULL COMMENT 'API名稱',
  `LockFlag` int(11) NOT NULL DEFAULT 0 COMMENT '延遲時間',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  PRIMARY KEY (`UserID`),
  KEY `APINName` (`APIName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='相同API進入時，延遲設定';
