-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.8.3-MariaDB-1:10.8.3+maria~jammy - mariadb.org binary distribution
-- 伺服器作業系統:                      debian-linux-gnu
-- HeidiSQL 版本:                  12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- 傾印 koa_static 的資料庫結構
USE `koa_static`;

DROP TABLE `FreePetaInfo`;

-- 傾印  資料表 koa_static.FreePetaInfo 結構
CREATE TABLE IF NOT EXISTS `FreePetaInfo` (
  `ID` int(10) unsigned NOT NULL,
  `Type` tinyint(1) DEFAULT NULL COMMENT '免費Peta種類',
  `Constitution` smallint(5) NOT NULL DEFAULT 0 COMMENT '體力',
  `Strength` smallint(5) NOT NULL DEFAULT 0 COMMENT '力量',
  `Dexterity` smallint(5) NOT NULL DEFAULT 0 COMMENT '技巧',
  `Agility` smallint(5) NOT NULL DEFAULT 0 COMMENT '敏捷',
  `Attribute` tinyint(3) NOT NULL DEFAULT 0 COMMENT '屬性',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='免費Peta 基礎數值合集';

-- 正在傾印表格  koa_static.FreePetaInfo 的資料：~15 rows (近似值)
INSERT INTO `FreePetaInfo` (`ID`, `Type`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`) VALUES
	(1, 0, 4000, 6700, 4600, 4700, 1),
	(2, 0, 4000, 6800, 4400, 4800, 1),
	(3, 0, 3900, 6700, 4600, 4800, 1),
	(4, 0, 4000, 5300, 5100, 5600, 1),
	(5, 0, 4000, 4700, 4300, 7000, 1),
	(6, 1, 5300, 5000, 4900, 4800, 1),
	(7, 1, 5400, 4800, 4800, 5000, 1),
	(8, 1, 5500, 4700, 5100, 4700, 1),
	(9, 1, 4700, 4800, 4900, 5600, 1),
	(10, 1, 4900, 4800, 5100, 5200, 1),
	(11, 2, 5600, 4000, 6200, 4200, 1),
	(12, 2, 5000, 4400, 6300, 4300, 1),
	(13, 2, 4800, 4300, 6400, 4500, 1),
	(14, 2, 6200, 4000, 5500, 4300, 1),
	(15, 2, 5700, 4400, 5400, 4500, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
