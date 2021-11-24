-- --------------------------------------------------------
-- 主機:                           192.168.2.119
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


-- 傾印 koa_main 的資料庫結構
CREATE DATABASE IF NOT EXISTS `koa_main` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `koa_main`;

-- 傾印  資料表 koa_main.Configs 結構
CREATE TABLE IF NOT EXISTS `Configs` (
  `Name` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  PRIMARY KEY (`Name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='雜項設置';

-- 正在傾印表格  koa_main.Configs 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `Configs` DISABLE KEYS */;
/*!40000 ALTER TABLE `Configs` ENABLE KEYS */;

-- 傾印  資料表 koa_main.DatabaseInfo 結構
CREATE TABLE IF NOT EXISTS `DatabaseInfo` (
  `Label` varchar(255) NOT NULL COMMENT '標記代號',
  `Host` varchar(255) NOT NULL COMMENT '主機名稱或IP',
  `Username` varchar(255) NOT NULL COMMENT '帳號',
  `Password` varchar(255) NOT NULL COMMENT '密碼',
  `Name` varchar(255) NOT NULL COMMENT '資料庫名稱',
  `Port` smallint(5) unsigned DEFAULT 3306,
  PRIMARY KEY (`Label`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='資料庫連線資訊';

-- 正在傾印表格  koa_main.DatabaseInfo 的資料：~1 rows (近似值)
/*!40000 ALTER TABLE `DatabaseInfo` DISABLE KEYS */;
INSERT INTO `DatabaseInfo` (`Label`, `Host`, `Username`, `Password`, `Name`, `Port`) VALUES
	('KoaMain', '192.168.2.119', 'root', '1111', 'koa_main', 37002);
/*!40000 ALTER TABLE `DatabaseInfo` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
