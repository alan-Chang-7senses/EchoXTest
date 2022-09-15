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


-- 傾印 koa_static 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_static` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_static`;

-- 傾印  資料表 koa_static.RaceSceneVerify 結構
CREATE TABLE IF NOT EXISTS `RaceSceneVerify` (
  `SceneID` int(10) unsigned NOT NULL COMMENT '場景',
  `TrackNumber` tinyint(3) unsigned NOT NULL COMMENT '賽道',
  `BeginDistance` float unsigned NOT NULL COMMENT '開始距離',
  `TotalDistance` float unsigned NOT NULL COMMENT '總長'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- 正在傾印表格  koa_static.RaceSceneVerify 的資料：0 rows
/*!40000 ALTER TABLE `RaceSceneVerify` DISABLE KEYS */;
INSERT INTO `RaceSceneVerify` (`SceneID`, `TrackNumber`, `BeginDistance`, `TotalDistance`) VALUES
	(1001, 1, 0, 1289),
	(1001, 2, 0, 1289);
/*!40000 ALTER TABLE `RaceSceneVerify` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
