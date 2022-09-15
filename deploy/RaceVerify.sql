-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.8.3-MariaDB-1:10.8.3+maria~jammy - mariadb.org binary distribution
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

-- 傾印  資料表 koa_main.RaceVerify 結構
CREATE TABLE IF NOT EXISTS `RaceVerify` (
  `RacePlayerID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '競賽角色編號',
  `VerifyStage` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '驗證階段',
  `Speed` float unsigned NOT NULL DEFAULT 0 COMMENT '當前速度',
  `ServerDistance` float unsigned NOT NULL DEFAULT 0 COMMENT '移動距離',
  `ClientDistance` float unsigned NOT NULL DEFAULT 0 COMMENT '誤差值',
  `IsCheat` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '是否作弊',
  `UpdateTime` decimal(20,6) unsigned NOT NULL DEFAULT 0.000000 COMMENT '更新時間',
  `StartTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '開始時間',
  `CreateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  PRIMARY KEY (`RacePlayerID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='比賽驗證表';

-- 正在傾印表格  koa_main.RaceVerify 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `RaceVerify` DISABLE KEYS */;
/*!40000 ALTER TABLE `RaceVerify` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
