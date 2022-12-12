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

-- 正在傾印表格  koa_main.Configs 的資料：~42 rows (近似值)
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
	('LobbyCoinPlayerLevel', '0', '金幣賽指定角色等級(0=不指定)'),
	('LobbyCoinSkillLevel', '0', '金幣賽指定技能等級(0=不指定)'),
	('LobbyPTPlayerLevel', '100', 'PT賽指定角色等級(0=不指定)'),
	('LobbyPTSkillLevel', '5', 'PT賽指定技能等級(0=不指定)'),
	('LobbyPVEPlayerLevel', '0', 'PVE指定角色等級(0=不指定)'),
	('LobbyPVESkillLevel', '0', 'PVE指定技能等級(0=不指定)'),
	('LobbyStudyPlayerLevel', '100', '練習賽指定角色等級(0=不指定)'),
	('LobbyStudySkillLevel', '5', '練習賽指定技能等級(0=不指定)'),
	('MailShowLimit', '100', '信件顯示上限'),
	('MyCardRestoreMailDay', '30', 'MyCard補儲加入信件的過期時間(日)'),
	('MyCardRestoreMailID', '13', 'MyCard補儲加入信件的MailID'),
	('NewNFTRewardMailExpireDate', '500', 'NFT創角獎勵信件之領取期限(0為空)'),
	('NewNFTRewardMailID', '12', 'NFT創角獎勵之信件編號(0為空)'),
	('PvP_B_FreeTicketId_1_Count', '30', '金幣賽免費入場券(每次)發放數量'),
	('PvP_B_FreeTicketId_2_Count', '0', 'PT幣賽免費入場券(每次)發放數量'),
	('PvP_B_LimitTimeData', '[{"start":"07:00:00+8:00", "end":"11:00:00+8:00"},{"start":"19:00:00+8:00", "end":"23:00:00+8:00"}]', 'PT賽每日開放時間與跑馬燈資料(JSON 物件陣列)'),
	('PvP_B_LimitTimeEndMarqueeID', '[27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39]', 'PT賽每日結束時各語系跑馬燈編號'),
	('PvP_B_LimitTimeStartMarqueeID', '[14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26]', 'PT賽每日開始時各語系跑馬燈編號'),
	('PvP_B_MaxTickets_1', '100', '金幣賽入場券的儲存上限'),
	('PvP_B_MaxTickets_2', '3', 'PT賽入場券的儲存上限'),
	('PvP_B_PetaLvLimit_1', '70', '參加金幣賽的Peta等級壓縮'),
	('PvP_B_SeasonStartTime', '2022-06-24 00:00:00+8:00', '晉級賽賽季起始時間'),
	('PvP_B_StopMatch', '1800', '晉級賽結束配對時間(秒數)'),
	('PvP_B_Treshold_1', '10', '火星幣賽的上榜門檻(比賽次數)'),
	('PvP_B_Treshold_2', '10', 'PT幣賽的上榜門檻(比賽次數)'),
	('PvP_B_WeeksPerSeacon', '2', '晉級賽每賽季有幾週'),
	('RaceRewardMultiplier', '1', '競賽獎勵倍數'),
	('RaceVerifyDistance', '10', '驗證比賽距離誤差值'),
	('SeasonRankingRewardMailDay', '7', '賽季排行獎勵信件過期時間(日)'),
	('SeasonRankingRewardMailID', '{"1":15,"2":16,"4":17,"5":18}', '賽季排行獎勵信件編號'),
	('StoreAutoRefreshTime', '00:00:00+08:00', '每日自動刷新商店內容的時間'),
	('TimelimitElitetestRace', '200', '菁英測試競賽時限(秒)'),
	('TimelimitRaceFinish', '300', '競賽完賽時限(秒)'),
	('TutorialRewards', '[{"Step":1, "ItemID":1003, "Amount":1}]', '新手引導獎勵'),
	('TutorialSceneID', '4000', '新手引導賽場編號');
/*!40000 ALTER TABLE `Configs` ENABLE KEYS */;

-- 傾印  資料表 koa_main.UserMails 結構
CREATE TABLE IF NOT EXISTS `UserMails` (
  `UserMailID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '信件流水號',
  `UserID` int(10) NOT NULL COMMENT '玩家ID',
  `MailsID` int(10) NOT NULL COMMENT '信件編號',
  `MailArgument` varchar(250) NOT NULL DEFAULT '' COMMENT '信件參數',
  `OpenStatus` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '開啟狀態',
  `ReceiveStatus` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '領取狀態',
  `CreateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  `FinishTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '結束時間',
  PRIMARY KEY (`UserMailID`) USING BTREE,
  KEY `UserID` (`UserID`) USING BTREE,
  KEY `MailsID` (`MailsID`) USING BTREE,
  KEY `ReceiveStatus` (`ReceiveStatus`),
  KEY `OpenStatus` (`OpenStatus`),
  KEY `FinishTime` (`FinishTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 正在傾印表格  koa_main.UserMails 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `UserMails` DISABLE KEYS */;
/*!40000 ALTER TABLE `UserMails` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
