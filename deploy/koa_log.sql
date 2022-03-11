-- --------------------------------------------------------
-- 主機:                           192.168.2.196
-- 伺服器版本:                        10.6.5-MariaDB-1:10.6.5+maria~focal - mariadb.org binary distribution
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

-- 傾印  資料表 koa_log.BaseProcess 結構
CREATE TABLE IF NOT EXISTS `BaseProcess` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `UserID` int(10) unsigned NOT NULL COMMENT '使用者ID',
  `RedirectURL` varchar(255) NOT NULL COMMENT '執行網址',
  `Content` text NOT NULL COMMENT '內容',
  `Result` tinyint(4) NOT NULL DEFAULT 0 COMMENT '處理結果(1=成功)',
  `Message` text DEFAULT NULL COMMENT '處理結果訊息',
  `BeginTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '起始時間',
  `RecordTime` decimal(20,6) NOT NULL DEFAULT 0.000000 COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`,`RecordTime`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='基礎處理 log';

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
