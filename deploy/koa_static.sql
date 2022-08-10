-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.8.3-MariaDB-1:10.8.3+maria~jammy - mariadb.org binary distribution
-- 伺服器作業系統:                      debian-linux-gnu
-- HeidiSQL 版本:                  12.0.0.6468
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

-- 傾印  資料表 koa_static.FreePetaDNA 結構
CREATE TABLE IF NOT EXISTS `FreePetaDNA` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `HeadDNA` varchar(50) DEFAULT NULL,
  `BodyDNA` varchar(50) DEFAULT NULL,
  `HandDNA` varchar(50) DEFAULT NULL,
  `LegDNA` varchar(50) DEFAULT NULL,
  `BackDNA` varchar(50) DEFAULT NULL,
  `HatDNA` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='免費Peta DNA合集';

-- 正在傾印表格  koa_static.FreePetaDNA 的資料：~15 rows (近似值)
/*!40000 ALTER TABLE `FreePetaDNA` DISABLE KEYS */;
INSERT INTO `FreePetaDNA` (`Serial`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`) VALUES
	(1, '140301000014030100001403010000', '140301000014030100001403010000', '140301000014030100001403010000', '140301000014030100001403010000', '140301000014030100001403010000', '140301000014030100001403010000'),
	(2, '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000'),
	(3, '140102000014010200001401020000', '140102000014010200001401020000', '140102000014010200001401020000', '140102000014010200001401020000', '140102000014010200001401020000', '140102000014010200001401020000'),
	(4, '140303000014030300001403030000', '140303000014030300001403030000', '140303000014030300001403030000', '140303000014030300001403030000', '140303000014030300001403030000', '140303000014030300001403030000'),
	(5, '140204000014020400001402040000', '140204000014020400001402040000', '140204000014020400001402040000', '140204000014020400001402040000', '140204000014020400001402040000', '140204000014020400001402040000'),
	(6, '140105000014010500001401050000', '140105000014010500001401050000', '140105000014010500001401050000', '140105000014010500001401050000', '140105000014010500001401050000', '140105000014010500001401050000'),
	(7, '140206000014020600001402060000', '140206000014020600001402060000', '140206000014020600001402060000', '140206000014020600001402060000', '140206000014020600001402060000', '140206000014020600001402060000'),
	(8, '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000'),
	(9, '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000'),
	(10, '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000'),
	(11, '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000'),
	(12, '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000'),
	(13, '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000'),
	(14, '130302000013030200001303020000', '130302000013030200001303020000', '130302000013030200001303020000', '130302000013030200001303020000', '130302000013030200001303020000', '130302000013030200001303020000'),
	(15, '130303000013030300001303030000', '130303000013030300001303030000', '130303000013030300001303030000', '130303000013030300001303030000', '130303000013030300001303030000', '130303000013030300001303030000');
/*!40000 ALTER TABLE `FreePetaDNA` ENABLE KEYS */;

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
/*!40000 ALTER TABLE `FreePetaInfo` DISABLE KEYS */;
INSERT INTO `FreePetaInfo` (`ID`, `Type`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`) VALUES
	(1, 0, 4000, 6500, 4600, 4500, 1),
	(2, 0, 4000, 6600, 4400, 4600, 1),
	(3, 0, 3900, 6700, 4600, 4400, 1),
	(4, 0, 4000, 5100, 5100, 5400, 1),
	(5, 0, 4000, 4500, 4300, 6800, 1),
	(6, 1, 5200, 4900, 4800, 4700, 1),
	(7, 1, 5300, 4700, 4700, 4900, 1),
	(8, 1, 5400, 4600, 5000, 4600, 1),
	(9, 1, 4600, 4700, 4800, 5500, 1),
	(10, 1, 4800, 4700, 5000, 5100, 1),
	(11, 2, 5400, 4000, 6000, 4200, 1),
	(12, 2, 4800, 4400, 6100, 4300, 1),
	(13, 2, 4600, 4300, 6200, 4500, 1),
	(14, 2, 6000, 4000, 5300, 4300, 1),
	(15, 2, 5500, 4400, 5200, 4500, 1);
/*!40000 ALTER TABLE `FreePetaInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.ItemDrop 結構
CREATE TABLE IF NOT EXISTS `ItemDrop` (
  `Serial` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ItemDropID` int(11) NOT NULL DEFAULT 0 COMMENT '掉落物編號',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '物品編號',
  `Amount` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '數量',
  `Proportion` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '比例權重',
  PRIMARY KEY (`Serial`),
  KEY `ItemDropID` (`ItemDropID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='物品掉落表';

-- 正在傾印表格  koa_static.ItemDrop 的資料：~32 rows (近似值)
/*!40000 ALTER TABLE `ItemDrop` DISABLE KEYS */;
INSERT INTO `ItemDrop` (`Serial`, `ItemDropID`, `ItemID`, `Amount`, `Proportion`) VALUES
	(1, 1, 1111, 1, 100),
	(2, 1, 1121, 1, 100),
	(3, 1, 1131, 1, 100),
	(4, 2, 1112, 1, 100),
	(5, 2, 1122, 1, 100),
	(6, 2, 1132, 1, 100),
	(7, 3, 2011, 1, 100),
	(8, 3, 2013, 1, 100),
	(9, 3, 2014, 1, 100),
	(10, 3, 2015, 1, 100),
	(11, 3, 2016, 1, 100),
	(12, 3, 2017, 1, 100),
	(13, 4, 2000, 1, 30),
	(14, 4, 2011, 1, 10),
	(15, 4, 2013, 1, 10),
	(16, 4, 2014, 1, 10),
	(17, 4, 2015, 1, 10),
	(18, 4, 2016, 1, 10),
	(19, 4, 2017, 1, 10),
	(20, 4, 2000, 2, 20),
	(21, 4, 2000, 3, 10),
	(22, 5, 1001, 10, 100),
	(23, 5, 1002, 5, 100),
	(24, 6, 1002, 10, 100),
	(25, 6, 1003, 5, 100),
	(26, 6, 1111, 1, 100),
	(27, 6, 1121, 1, 100),
	(28, 6, 1131, 1, 100),
	(29, 7, 1003, 10, 100),
	(30, 7, 1112, 1, 100),
	(31, 7, 1122, 1, 100),
	(32, 7, 1132, 1, 100);
/*!40000 ALTER TABLE `ItemDrop` ENABLE KEYS */;

