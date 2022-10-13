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

-- 正在傾印表格  koa_main.Configs 的資料：~30 rows (近似值)
/*!40000 ALTER TABLE `Configs` DISABLE KEYS */;
INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES
	('AllPlayerLevel', '100', '強制指定所有角色等級(0=無效)'),
	('AllSkillLevel', '5', '強制指定所有角色技能等級(0=無效)'),
	('AmountRacePlayerMax', '8', '開房最大人數'),
	('CreateUserItems', '[{"ItemID":5100, "Amount":50}]', '建立使用者發送物品(JSON 物件陣列)'),
	('CreateUserMailDay', '7', '建立使用者發送信件的保留天數'),
	('CreateUserMailIDs', '[5,6,7,8]', '建立使用者發送信件編號(JSON陣列)'),
	('ItemFullAddMailID', '10', '物品超過堆疊上限加入信件的MailID'),
	('ItemFullAddMailIDay', '7', '物品超過堆疊上限加入信件的過期時間(日)'),
	('PvP_B_FreeTicketId_1_Count', '30', '金幣賽免費入場券(每次)發放數量'),
	('PvP_B_FreeTicketId_2_Count', '0', 'PT幣賽免費入場券(每次)發放數量'),
	('PvP_B_MaxTickets_1', '100', '金幣賽入場券的儲存上限'),
	('PvP_B_MaxTickets_2', '3', 'PT賽入場券的儲存上限'),
	('PvP_B_NewRoomRate_1', '1', '金幣晉級賽創建房間千分比'),
	('PvP_B_NewRoomRate_2', '250', 'PT晉級賽創建房間千分比'),
	('PvP_B_PetaLvLimit_1', '70', '參加金幣賽的Peta等級壓縮'),
	('PvP_B_SeasonStartTime', '2022-06-24 00:00:00+8:00', '晉級賽賽季起始時間'),
	('PvP_B_StopMatch', '1800', '晉級賽結束配對時間(秒數)'),
	('PvP_B_TicketId_1', '5100', '金幣賽入場券的道具Id'),
	('PvP_B_TicketId_2', '5201', 'PT賽入場券的道具Id'),
	('PvP_B_Treshold_1', '10', '火星幣賽的上榜門檻(比賽次數)'),
	('PvP_B_Treshold_2', '10', 'PT幣賽的上榜門檻(比賽次數)'),
	('PvP_B_WeeksPerSeacon', '2', '晉級賽每賽季有幾週'),
	('PvP_ExtraMatchSeconds', '60', '開局配對延長等待秒數'),
	('PvP_MaxMatchSeconds', '60', '開局配對基本等待秒數'),
	('RaceRewardMultiplier', '1', '競賽獎勵倍數'),
	('RaceVerifyDistance', '10', '驗證比賽距離誤差值'),
	('SeasonRankingRewardMailDay', '7', '賽季排行獎勵信件過期時間(日)'),
	('SeasonRankingRewardMailID', '1', '賽季排行獎勵信件編號'),
	('StoreAutoRefreshTime', '00:00:00', '每日自動刷新商店內容的時間'),
	('StoreRefreshResetTime', '00:00:00', '每日重置刷新按鈕的時間'),
	('TimelimitElitetestRace', '200', '菁英測試競賽時限(秒)'),
	('TimelimitRaceFinish', '300', '競賽完賽時限(秒)');
/*!40000 ALTER TABLE `Configs` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