-- 傾印  資料表 koa_static.ItemInfo 結構
CREATE TABLE IF NOT EXISTS `ItemInfo` (
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '物品編號',
  `ItemName` varchar(50) NOT NULL COMMENT '物品名稱代號',
  `Description` varchar(50) NOT NULL COMMENT '物品敘述代號',
  `ItemType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '物品種類',
  `Icon` varchar(50) NOT NULL COMMENT '圖號',
  `StackLimit` int(11) NOT NULL DEFAULT 0 COMMENT '堆疊上限',
  `UseType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '使用類型',
  `EffectType` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '效果類型',
  `EffectValue` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '效果值',
  `ItemDropIDs` text DEFAULT NULL COMMENT '掉落物編號',
  `DropType` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '掉落類型',
  `DropCount` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '掉落次數',
  `Source` varchar(255) NOT NULL DEFAULT '' COMMENT '來源代號',
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='物品資訊表';

-- 正在傾印表格  koa_static.ItemInfo 的資料：~31 rows (近似值)
/*!40000 ALTER TABLE `ItemInfo` DISABLE KEYS */;
INSERT INTO `ItemInfo` (`ItemID`, `ItemName`, `Description`, `ItemType`, `Icon`, `StackLimit`, `UseType`, `EffectType`, `EffectValue`, `ItemDropIDs`, `DropType`, `DropCount`, `Source`) VALUES
	(1001, '8101', '8601', 1, 'ItemIcon_1001', 99999, 0, 101, 150, NULL, 0, 1, 'pve,store,s101'),
	(1002, '8102', '8602', 1, 'ItemIcon_1002', 99999, 0, 101, 750, NULL, 0, 1, 'pvp1,store,s102'),
	(1003, '8103', '8603', 1, 'ItemIcon_1003', 99999, 0, 101, 2100, NULL, 0, 1, 'pvp2,store,s103'),
	(1111, '8104', '8604', 1, 'ItemIcon_0004', 99999, 0, 201, 0, NULL, 0, 1, 'pvp3'),
	(1112, '8105', '8605', 1, 'ItemIcon_0005', 99999, 0, 202, 0, NULL, 0, 1, 's008'),
	(1121, '8106', '8606', 1, 'ItemIcon_0006', 99999, 0, 203, 0, NULL, 0, 1, 's012'),
	(1122, '8107', '8607', 1, 'ItemIcon_0007', 99999, 0, 204, 0, NULL, 0, 1, 's009'),
	(1131, '8108', '8608', 1, 'ItemIcon_0008', 99999, 0, 205, 0, NULL, 0, 1, 's010'),
	(1132, '8109', '8609', 1, 'ItemIcon_0009', 99999, 0, 206, 0, NULL, 0, 1, 's011'),
	(2000, '8110', '8610', 2, 'ItemIcon_0010', 99999, 0, 0, 0, NULL, 0, 1, 's001'),
	(2001, '8118', '8618', 2, 'ItemIcon_0039_2', 99999, 0, 0, 0, NULL, 0, 1, ''),
	(2002, '8117', '8617', 2, 'ItemIcon_0039_1', 99999, 0, 0, 0, NULL, 0, 1, ''),
	(2011, '8111', '8611', 2, 'ItemIcon_0011', 99999, 0, 0, 0, NULL, 0, 1, 's002'),
	(2013, '8113', '8613', 2, 'ItemIcon_0012', 99999, 0, 0, 0, NULL, 0, 1, 's004'),
	(2014, '8112', '8612', 2, 'ItemIcon_0013', 99999, 0, 0, 0, NULL, 0, 1, 's003'),
	(2015, '8116', '8616', 2, 'ItemIcon_0014', 99999, 0, 0, 0, NULL, 0, 1, 's007'),
	(2016, '8115', '8615', 2, 'ItemIcon_0015', 99999, 0, 0, 0, NULL, 0, 1, 's006'),
	(2017, '8114', '8614', 2, 'ItemIcon_0016', 99999, 0, 0, 0, NULL, 0, 1, 's005'),
	(3001, '8119', '8619', 3, 'ItemIcon_0017_2', 999, 1, 0, 0, '1', 3, 1, '0'),
	(3002, '8120', '8620', 3, 'ItemIcon_0018_2', 999, 1, 0, 0, '2', 3, 1, '0'),
	(3003, '8124', '8624', 3, 'ItemIcon_0019_2', 999, 1, 0, 0, '3', 1, 1, '0'),
	(4001, '8121', '8621', 4, 'ItemIcon_0020_1', 999, 1, 0, 0, '1', 4, 1, '0'),
	(4002, '8122', '8622', 4, 'ItemIcon_0021_1', 999, 1, 0, 0, '2', 4, 1, '0'),
	(4003, '8123', '8623', 4, 'ItemIcon_0020_3', 999, 1, 0, 0, '4', 3, 1, '0'),
	(4004, '8125', '8625', 4, 'ItemIcon_0021_1', 999, 1, 0, 0, '3', 4, 1, '0'),
	(4005, '8126', '8626', 4, 'ItemIcon_0025_1', 999, 1, 0, 0, '5', 4, 1, '0'),
	(4006, '8127', '8627', 4, 'ItemIcon_0025_2', 999, 1, 0, 0, '6', 4, 1, '0'),
	(4007, '8128', '8628', 4, 'ItemIcon_0025_3', 999, 1, 0, 0, '7', 4, 1, '0'),
	(5100, '8129', '8629', 5, 'ItemIcon_0032', 999, 0, 0, 0, NULL, 0, 0, '0'),
	(5201, '8130', '8630', 5, 'ItemIcon_0031', 999, 0, 0, 0, NULL, 0, 0, '0'),
	(5202, '8131', '8631', 5, 'ItemIcon_0030', 999, 0, 0, 0, NULL, 0, 0, '0');
/*!40000 ALTER TABLE `ItemInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.MailsInfo 結構
CREATE TABLE IF NOT EXISTS `MailsInfo` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `MailsID` int(10) unsigned NOT NULL COMMENT '信件編號',
  `Status` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '啟用狀態',
  `Type` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '信件分類',
  `Lang` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '多國語言',
  `Title` varchar(50) NOT NULL DEFAULT '' COMMENT '信件標題',
  `Content` text NOT NULL COMMENT '信件內容',
  `Sender` varchar(50) NOT NULL DEFAULT '' COMMENT '寄件者',
  `URL` varchar(50) NOT NULL DEFAULT '' COMMENT '連結',
  `RewardID` int(10) unsigned DEFAULT NULL COMMENT '信件獎勵',
  `CreateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建立時間',
  `UpdateTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新時間',
  `FinishTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '結束時間',
  PRIMARY KEY (`Serial`) USING BTREE,
  KEY `RewardID` (`RewardID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='信件資訊';

-- 正在傾印表格  koa_static.MailsInfo 的資料：~13 rows (近似值)
/*!40000 ALTER TABLE `MailsInfo` DISABLE KEYS */;
INSERT INTO `MailsInfo` (`Serial`, `MailsID`, `Status`, `Type`, `Lang`, `Title`, `Content`, `Sender`, `URL`, `RewardID`, `CreateTime`, `UpdateTime`, `FinishTime`) VALUES
	(1, 1, 1, 0, 0, 'Welcome to PetaRush,', 'So glad to have you in PetaRush. Come and join the festivity!', 'Sender: Direct Team', 'https://www.google.com', 0, 1655797800, 1655797800, 1659110400),
	(2, 1, 1, 0, 2, 'Welcome to PetaRush,', 'So glad to have you in PetaRush. Come and join the festivity!', 'Sender: Direct Team', 'https://www.google.com', 0, 1655797800, 1655797800, 1659110400),
	(3, 1, 1, 0, 6, '《PetaRush》へようこそ', '《PetaRush》へようこそ，一緒に《PetaRush》で盛り上がりましょう！', '送信者：研究開発チーム', '', 0, 1655797800, 1655797800, 1659110400),
	(4, 1, 1, 0, 7, '<PetaRush>에 오신 걸 축하드립니다.', '<PetaRush>에 오신 걸 환영합니다. 당신을 <페타 러시> Peta의 대형 행사에 초대합니다!', '발신자: 개발팀', '', 0, 1655797800, 1655797800, 1659110400),
	(5, 1, 1, 0, 11, 'ยินดีต้อนรับสู่PetaRush', 'ยินดีต้อนรับสู่ 《PetaRush》เราขอเชิญคุณมาร่วมงานPetaครั้งสำคัญใน PetaRush ', 'ผู้ส่ง：ทีมผู้วิจัยและพัฒนา', '', 0, 1655797800, 1655797800, 1659110400),
	(6, 1, 1, 0, 5, 'Selamat datang di PetaRush', 'Selamat datang di PetaRush. Dengan senang hati, kami mengundang Anda untuk bergabung dengan kami di acara akbar Peta, yaitu PetaRush!', 'Pengirim: Tim Pengembang', '', 0, 1655797800, 1655797800, 1659110400),
	(7, 1, 1, 0, 4, 'Maligayang pagdating sa “PetaRush”', 'Maligayang pagdating sa “PetaRush”. Taos-puso kaming inaanyayahan na sumali sa amin sa malaking kaganapan ng Peta, “pagtakbo ng hayop” !', 'Nagpadala: pangkat ng R&D', '', 0, 1655797800, 1655797800, 1659110400),
	(8, 1, 1, 0, 3, 'Bienvenido a PetaRush', '¡Bienvenido a PetaRush! ¡Venga y únase a la fiesta de Peta con nosotros!', 'Remitente: Equipo de desarrolladores', '', 0, 1655797800, 1655797800, 1659110400),
	(9, 1, 1, 0, 1, 'Willkommen bei PetaRush', 'Willkommen bei PetaRush! Komm und feiere mit uns das Peta Fest!', 'Absender: Entwickler-Team', '', 0, 1655797800, 1655797800, 1659110400),
	(10, 1, 1, 0, 9, 'Bem-vindo à PetaRush', 'Bem-vindo à PetaRush! Vem participar das comemorações da Peta com a gente!', 'Remetente: Equipe de desenvolvedores', '', 0, 1655797800, 1655797800, 1659110400),
	(11, 1, 1, 0, 10, 'Добро пожаловать в PetaRush', 'Добро пожаловать в PetaRush! Присоединяйтесь к празднику Peta вместе с нами!', 'Отправитель: команда разработчиков', '', 0, 1655797800, 1655797800, 1659110400),
	(12, 1, 1, 0, 8, '"PetaRush" မွ လႈိက္လွဲစြာႀကိဳဆိုပါသည္။', '"PetaRush" မွ ႀကိဳဆိုပါသည္။ Peta ၏အဓိကပြဲျဖစ္သည့္ "PetaRush" တြင္ ပါဝင္ဆင္ႏႊဲရန္ ေလးစားစြာျဖင့္ ဖိတ္ၾကားအပ္ပါသည္။', 'ေပးပို႔သူ- R&D အဖဲြ႔', '', 0, 1655797800, 1655797800, 1659110400),
	(13, 1, 1, 0, 12, '歡迎來到《PetaRush》', '歡迎來到《PetaRush》誠摯邀請你一同來參與《動物大奔走》這個Peta的大型盛事！', '寄件人：研發團隊', '', 0, 1655797800, 1655797800, 1659110400);
/*!40000 ALTER TABLE `MailsInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.MailsRewards 結構
CREATE TABLE IF NOT EXISTS `MailsRewards` (
  `RewardID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '獎勵編號',
  `ItemID1` int(11) NOT NULL DEFAULT 0 COMMENT '道具編號A',
  `ItemNumber1` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '道具數量A',
  `ItemID2` int(11) NOT NULL DEFAULT 0 COMMENT '道具編號B',
  `ItemNumber2` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '道具數量B',
  `ItemID3` int(11) NOT NULL DEFAULT 0 COMMENT '道具編號C',
  `ItemNumber3` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '道具數量C',
  PRIMARY KEY (`RewardID`) USING BTREE,
  KEY `ItemID` (`ItemID1`,`ItemID2`,`ItemID3`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='信件獎勵表';

-- 正在傾印表格  koa_static.MailsRewards 的資料：~1 rows (近似值)
/*!40000 ALTER TABLE `MailsRewards` DISABLE KEYS */;
INSERT INTO `MailsRewards` (`RewardID`, `ItemID1`, `ItemNumber1`, `ItemID2`, `ItemNumber2`, `ItemID3`, `ItemNumber3`) VALUES
	(1, 8129, 2, 8130, 2, 0, 0);
/*!40000 ALTER TABLE `MailsRewards` ENABLE KEYS */;

-- 傾印  資料表 koa_static.QualifyingArena 結構
CREATE TABLE IF NOT EXISTS `QualifyingArena` (
  `QualifyingArenaID` int(11) NOT NULL DEFAULT 0 COMMENT '晉級賽場編號',
  `PTScene` int(10) unsigned DEFAULT 0 COMMENT 'PT場地',
  `CoinScene` int(10) unsigned DEFAULT 0 COMMENT '金幣場地',
  PRIMARY KEY (`QualifyingArenaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='晉級賽賽場';

-- 正在傾印表格  koa_static.QualifyingArena 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `QualifyingArena` DISABLE KEYS */;
INSERT INTO `QualifyingArena` (`QualifyingArenaID`, `PTScene`, `CoinScene`) VALUES
	(1, 1, 1),
	(2, 2, 0);
/*!40000 ALTER TABLE `QualifyingArena` ENABLE KEYS */;

-- 傾印  資料表 koa_static.RewardContent 結構
CREATE TABLE IF NOT EXISTS `RewardContent` (
  `ContentGroupID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '群組編號',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '獎勵內容',
  `Amount` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵數量',
  `Proportion` smallint(4) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵權重或機率(千分比)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='獎勵內容';

-- 正在傾印表格  koa_static.RewardContent 的資料：~7 rows (近似值)
/*!40000 ALTER TABLE `RewardContent` DISABLE KEYS */;
INSERT INTO `RewardContent` (`ContentGroupID`, `ItemID`, `Amount`, `Proportion`) VALUES
	(1, 1, 1, 100),
	(1, 2, 2, 200),
	(1, 3, 3, 300),
	(2, 1, 1, 100),
	(2, 2, 2, 200),
	(2, 3, 3, 300),
	(3, -1, 100, 300);
/*!40000 ALTER TABLE `RewardContent` ENABLE KEYS */;

-- 傾印  資料表 koa_static.RewardInfo 結構
CREATE TABLE IF NOT EXISTS `RewardInfo` (
  `RewardID` int(11) NOT NULL COMMENT '獎勵編號',
  `Modes` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵模式',
  `Times` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '發放次數',
  `ContentGroupID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵內容群組',
  PRIMARY KEY (`RewardID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='獎勵資訊';

-- 正在傾印表格  koa_static.RewardInfo 的資料：~7 rows (近似值)
/*!40000 ALTER TABLE `RewardInfo` DISABLE KEYS */;
INSERT INTO `RewardInfo` (`RewardID`, `Modes`, `Times`, `ContentGroupID`) VALUES
	(1, 1, 1, 1),
	(2, 2, 1, 1),
	(3, 3, 1, 1),
	(4, 5, 1, 1),
	(5, 1, 1, 2),
	(6, 1, 100, 2),
	(7, 1, 100, 3);
/*!40000 ALTER TABLE `RewardInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SceneClimate 結構
CREATE TABLE IF NOT EXISTS `SceneClimate` (
  `SceneClimateID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SceneID` int(10) unsigned NOT NULL DEFAULT 0,
  `Weather` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '天氣',
  `WindDirection` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '風向',
  `WindSpeed` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '風速',
  `StartTime` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT '起始時間（當日秒數）',
  `Lighting` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '明暗（日照與背光）',
  PRIMARY KEY (`SceneClimateID`),
  KEY `SceneID` (`SceneID`),
  KEY `StartTime` (`StartTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='場景氣候';

-- 正在傾印表格  koa_static.SceneClimate 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `SceneClimate` DISABLE KEYS */;
INSERT INTO `SceneClimate` (`SceneClimateID`, `SceneID`, `Weather`, `WindDirection`, `WindSpeed`, `StartTime`, `Lighting`) VALUES
	(1, 1, 1, 1, 50, 0, 2),
	(2, 1, 1, 2, 50, 28800, 1),
	(3, 1, 1, 3, 50, 64800, 2);
/*!40000 ALTER TABLE `SceneClimate` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SceneInfo 結構
CREATE TABLE IF NOT EXISTS `SceneInfo` (
  `SceneID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SceneName` varchar(50) NOT NULL DEFAULT '' COMMENT '場景代號（名稱）',
  `SceneEnv` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '場景環境',
  PRIMARY KEY (`SceneID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='場景主要資訊';

-- 正在傾印表格  koa_static.SceneInfo 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `SceneInfo` DISABLE KEYS */;
INSERT INTO `SceneInfo` (`SceneID`, `SceneName`, `SceneEnv`) VALUES
	(1, 'CloseBeta', 1);
/*!40000 ALTER TABLE `SceneInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillAffixAlias 結構
CREATE TABLE IF NOT EXISTS `SkillAffixAlias` (
  `SkillAffixID` int(11) unsigned NOT NULL,
  `Level1` varchar(50) DEFAULT NULL COMMENT 'Level1 AliasCode',
  `Level2` varchar(50) DEFAULT NULL COMMENT 'Level2 AliasCode',
  `Level3` varchar(50) DEFAULT NULL COMMENT 'Level3 AliasCode',
  `Level4` varchar(50) DEFAULT NULL COMMENT 'Level4 AliasCode',
  `Level5` varchar(50) DEFAULT NULL COMMENT 'Level5 AliasCode',
  `Level6` varchar(50) DEFAULT NULL COMMENT 'Level6 AliasCode',
  PRIMARY KEY (`SkillAffixID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='詞綴技能識別碼';

-- 正在傾印表格  koa_static.SkillAffixAlias 的資料：~5 rows (近似值)
/*!40000 ALTER TABLE `SkillAffixAlias` DISABLE KEYS */;
INSERT INTO `SkillAffixAlias` (`SkillAffixID`, `Level1`, `Level2`, `Level3`, `Level4`, `Level5`, `Level6`) VALUES
	(1, NULL, NULL, 'Norn_3', 'Norn_4', 'Norn_5', 'Norn_6'),
	(2, NULL, NULL, 'Bil_3', 'Bil_4', 'Bil_5', 'Bil_6'),
	(3, NULL, NULL, 'Demeter_3', 'Demeter_4', 'Demeter_5', 'Demeter_6'),
	(4, NULL, NULL, 'Artemis_3', 'Artemis_4', 'Artemis_5', 'Artemis_6'),
	(5, NULL, NULL, 'Eir_3', 'Eir_4', 'Eir_5', 'Eir_6');
/*!40000 ALTER TABLE `SkillAffixAlias` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillEffect 結構
CREATE TABLE IF NOT EXISTS `SkillEffect` (
  `SkillEffectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EffectName` varchar(50) DEFAULT NULL COMMENT '效果名稱或標籤',
  `EffectType` smallint(6) NOT NULL DEFAULT 0 COMMENT '效果類型',
  `Formula` text DEFAULT NULL COMMENT '公式',
  PRIMARY KEY (`SkillEffectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能效果表';

-- 正在傾印表格  koa_static.SkillEffect 的資料：~62 rows (近似值)
/*!40000 ALTER TABLE `SkillEffect` DISABLE KEYS */;
INSERT INTO `SkillEffect` (`SkillEffectID`, `EffectName`, `EffectType`, `Formula`) VALUES
	(20100, 'Spd_up_00', 111, 'SPD*N%'),
	(20101, 'Spd_up_01', 111, 'SPD*N%'),
	(20102, 'Spd_up_02', 111, 'SPD*N%'),
	(20103, 'Spd_up_03', 111, 'SPD*N%'),
	(20104, 'Spd_up_04', 111, 'SPD*N%'),
	(20105, 'Spd_up_05', 111, 'SPD*N%'),
	(20106, 'Spd_up_06', 111, 'SPD*N%'),
	(20111, 'Spd_up_01', 111, 'SPD*N%'),
	(20112, 'Spd_up_02', 111, 'SPD*N%'),
	(20113, 'Spd_up_03', 111, 'SPD*N%'),
	(20114, 'Spd_up_04', 111, 'SPD*N%'),
	(20115, 'Spd_up_05', 111, 'SPD*N%'),
	(20116, 'Spd_up_06', 111, 'SPD*N%'),
	(20200, 'Pow_up_00', 112, 'POW*N%'),
	(20201, 'Pow_up_01', 112, 'POW*N%'),
	(20202, 'Pow_up_02', 112, 'POW*N%'),
	(20203, 'Pow_up_03', 112, 'POW*N%'),
	(20204, 'Pow_up_04', 112, 'POW*N%'),
	(20205, 'Pow_up_05', 112, 'POW*N%'),
	(20206, 'Pow_up_06', 112, 'POW*N%'),
	(20211, 'Pow_up_01', 112, 'POW*N%'),
	(20212, 'Pow_up_02', 112, 'POW*N%'),
	(20213, 'Pow_up_03', 112, 'POW*N%'),
	(20214, 'Pow_up_04', 112, 'POW*N%'),
	(20215, 'Pow_up_05', 112, 'POW*N%'),
	(20216, 'Pow_up_06', 112, 'POW*N%'),
	(20300, 'Fig_up_00', 113, 'FIG*N%'),
	(20301, 'Fig_up_01', 113, 'FIG*N%'),
	(20302, 'Fig_up_02', 113, 'FIG*N%'),
	(20303, 'Fig_up_03', 113, 'FIG*N%'),
	(20304, 'Fig_up_04', 113, 'FIG*N%'),
	(20305, 'Fig_up_05', 113, 'FIG*N%'),
	(20306, 'Fig_up_06', 113, 'FIG*N%'),
	(20311, 'Fig_up_01', 113, 'FIG*N%'),
	(20312, 'Fig_up_02', 113, 'FIG*N%'),
	(20313, 'Fig_up_03', 113, 'FIG*N%'),
	(20314, 'Fig_up_04', 113, 'FIG*N%'),
	(20315, 'Fig_up_05', 113, 'FIG*N%'),
	(20316, 'Fig_up_06', 113, 'FIG*N%'),
	(20400, 'int_up_00', 114, 'INT*N%'),
	(20401, 'int_up_01', 114, 'INT*N%'),
	(20402, 'int_up_02', 114, 'INT*N%'),
	(20403, 'int_up_03', 114, 'INT*N%'),
	(20404, 'int_up_04', 114, 'INT*N%'),
	(20405, 'int_up_05', 114, 'INT*N%'),
	(20406, 'int_up_06', 114, 'INT*N%'),
	(20411, 'int_up_01', 114, 'INT*N%'),
	(20412, 'int_up_02', 114, 'INT*N%'),
	(20413, 'int_up_03', 114, 'INT*N%'),
	(20414, 'int_up_04', 114, 'INT*N%'),
	(20415, 'int_up_05', 114, 'INT*N%'),
	(20416, 'int_up_06', 114, 'INT*N%'),
	(20500, 'Sta_up_00', 115, 'STA*N%'),
	(20501, 'Sta_up_01', 115, 'STA*N%'),
	(20502, 'Sta_up_02', 115, 'STA*N%'),
	(20503, 'Sta_up_03', 115, 'STA*N%'),
	(20504, 'Sta_up_04', 115, 'STA*N%'),
	(20505, 'Sta_up_05', 115, 'STA*N%'),
	(20506, 'Sta_up_06', 115, 'STA*N%'),
	(20511, 'Sta_up_01', 115, 'STA*N%'),
	(20512, 'Sta_up_02', 115, 'STA*N%'),
	(20513, 'Sta_up_03', 115, 'STA*N%'),
	(20514, 'Sta_up_04', 115, 'STA*N%'),
	(20515, 'Sta_up_05', 115, 'STA*N%'),
	(20516, 'Sta_up_06', 115, 'STA*N%');
/*!40000 ALTER TABLE `SkillEffect` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillInfo 結構
CREATE TABLE IF NOT EXISTS `SkillInfo` (
  `SkillID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AliasCode` varchar(50) DEFAULT NULL COMMENT '識別碼(企劃用)',
  `SkillName` varchar(50) DEFAULT NULL COMMENT '技能名稱標籤',
  `Icon` smallint(6) NOT NULL DEFAULT 0 COMMENT '技能Icon編號',
  `Description` varchar(50) DEFAULT NULL COMMENT '技能描述標籤',
  `Energy` varchar(50) DEFAULT NULL COMMENT '能量條件 紅,黃,藍,綠',
  `Effect` varchar(50) DEFAULT NULL COMMENT '效果',
  `Cooldown` smallint(6) NOT NULL DEFAULT 100 COMMENT '冷卻時間',
  `Duration` smallint(6) NOT NULL DEFAULT 0 COMMENT '時效性',
  `Level1` int(11) NOT NULL DEFAULT 0 COMMENT '1級N值',
  `Level2` int(11) NOT NULL DEFAULT 0 COMMENT '2級N值',
  `Level3` int(11) NOT NULL DEFAULT 0 COMMENT '3級N值',
  `Level4` int(11) NOT NULL DEFAULT 0 COMMENT '4級N值',
  `Level5` int(11) NOT NULL DEFAULT 0 COMMENT '5級N值',
  `MaxDescription` varchar(50) DEFAULT NULL COMMENT '滿等級敘述標籤',
  `MaxCondition` tinyint(4) NOT NULL DEFAULT 0 COMMENT '滿等級技能條件',
  `MaxConditionValue` tinyint(4) NOT NULL DEFAULT 0 COMMENT '滿等級技能條件值',
  `MaxEffect` varchar(50) DEFAULT NULL COMMENT '滿等技能效果',
  PRIMARY KEY (`SkillID`),
  UNIQUE KEY `AliasCode` (`AliasCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能資訊表';

-- 正在傾印表格  koa_static.SkillInfo 的資料：~50 rows (近似值)
/*!40000 ALTER TABLE `SkillInfo` DISABLE KEYS */;
INSERT INTO `SkillInfo` (`SkillID`, `AliasCode`, `SkillName`, `Description`, `Energy`, `Effect`, `Cooldown`, `Duration`, `Level1`, `Level2`, `Level3`, `Level4`, `Level5`, `MaxDescription`, `MaxCondition`, `MaxConditionValue`, `MaxEffect`) VALUES
	(1, 'Lion011', '21001', '22204', '0,2,0,1', '20403', 200, 720, 500, 1000, 1500, 2000, 2500, '23007', 2, 3, '7'),
	(2, 'Lion012', '21002', '22205', '2,0,2,0', '20504', 200, 980, 500, 1000, 1500, 2000, 2500, '23008', 4, 1, '8'),
	(3, 'Lion013', '21003', '22202', '0,0,0,3', '20203', 200, 720, 500, 1000, 1500, 2000, 2500, '23011', 1, 1, '11'),
	(4, 'Lion014', '21004', '22201', '0,0,2,2', '20104', 200, 980, 500, 1000, 1500, 2000, 2500, '23012', 41, 0, '12'),
	(5, 'Lion015', '21005', '22203', '2,0,1,0', '20303', 200, 720, 500, 1000, 1500, 2000, 2500, '23012', 31, 0, '12'),
	(6, 'Lion016', '21006', '22202', '1,3,0,0', '20204', 200, 980, 500, 1000, 1500, 2000, 2500, '23013', 11, 0, '13'),
	(7, 'Lion021', '21007', '22204', '1,0,0,0', '20400', 200, -1, 100, 100, 200, 200, 300, '23014', 32, 0, '14'),
	(8, 'Lion022', '21008', '22205', '0,0,0,2', '20500', 200, -1, 200, 300, 400, 500, 600, '23015', 2, 3, '15'),
	(9, 'Lion023', '21009', '22202', '2,2,0,1', '20200', 200, -1, 500, 600, 900, 1200, 1500, '23016', 11, 0, '16'),
	(10, 'Lion024', '21010', '22201', '0,0,3,1', '20100', 200, -1, 400, 600, 800, 1000, 1200, '23017', 1, 1, '17'),
	(11, 'Lion025', '21011', '22203', '2,2,0,2', '20300', 200, -1, 600, 900, 1200, 1500, 1800, '23013', 22, 0, '13'),
	(12, 'Lion026', '21012', '22201', '0,1,2,0', '20100', 200, -1, 300, 400, 500, 700, 900, '23019', 51, 0, '19'),
	(13, 'Lion031', '21013', '22204', '2,0,0,0', '20402', 200, 500, 500, 1000, 1500, 2000, 2500, '23021', 23, 0, '21'),
	(14, 'Lion032', '21014', '22205', '0,2,0,2', '20504', 200, 980, 500, 1000, 1500, 2000, 2500, '23010', 1, 1, '10'),
	(15, 'Lion033', '21015', '22202', '0,1,0,0', '20201', 200, 300, 500, 1000, 1500, 2000, 2500, '23022', 11, 0, '22'),
	(16, 'Lion034', '21016', '22201', '1,0,1,0', '20102', 200, 500, 500, 1000, 1500, 2000, 2500, '23013', 12, 0, '13'),
	(17, 'Lion035', '21017', '22203', '0,0,0,1', '20301', 200, 300, 500, 1000, 1500, 2000, 2500, '23023', 2, 3, '23'),
	(18, 'Lion036', '21018', '22204', '0,0,2,0', '20402', 200, 500, 500, 1000, 1500, 2000, 2500, '23023', 43, 0, '23'),
	(19, 'Lion041', '21019', '22204', '0,0,6,0', '20406', 200, 1570, 500, 1000, 1500, 2000, 2500, '23024', 23, 0, '24'),
	(20, 'Lion042', '21020', '22205', '0,4,0,0', '20506', 200, 980, 500, 1000, 1500, 2000, 2500, '23009', 1, 2, '9'),
	(21, 'Lion043', '21021', '22202', '6,0,0,0', '20206', 200, 1570, 500, 1000, 1500, 2000, 2500, '23025', 51, 0, '25'),
	(22, 'Lion044', '21022', '22201', '0,3,0,2', '20105', 200, 1260, 500, 1000, 1500, 2000, 2500, '23026', 12, 0, '26'),
	(23, 'Lion045', '21023', '22203', '2,0,0,3', '20305', 200, 1260, 500, 1000, 1500, 2000, 2500, '23027', 2, 3, '27'),
	(24, 'Lion046', '21024', '22203', '0,0,2,3', '20315', 200, 1260, 500, 1000, 1500, 2000, 2500, '23028', 42, 0, '28'),
	(25, 'deer011', '21025', '22203', '0,2,0,2', '20314', 200, 1460, 300, 600, 1000, 1300, 1600, '23013', 22, 0, '13'),
	(26, 'deer012', '21026', '22204', '2,0,1,0', '20413', 200, 1080, 300, 600, 1000, 1300, 1600, '23022', 23, 0, '22'),
	(27, 'deer013', '21027', '22205', '0,1,3,0', '20514', 200, 1460, 300, 600, 1000, 1300, 1600, '23029', 3, 4, '29'),
	(28, 'deer014', '21028', '22202', '1,0,0,2', '20213', 200, 1080, 300, 600, 1000, 1300, 1600, '23022', 3, 3, '22'),
	(29, 'deer015', '21029', '22201', '2,2,0,0', '20114', 200, 1460, 300, 600, 1000, 1300, 1600, '23012', 1, 8, '12'),
	(30, 'deer016', '21030', '22205', '0,0,2,1', '20513', 200, 1080, 300, 600, 1000, 1300, 1600, '23007', 5, 1, '7'),
	(31, 'Norn_3', '21031', '22201', '2,1,0,0', '20103', 200, 720, 500, 1000, 1500, 2000, 2500, '23030', 41, 0, '30'),
	(32, 'Norn_4', '21032', '22201', '2,2,0,0', '20104', 200, 980, 500, 1000, 1500, 2000, 2500, '23031', 41, 0, '31'),
	(33, 'Norn_5', '21033', '22201', '2,2,1,0', '20105', 200, 1260, 500, 1000, 1500, 2000, 2500, '23032', 41, 0, '32'),
	(34, 'Norn_6', '21034', '22201', '2,2,2,0', '20106', 200, 1570, 500, 1000, 1500, 2000, 2500, '23033', 41, 0, '33'),
	(35, 'Bil_3', '21035', '22202', '1,1,1,0', '20200', 200, -1, 300, 400, 600, 800, 1000, '23034', 32, 0, '34'),
	(36, 'Bil_4', '21036', '22202', '1,1,1,0', '20200', 200, -1, 400, 500, 700, 1000, 1300, '23035', 32, 0, '35'),
	(37, 'Bil_5', '21037', '22202', '1,1,1,0', '20200', 200, -1, 500, 600, 900, 1300, 1600, '23036', 32, 0, '36'),
	(38, 'Bil_6', '21038', '22202', '1,1,1,0', '20200', 200, -1, 600, 700, 1100, 1600, 2000, '23037', 32, 0, '37'),
	(39, 'Demeter_3', '21039', '22203', '0,2,1,0', '20303', 200, 720, 500, 1000, 1500, 2000, 2500, '23038', 43, 0, '38'),
	(40, 'Demeter_4', '21040', '22203', '0,2,2,0', '20303', 200, 720, 600, 1200, 1800, 2400, 3000, '23039', 43, 0, '39'),
	(41, 'Demeter_5', '21041', '22203', '0,2,3,0', '20303', 200, 720, 700, 1400, 2100, 2800, 3600, '23040', 43, 0, '40'),
	(42, 'Demeter_6', '21042', '22203', '0,2,3,1', '20303', 200, 720, 800, 1600, 2500, 3300, 4300, '23041', 43, 0, '41'),
	(43, 'Artemis_3', '21043', '22204', '0,0,1,2', '20403', 200, 720, 500, 1000, 1500, 2000, 2500, '23042', 42, 0, '42'),
	(44, 'Artemis_4', '21044', '22204', '0,0,2,2', '20404', 200, 980, 500, 1000, 1500, 2000, 2500, '23043', 42, 0, '43'),
	(45, 'Artemis_5', '21045', '22204', '1,0,2,2', '20405', 200, 1260, 500, 1000, 1500, 2000, 2500, '23044', 42, 0, '44'),
	(46, 'Artemis_6', '21046', '22204', '2,0,2,2', '20406', 200, 1570, 500, 1000, 1500, 2000, 2500, '23045', 42, 0, '45'),
	(47, 'Eir_3', '21047', '22205', '0,2,0,1', '20503', 200, 720, 500, 1000, 1500, 2000, 2500, '23046', 4, 1, '46'),
	(48, 'Eir_4', '21048', '22205', '0,2,0,2', '20504', 200, 980, 500, 1000, 1500, 2000, 2500, '23047', 4, 1, '47'),
	(49, 'Eir_5', '21049', '22205', '1,2,0,2', '20505', 200, 1260, 500, 1000, 1500, 2000, 2500, '23048', 4, 1, '48'),
	(50, 'Eir_6', '21050', '22205', '2,2,0,2', '20506', 200, 1570, 500, 1000, 1500, 2000, 2500, '23049', 4, 1, '49'),
	(51, 'Lion051', '21051', '22204', '0,2,0,0', '20402', 200, 500, 500, 1000, 1500, 2000, 2500, '23011', 31, 0, '11'),
	(52, 'Lion052', '21052', '22205', '2,0,0,0', '20502', 200, 980, 500, 1000, 1500, 2000, 2500, '23013', 4, 1, '13'),
	(53, 'Lion053', '21053', '22202', '0,1,2,0', '20203', 200, 720, 500, 1000, 1500, 2000, 2500, '23012', 1, 2, '12'),
	(54, 'Lion054', '21054', '22201', '1,0,0,2', '20103', 200, 720, 500, 1000, 1500, 2000, 2500, '23007', 1, 1, '7'),
	(55, 'Lion055', '21055', '22203', '2,2,0,0', '20304', 200, 500, 500, 1000, 1500, 2000, 2500, '23022', 2, 3, '22'),
	(56, 'Lion056', '21056', '22205', '0,0,2,2', '20504', 200, 980, 500, 1000, 1500, 2000, 2500, '23047', 51, 0, '47'),
	(57, 'Pige001', '21057', '22203', '3,0,0,1', '20304', 200, 610, 800, 1600, 2400, 3200, 4000, '23047', 43, 0, '47'),
	(58, 'Pige002', '21058', '22204', '0,2,0,2', '20404', 200, 610, 800, 1600, 2400, 3200, 4000, '23010', 4, 1, '10'),
	(59, 'Pige003', '21059', '22205', '0,3,1,0', '20504', 200, 610, 800, 1600, 2400, 3200, 4000, '23050', 22, 0, '50'),
	(60, 'Pige004', '21060', '22201', '1,0,2,0', '20103', 200, 450, 800, 1600, 2400, 3200, 4000, '23021', 22, 0, '21'),
	(61, 'Pige005', '21061', '22202', '1,0,0,2', '20203', 200, 450, 800, 1600, 2400, 3200, 4000, '23012', 5, 1, '12'),
	(62, 'Pige006', '21062', '22202', '0,0,3,0', '20203', 200, 450, 800, 1600, 2400, 3200, 4000, '23007', 22, 0, '7');
/*!40000 ALTER TABLE `SkillInfo` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillMaxEffect 結構
CREATE TABLE IF NOT EXISTS `SkillMaxEffect` (
  `MaxEffectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EffectName` varchar(50) DEFAULT NULL COMMENT '效果名稱或標籤',
  `EffectType` smallint(6) NOT NULL DEFAULT 0 COMMENT '效果類型',
  `Target` tinyint(4) NOT NULL DEFAULT 0 COMMENT '作用對象',
  `Formula` text DEFAULT NULL COMMENT '公式(或值)',
  PRIMARY KEY (`MaxEffectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='滿等級技能效果表';

-- 正在傾印表格  koa_static.SkillMaxEffect 的資料：~44 rows (近似值)
/*!40000 ALTER TABLE `SkillMaxEffect` DISABLE KEYS */;
INSERT INTO `SkillMaxEffect` (`MaxEffectID`, `EffectName`, `EffectType`, `Target`, `Formula`) VALUES
	(7, 'S_ADD_01', 102, 0, '0.5'),
	(8, 'change_c01', 504, 0, NULL),
	(9, 'change_c02', 505, 0, NULL),
	(10, 'change_c03', 506, 0, NULL),
	(11, 'Sunny_add_01', 141, 0, '10'),
	(12, 'S_UP_01', 102, 0, 'S*20%'),
	(13, 'Dune_add_01', 121, 0, '10'),
	(14, 'S_ADD_02', 102, 0, '0.1'),
	(15, 'S_UP_02', 102, 0, 'S*5%'),
	(16, 'agwind_add_1', 132, 0, '10'),
	(17, 'S_UP_03', 102, 0, 'S*10%'),
	(18, 'change_w01', 501, 0, NULL),
	(19, 'change_w02', 503, 0, NULL),
	(20, 'change_w03', 502, 0, NULL),
	(21, 'dust_add_01', 143, 0, '10'),
	(22, 'H_red_01', 101, 0, '-0.1'),
	(23, 'H_down_01', 101, 0, '-H*20%'),
	(24, 'att_HP_01', 201, 1, '-20'),
	(25, 'Dune_add_02', 121, 0, '20'),
	(26, 'att_S_01', 102, 3, '-0.5'),
	(27, 'att_H_01', 101, 3, '-0.2'),
	(28, 'att_INT_01', 114, 4, '-10'),
	(29, 'HP_ADD_01', 201, 0, 'HP*20%'),
	(30, 'Norn_S_up_1', 102, 0, 'S*15%'),
	(31, 'Norn_S_up_2', 102, 0, 'S*20%'),
	(32, 'Norn_S_up_3', 102, 0, 'S*25%'),
	(33, 'Norn_S_up_4', 102, 0, 'S*30%'),
	(34, 'Bil_S_UP_3', 102, 0, 'S*5%'),
	(35, 'Bil_S_up_2', 102, 0, 'S*10%'),
	(36, 'Bil_S_up_3', 102, 0, 'S*15%'),
	(37, 'Bil_S_up_4', 102, 0, 'S*20%'),
	(38, 'Demeter_H_01', 101, 0, '-H*20%'),
	(39, 'Demeter_H_02', 101, 0, '-H*30%'),
	(40, 'Demeter_H_03', 101, 0, '-H*40%'),
	(41, 'Demeter_H_04', 101, 0, '-H*50%'),
	(42, 'Artemis_att_HP_01', 201, 5, '-5'),
	(43, 'Artemis_att_HP_02', 201, 5, '-10'),
	(44, 'Artemis_att_HP_03', 201, 5, '-15'),
	(45, 'Artemis_att_HP_04', 201, 5, '-20'),
	(46, 'Eir_ADD_HP_01', 201, 0, '15'),
	(47, 'Eir_ADD_HP_02', 201, 0, '20'),
	(48, 'Eir_ADD_HP_03', 201, 0, '25'),
	(49, 'Eir_ADD_HP_04', 201, 0, '30'),
	(50, 'Dune_add_01', 123, 0, '10');
/*!40000 ALTER TABLE `SkillMaxEffect` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillPart 結構
CREATE TABLE IF NOT EXISTS `SkillPart` (
  `SkillPartID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PartCode` varchar(50) DEFAULT NULL COMMENT '部位外觀碼',
  `PartType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '部位',
  `AliasCode1` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `AliasCode2` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `AliasCode3` varchar(50) DEFAULT NULL COMMENT '技能識別碼',
  `SkillAffixID` int(11) unsigned NOT NULL COMMENT '詞綴技能ID',
  PRIMARY KEY (`SkillPartID`),
  UNIQUE KEY `PartCode_PartType` (`PartCode`,`PartType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='部位技能對照表';

-- 正在傾印表格  koa_static.SkillPart 的資料：~27 rows (近似值)
/*!40000 ALTER TABLE `SkillPart` DISABLE KEYS */;
INSERT INTO `SkillPart` (`SkillPartID`, `PartCode`, `PartType`, `AliasCode1`, `AliasCode2`, `AliasCode3`, `SkillAffixID`) VALUES
	(1, '110101', 1, 'Lion011', NULL, NULL, 1),
	(2, '110101', 2, 'Lion012', NULL, NULL, 1),
	(3, '110101', 3, 'Lion013', NULL, NULL, 1),
	(4, '110101', 4, 'Lion014', NULL, NULL, 1),
	(5, '110101', 5, 'Lion015', NULL, NULL, 1),
	(6, '110101', 6, 'Lion016', NULL, NULL, 1),
	(7, '110102', 1, 'Lion021', NULL, NULL, 2),
	(8, '110102', 2, 'Lion022', NULL, NULL, 2),
	(9, '110102', 3, 'Lion023', NULL, NULL, 2),
	(10, '110102', 4, 'Lion024', NULL, NULL, 2),
	(11, '110102', 5, 'Lion025', NULL, NULL, 2),
	(12, '110102', 6, 'Lion026', NULL, NULL, 2),
	(13, '110103', 1, 'Lion031', NULL, NULL, 3),
	(14, '110103', 2, 'Lion032', NULL, NULL, 3),
	(15, '110103', 3, 'Lion033', NULL, NULL, 3),
	(16, '110103', 4, 'Lion034', NULL, NULL, 3),
	(17, '110103', 5, 'Lion035', NULL, NULL, 3),
	(18, '110103', 6, 'Lion036', NULL, NULL, 3),
	(19, '110104', 1, 'Lion041', NULL, NULL, 4),
	(20, '110104', 2, 'Lion042', NULL, NULL, 4),
	(21, '110104', 3, 'Lion043', NULL, NULL, 4),
	(22, '110104', 4, 'Lion044', NULL, NULL, 4),
	(23, '110104', 5, 'Lion045', NULL, NULL, 4),
	(24, '110104', 6, 'Lion046', NULL, NULL, 4),
	(25, '120301', 1, 'deer011', NULL, NULL, 5),
	(26, '120301', 2, 'deer012', NULL, NULL, 5),
	(27, '120301', 3, 'deer013', NULL, NULL, 5),
	(28, '120301', 4, 'deer014', NULL, NULL, 5),
	(29, '120301', 5, 'deer015', NULL, NULL, 5),
	(30, '120301', 6, 'deer016', NULL, NULL, 5),
	(31, '110205', 1, 'Lion051', NULL, NULL, 5),
	(32, '110205', 2, 'Lion052', NULL, NULL, 5),
	(33, '110205', 3, 'Lion053', NULL, NULL, 5),
	(34, '110205', 4, 'Lion054', NULL, NULL, 5),
	(35, '110205', 5, 'Lion055', NULL, NULL, 5),
	(36, '110205', 6, 'Lion056', NULL, NULL, 5),
	(37, '210301', 1, 'Pige001', NULL, NULL, 3),
	(38, '210301', 2, 'Pige002', NULL, NULL, 3),
	(39, '210301', 3, 'Pige003', NULL, NULL, 3),
	(40, '210301', 4, 'Pige004', NULL, NULL, 3),
	(41, '210301', 5, 'Pige005', NULL, NULL, 3),
	(42, '210301', 6, 'Pige006', NULL, NULL, 3),
	(43, '110106', 1, 'Lion011', NULL, NULL, 1),
	(44, '110106', 2, 'Lion012', NULL, NULL, 1),
	(45, '110106', 3, 'Lion013', NULL, NULL, 1),
	(46, '110106', 4, 'Lion014', NULL, NULL, 1),
	(47, '110106', 5, 'Lion015', NULL, NULL, 1),
	(48, '110106', 6, 'Lion016', NULL, NULL, 1),
	(49, '110207', 1, 'Lion021', NULL, NULL, 2),
	(50, '110207', 2, 'Lion022', NULL, NULL, 2),
	(51, '110207', 3, 'Lion023', NULL, NULL, 2),
	(52, '110207', 4, 'Lion024', NULL, NULL, 2),
	(53, '110207', 5, 'Lion025', NULL, NULL, 2),
	(54, '110207', 6, 'Lion026', NULL, NULL, 2),
	(55, '110208', 1, 'Lion031', NULL, NULL, 3),
	(56, '110208', 2, 'Lion032', NULL, NULL, 3),
	(57, '110208', 3, 'Lion033', NULL, NULL, 3),
	(58, '110208', 4, 'Lion034', NULL, NULL, 3),
	(59, '110208', 5, 'Lion035', NULL, NULL, 3),
	(60, '110208', 6, 'Lion036', NULL, NULL, 3),
	(61, '110109', 1, 'Lion041', NULL, NULL, 4),
	(62, '110109', 2, 'Lion042', NULL, NULL, 4),
	(63, '110109', 3, 'Lion043', NULL, NULL, 4),
	(64, '110109', 4, 'Lion044', NULL, NULL, 4),
	(65, '110109', 5, 'Lion045', NULL, NULL, 4),
	(66, '110109', 6, 'Lion046', NULL, NULL, 4),
	(67, '110110', 1, 'deer011', NULL, NULL, 5),
	(68, '110110', 2, 'deer012', NULL, NULL, 5),
	(69, '110110', 3, 'deer013', NULL, NULL, 5),
	(70, '110110', 4, 'deer014', NULL, NULL, 5),
	(71, '110110', 5, 'deer015', NULL, NULL, 5),
	(72, '110110', 6, 'deer016', NULL, NULL, 5),
	(73, '110211', 1, 'Lion051', NULL, NULL, 5),
	(74, '110211', 2, 'Lion052', NULL, NULL, 5),
	(75, '110211', 3, 'Lion053', NULL, NULL, 5),
	(76, '110211', 4, 'Lion054', NULL, NULL, 5),
	(77, '110211', 5, 'Lion055', NULL, NULL, 5),
	(78, '110211', 6, 'Lion056', NULL, NULL, 5),
	(79, '130301', 1, 'Pige001', NULL, NULL, 3),
	(80, '130301', 2, 'Pige002', NULL, NULL, 3),
	(81, '130301', 3, 'Pige003', NULL, NULL, 3),
	(82, '130301', 4, 'Pige004', NULL, NULL, 3),
	(83, '130301', 5, 'Pige005', NULL, NULL, 3),
	(84, '130301', 6, 'Pige006', NULL, NULL, 3),
	(85, '130302', 1, 'Lion011', NULL, NULL, 1),
	(86, '130302', 2, 'Lion012', NULL, NULL, 1),
	(87, '130302', 3, 'Lion013', NULL, NULL, 1),
	(88, '130302', 4, 'Lion014', NULL, NULL, 1),
	(89, '130302', 5, 'Lion015', NULL, NULL, 1),
	(90, '130302', 6, 'Lion016', NULL, NULL, 1),
	(91, '130303', 1, 'Lion021', NULL, NULL, 2),
	(92, '130303', 2, 'Lion022', NULL, NULL, 2),
	(93, '130303', 3, 'Lion023', NULL, NULL, 2),
	(94, '130303', 4, 'Lion024', NULL, NULL, 2),
	(95, '130303', 5, 'Lion025', NULL, NULL, 2),
	(96, '130303', 6, 'Lion026', NULL, NULL, 2),
	(97, '140301', 1, 'Lion031', NULL, NULL, 3),
	(98, '140301', 2, 'Lion032', NULL, NULL, 3),
	(99, '140301', 3, 'Lion033', NULL, NULL, 3),
	(100, '140301', 4, 'Lion034', NULL, NULL, 3),
	(101, '140301', 5, 'Lion035', NULL, NULL, 3),
	(102, '140301', 6, 'Lion036', NULL, NULL, 3),
	(103, '140102', 1, 'Lion041', NULL, NULL, 4),
	(104, '140102', 2, 'Lion042', NULL, NULL, 4),
	(105, '140102', 3, 'Lion043', NULL, NULL, 4),
	(106, '140102', 4, 'Lion044', NULL, NULL, 4),
	(107, '140102', 5, 'Lion045', NULL, NULL, 4),
	(108, '140102', 6, 'Lion046', NULL, NULL, 4),
	(109, '140303', 1, 'deer011', NULL, NULL, 5),
	(110, '140303', 2, 'deer012', NULL, NULL, 5),
	(111, '140303', 3, 'deer013', NULL, NULL, 5),
	(112, '140303', 4, 'deer014', NULL, NULL, 5),
	(113, '140303', 5, 'deer015', NULL, NULL, 5),
	(114, '140303', 6, 'deer016', NULL, NULL, 5),
	(115, '140204', 1, 'Lion051', NULL, NULL, 5),
	(116, '140204', 2, 'Lion052', NULL, NULL, 5),
	(117, '140204', 3, 'Lion053', NULL, NULL, 5),
	(118, '140204', 4, 'Lion054', NULL, NULL, 5),
	(119, '140204', 5, 'Lion055', NULL, NULL, 5),
	(120, '140204', 6, 'Lion056', NULL, NULL, 5),
	(121, '140105', 1, 'Pige001', NULL, NULL, 3),
	(122, '140105', 2, 'Pige002', NULL, NULL, 3),
	(123, '140105', 3, 'Pige003', NULL, NULL, 3),
	(124, '140105', 4, 'Pige004', NULL, NULL, 3),
	(125, '140105', 5, 'Pige005', NULL, NULL, 3),
	(126, '140105', 6, 'Pige006', NULL, NULL, 3),
	(127, '140206', 1, 'Lion011', NULL, NULL, 1),
	(128, '140206', 2, 'Lion012', NULL, NULL, 1),
	(129, '140206', 3, 'Lion013', NULL, NULL, 1),
	(130, '140206', 4, 'Lion014', NULL, NULL, 1),
	(131, '140206', 5, 'Lion015', NULL, NULL, 1),
	(132, '140206', 6, 'Lion016', NULL, NULL, 1);
/*!40000 ALTER TABLE `SkillPart` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
