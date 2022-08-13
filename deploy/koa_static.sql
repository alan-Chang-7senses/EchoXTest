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
  `RewardID` int(11) NOT NULL DEFAULT 0 COMMENT '獎勵編號',
  `Source` varchar(255) NOT NULL DEFAULT '' COMMENT '來源代號',
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='物品資訊表';

-- 正在傾印表格  koa_static.ItemInfo 的資料：~35 rows (近似值)
/*!40000 ALTER TABLE `ItemInfo` DISABLE KEYS */;
INSERT INTO `ItemInfo` (`ItemID`, `ItemName`, `Description`, `ItemType`, `Icon`, `StackLimit`, `UseType`, `EffectType`, `EffectValue`, `RewardID`, `Source`) VALUES
	(-4, '8133', '8633', 1, 'ItemIcon_0040', 2147483647, 0, 0, 0, 0, ''),
	(-3, '8134', '8634', 1, 'ItemIcon_0028', 2147483647, 0, 0, 0, 0, ''),
	(-2, '8132', '8632', 1, 'ItemIcon_0026', 2147483647, 0, 0, 0, 0, ''),
	(-1, '8135', '8635', 1, 'ItemIcon_0029', 2147483647, 0, 0, 0, 0, ''),
	(1001, '8101', '8601', 1, 'ItemIcon_1001', 99999, 0, 101, 150, 0, 'pve,store,s101'),
	(1002, '8102', '8602', 1, 'ItemIcon_1002', 99999, 0, 101, 750, 0, 'pvp1,store,s102'),
	(1003, '8103', '8603', 1, 'ItemIcon_1003', 99999, 0, 101, 2100, 0, 'pvp2,store,s103'),
	(1111, '8104', '8604', 1, 'ItemIcon_0004', 99999, 0, 201, 0, 0, 'pvp3'),
	(1112, '8105', '8605', 1, 'ItemIcon_0005', 99999, 0, 202, 0, 0, 's008'),
	(1121, '8106', '8606', 1, 'ItemIcon_0006', 99999, 0, 203, 0, 0, 's012'),
	(1122, '8107', '8607', 1, 'ItemIcon_0007', 99999, 0, 204, 0, 0, 's009'),
	(1131, '8108', '8608', 1, 'ItemIcon_0008', 99999, 0, 205, 0, 0, 's010'),
	(1132, '8109', '8609', 1, 'ItemIcon_0009', 99999, 0, 206, 0, 0, 's011'),
	(2000, '8110', '8610', 2, 'ItemIcon_0010', 99999, 0, 0, 0, 0, 's001'),
	(2001, '8118', '8618', 2, 'ItemIcon_0039_2', 99999, 0, 0, 0, 0, ''),
	(2002, '8117', '8617', 2, 'ItemIcon_0039_1', 99999, 0, 0, 0, 0, ''),
	(2011, '8111', '8611', 2, 'ItemIcon_0011', 99999, 0, 0, 0, 0, 's002'),
	(2013, '8113', '8613', 2, 'ItemIcon_0012', 99999, 0, 0, 0, 0, 's004'),
	(2014, '8112', '8612', 2, 'ItemIcon_0013', 99999, 0, 0, 0, 0, 's003'),
	(2015, '8116', '8616', 2, 'ItemIcon_0014', 99999, 0, 0, 0, 0, 's007'),
	(2016, '8115', '8615', 2, 'ItemIcon_0015', 99999, 0, 0, 0, 0, 's006'),
	(2017, '8114', '8614', 2, 'ItemIcon_0016', 99999, 0, 0, 0, 0, 's005'),
	(3001, '8119', '8619', 3, 'ItemIcon_0017_2', 999, 1, 0, 0, 1, '0'),
	(3002, '8120', '8620', 3, 'ItemIcon_0018_2', 999, 1, 0, 0, 2, '0'),
	(3003, '8124', '8624', 3, 'ItemIcon_0019_2', 999, 1, 0, 0, 6, '0'),
	(4001, '8121', '8621', 4, 'ItemIcon_0020_1', 999, 1, 0, 0, 3, '0'),
	(4002, '8122', '8622', 4, 'ItemIcon_0021_1', 999, 1, 0, 0, 4, '0'),
	(4003, '8123', '8623', 4, 'ItemIcon_0020_3', 999, 1, 0, 0, 5, '0'),
	(4004, '8125', '8625', 4, 'ItemIcon_0021_1', 999, 1, 0, 0, 7, '0'),
	(4005, '8126', '8626', 4, 'ItemIcon_0025_1', 999, 1, 0, 0, 8, '0'),
	(4006, '8127', '8627', 4, 'ItemIcon_0025_2', 999, 1, 0, 0, 9, '0'),
	(4007, '8128', '8628', 4, 'ItemIcon_0025_3', 999, 1, 0, 0, 10, '0'),
	(5100, '8129', '8629', 5, 'ItemIcon_0032', 999, 0, 0, 0, 0, '0'),
	(5201, '8130', '8630', 5, 'ItemIcon_0031', 999, 0, 0, 0, 0, '0'),
	(5202, '8131', '8631', 5, 'ItemIcon_0030', 999, 0, 0, 0, 0, '0');
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
  PRIMARY KEY (`Serial`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='信件資訊';

-- 正在傾印表格  koa_static.MailsInfo 的資料：~13 rows (近似值)
/*!40000 ALTER TABLE `MailsInfo` DISABLE KEYS */;
INSERT INTO `MailsInfo` (`Serial`, `MailsID`, `Status`, `Type`, `Lang`, `Title`, `Content`, `Sender`, `URL`) VALUES
	(1, 1, 1, 0, 0, 'Welcome to PetaRush,', 'So glad to have you in PetaRush. Come and join the festivity!', 'Sender: Direct Team', 'https://www.google.com'),
	(2, 1, 1, 0, 2, 'Welcome to PetaRush,', 'So glad to have you in PetaRush. Come and join the festivity!', 'Sender: Direct Team', 'https://www.google.com'),
	(3, 1, 1, 0, 6, '《PetaRush》へようこそ', '《PetaRush》へようこそ，一緒に《PetaRush》で盛り上がりましょう！', '送信者：研究開発チーム', ''),
	(4, 1, 1, 0, 7, '<PetaRush>에 오신 걸 축하드립니다.', '<PetaRush>에 오신 걸 환영합니다. 당신을 <페타 러시> Peta의 대형 행사에 초대합니다!', '발신자: 개발팀', ''),
	(5, 1, 1, 0, 11, 'ยินดีต้อนรับสู่PetaRush', 'ยินดีต้อนรับสู่ 《PetaRush》เราขอเชิญคุณมาร่วมงานPetaครั้งสำคัญใน PetaRush ', 'ผู้ส่ง：ทีมผู้วิจัยและพัฒนา', ''),
	(6, 1, 1, 0, 5, 'Selamat datang di PetaRush', 'Selamat datang di PetaRush. Dengan senang hati, kami mengundang Anda untuk bergabung dengan kami di acara akbar Peta, yaitu PetaRush!', 'Pengirim: Tim Pengembang', ''),
	(7, 1, 1, 0, 4, 'Maligayang pagdating sa “PetaRush”', 'Maligayang pagdating sa “PetaRush”. Taos-puso kaming inaanyayahan na sumali sa amin sa malaking kaganapan ng Peta, “pagtakbo ng hayop” !', 'Nagpadala: pangkat ng R&D', ''),
	(8, 1, 1, 0, 3, 'Bienvenido a PetaRush', '¡Bienvenido a PetaRush! ¡Venga y únase a la fiesta de Peta con nosotros!', 'Remitente: Equipo de desarrolladores', ''),
	(9, 1, 1, 0, 1, 'Willkommen bei PetaRush', 'Willkommen bei PetaRush! Komm und feiere mit uns das Peta Fest!', 'Absender: Entwickler-Team', ''),
	(10, 1, 1, 0, 9, 'Bem-vindo à PetaRush', 'Bem-vindo à PetaRush! Vem participar das comemorações da Peta com a gente!', 'Remetente: Equipe de desenvolvedores', ''),
	(11, 1, 1, 0, 10, 'Добро пожаловать в PetaRush', 'Добро пожаловать в PetaRush! Присоединяйтесь к празднику Peta вместе с нами!', 'Отправитель: команда разработчиков', ''),
	(12, 1, 1, 0, 8, '"PetaRush" မွ လႈိက္လွဲစြာႀကိဳဆိုပါသည္။', '"PetaRush" မွ ႀကိဳဆိုပါသည္။ Peta ၏အဓိကပြဲျဖစ္သည့္ "PetaRush" တြင္ ပါဝင္ဆင္ႏႊဲရန္ ေလးစားစြာျဖင့္ ဖိတ္ၾကားအပ္ပါသည္။', 'ေပးပို႔သူ- R&D အဖဲြ႔', ''),
	(13, 1, 1, 0, 12, '歡迎來到《PetaRush》', '歡迎來到《PetaRush》誠摯邀請你一同來參與《動物大奔走》這個Peta的大型盛事！', '寄件人：研發團隊', '');
/*!40000 ALTER TABLE `MailsInfo` ENABLE KEYS */;

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
	(1, 1001, 1001),
	(2, 1001, 1001);
/*!40000 ALTER TABLE `QualifyingArena` ENABLE KEYS */;

-- 傾印  資料表 koa_static.RewardContent 結構
CREATE TABLE IF NOT EXISTS `RewardContent` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ContentGroupID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '群組編號',
  `ItemID` int(11) NOT NULL DEFAULT 0 COMMENT '獎勵內容',
  `Amount` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵數量',
  `Proportion` smallint(4) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵權重或機率(千分比)',
  PRIMARY KEY (`Serial`),
  KEY `ContentGroupID` (`ContentGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='獎勵內容';

-- 正在傾印表格  koa_static.RewardContent 的資料：~32 rows (近似值)
/*!40000 ALTER TABLE `RewardContent` DISABLE KEYS */;
INSERT INTO `RewardContent` (`Serial`, `ContentGroupID`, `ItemID`, `Amount`, `Proportion`) VALUES
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
/*!40000 ALTER TABLE `RewardContent` ENABLE KEYS */;

-- 傾印  資料表 koa_static.RewardInfo 結構
CREATE TABLE IF NOT EXISTS `RewardInfo` (
  `RewardID` int(11) NOT NULL COMMENT '獎勵編號',
  `Modes` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵模式',
  `Times` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '發放次數',
  `ContentGroupID` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '獎勵內容群組',
  PRIMARY KEY (`RewardID`),
  KEY `ContentGroupID` (`ContentGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='獎勵資訊';

-- 正在傾印表格  koa_static.RewardInfo 的資料：~10 rows (近似值)
/*!40000 ALTER TABLE `RewardInfo` DISABLE KEYS */;
INSERT INTO `RewardInfo` (`RewardID`, `Modes`, `Times`, `ContentGroupID`) VALUES
	(1, 3, 1, 1),
	(2, 3, 1, 2),
	(3, 2, 1, 1),
	(4, 2, 1, 2),
	(5, 3, 1, 4),
	(6, 5, 1, 3),
	(7, 2, 1, 3),
	(8, 2, 1, 5),
	(9, 2, 1, 6),
	(10, 2, 1, 7);
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

-- 正在傾印表格  koa_static.SceneClimate 的資料：~24 rows (近似值)
/*!40000 ALTER TABLE `SceneClimate` DISABLE KEYS */;
INSERT INTO `SceneClimate` (`SceneClimateID`, `SceneID`, `Weather`, `WindDirection`, `WindSpeed`, `StartTime`, `Lighting`) VALUES
	(1, 1001, 2, 1, 50, 0, 2),
	(2, 1001, 1, 1, 50, 3600, 2),
	(3, 1001, 1, 1, 50, 7200, 1),
	(4, 1001, 1, 1, 50, 10800, 1),
	(5, 1001, 3, 1, 50, 14400, 1),
	(6, 1001, 3, 1, 50, 18000, 1),
	(7, 1001, 3, 2, 50, 21600, 1),
	(8, 1001, 3, 2, 50, 25200, 1),
	(9, 1001, 3, 2, 50, 28800, 1),
	(10, 1001, 3, 2, 50, 32400, 1),
	(11, 1001, 1, 2, 50, 36000, 1),
	(12, 1001, 1, 2, 50, 39600, 1),
	(13, 1001, 1, 2, 50, 43200, 1),
	(14, 1001, 2, 3, 50, 46800, 1),
	(15, 1001, 2, 3, 50, 50400, 2),
	(16, 1001, 2, 3, 50, 54000, 2),
	(17, 1001, 3, 3, 50, 57600, 2),
	(18, 1001, 3, 3, 50, 61200, 2),
	(19, 1001, 3, 3, 50, 64800, 2),
	(20, 1001, 3, 4, 50, 68400, 2),
	(21, 1001, 3, 4, 50, 72000, 2),
	(22, 1001, 3, 4, 50, 75600, 2),
	(23, 1001, 2, 4, 50, 79200, 2),
	(24, 1001, 2, 4, 50, 82800, 2);
/*!40000 ALTER TABLE `SceneClimate` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SceneInfo 結構
CREATE TABLE IF NOT EXISTS `SceneInfo` (
  `SceneID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SceneName` varchar(50) NOT NULL DEFAULT '' COMMENT '場景代號（名稱）',
  `SceneEnv` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '場景環境',
  PRIMARY KEY (`SceneID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='場景主要資訊';

-- 正在傾印表格  koa_static.SceneInfo 的資料：~24 rows (近似值)
/*!40000 ALTER TABLE `SceneInfo` DISABLE KEYS */;
INSERT INTO `SceneInfo` (`SceneID`, `SceneName`, `SceneEnv`) VALUES
	(1001, '9001', 1),
	(1002, '9003', 1),
	(1003, '9004', 1),
	(1004, '9005', 1),
	(1005, '9006', 1),
	(1006, '9007', 1),
	(1007, '9008', 1),
	(1008, '9009', 1),
	(2001, '9010', 2),
	(2002, '9011', 2),
	(2003, '9012', 2),
	(2004, '9013', 2),
	(2005, '9014', 2),
	(2006, '9015', 2),
	(2007, '9016', 2),
	(2008, '9017', 2),
	(3001, '9018', 3),
	(3002, '9019', 3),
	(3003, '9020', 3),
	(3004, '9021', 3),
	(3005, '9022', 3),
	(3006, '9023', 3),
	(3007, '9024', 3),
	(3008, '9025', 3);
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

-- 正在傾印表格  koa_static.SkillAffixAlias 的資料：~12 rows (近似值)
/*!40000 ALTER TABLE `SkillAffixAlias` DISABLE KEYS */;
INSERT INTO `SkillAffixAlias` (`SkillAffixID`, `Level1`, `Level2`, `Level3`, `Level4`, `Level5`, `Level6`) VALUES
	(1, NULL, 'Aimee_2', 'Aimee_3', 'Aimee_4', 'Aimee_5', 'Aimee_6'),
	(2, NULL, 'Ceto_2', 'Ceto_3', 'Ceto_4', 'Ceto_5', 'Ceto_6'),
	(3, NULL, 'Prudence_2', 'Prudence_3', 'Prudence_4', 'Prudence_5', 'Prudence_6'),
	(4, NULL, 'Olé_2', 'Olé_3', 'Olé_4', 'Olé_5', 'Olé_6'),
	(5, NULL, 'Oz_2', 'Oz_3', 'Oz_4', 'Oz_5', 'Oz_6'),
	(6, NULL, 'Al_2', 'Al_3', 'Al_4', 'Al_5', 'Al_6'),
	(7, NULL, 'Gottloh_2', 'Gottloh_3', 'Gottloh_4', 'Gottloh_5', 'Gottloh_6'),
	(8, NULL, 'Milhail_2', 'Milhail_3', 'Milhail_4', 'Milhail_5', 'Milhail_6'),
	(9, NULL, 'Aixe_2', 'Aixe_3', 'Aixe_4', 'Aixe_5', 'Aixe_6'),
	(10, NULL, 'Oliver_2', 'Oliver_3', 'Oliver_4', 'Oliver_5', 'Oliver_6'),
	(11, NULL, 'Morot_2', 'Morot_3', 'Morot_4', 'Morot_5', 'Morot_6'),
	(12, NULL, 'Arty_2', 'Arty_3', 'Arty_4', 'Arty_5', 'Arty_6');
/*!40000 ALTER TABLE `SkillAffixAlias` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillEffect 結構
CREATE TABLE IF NOT EXISTS `SkillEffect` (
  `SkillEffectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EffectName` varchar(50) DEFAULT NULL COMMENT '效果名稱或標籤',
  `EffectType` smallint(6) NOT NULL DEFAULT 0 COMMENT '效果類型',
  `Formula` text DEFAULT NULL COMMENT '公式',
  PRIMARY KEY (`SkillEffectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='技能效果表';

-- 正在傾印表格  koa_static.SkillEffect 的資料：~47 rows (近似值)
/*!40000 ALTER TABLE `SkillEffect` DISABLE KEYS */;
INSERT INTO `SkillEffect` (`SkillEffectID`, `EffectName`, `EffectType`, `Formula`) VALUES
	(10101, 'int_up_01', 101, '-H*N%'),
	(10102, 'int_up_02', 101, '-H*N%'),
	(10103, 'int_up_03', 101, '-H*N%'),
	(10104, 'int_up_04', 101, '-H*N%'),
	(10105, 'int_up_05', 101, '-H*N%'),
	(10106, 'int_up_06', 101, '-H*N%'),
	(10201, 'Sta_up_01', 102, 'S*N%'),
	(10202, 'Sta_up_02', 102, 'S*N%'),
	(10203, 'Sta_up_03', 102, 'S*N%'),
	(10204, 'Sta_up_04', 102, 'S*N%'),
	(10205, 'Sta_up_05', 102, 'S*N%'),
	(10206, 'Sta_up_06', 102, 'S*N%'),
	(20100, 'Spd_up_00', 111, 'SPD*N%'),
	(20101, 'Spd_up_01', 111, 'SPD*N%'),
	(20102, 'Spd_up_02', 111, 'SPD*N%'),
	(20103, 'Spd_up_03', 111, 'SPD*N%'),
	(20104, 'Spd_up_04', 111, 'SPD*N%'),
	(20105, 'Spd_up_05', 111, 'SPD*N%'),
	(20106, 'Spd_up_06', 111, 'SPD*N%'),
	(20200, 'Pow_up_00', 112, 'POW*N%'),
	(20201, 'Pow_up_01', 112, 'POW*N%'),
	(20202, 'Pow_up_02', 112, 'POW*N%'),
	(20203, 'Pow_up_03', 112, 'POW*N%'),
	(20204, 'Pow_up_04', 112, 'POW*N%'),
	(20205, 'Pow_up_05', 112, 'POW*N%'),
	(20206, 'Pow_up_06', 112, 'POW*N%'),
	(20300, 'Fig_up_00', 113, 'FIG*N%'),
	(20301, 'Fig_up_01', 113, 'FIG*N%'),
	(20302, 'Fig_up_02', 113, 'FIG*N%'),
	(20303, 'Fig_up_03', 113, 'FIG*N%'),
	(20304, 'Fig_up_04', 113, 'FIG*N%'),
	(20305, 'Fig_up_05', 113, 'FIG*N%'),
	(20306, 'Fig_up_06', 113, 'FIG*N%'),
	(20400, 'int_up_00', 114, 'INT*N%'),
	(20401, 'int_up_01', 114, 'INT*N%'),
	(20402, 'int_up_02', 114, 'INT*N%'),
	(20403, 'int_up_03', 114, 'INT*N%'),
	(20404, 'int_up_04', 114, 'INT*N%'),
	(20405, 'int_up_05', 114, 'INT*N%'),
	(20406, 'int_up_06', 114, 'INT*N%'),
	(20500, 'Sta_up_00', 115, 'STA*N%'),
	(20501, 'Sta_up_01', 115, 'STA*N%'),
	(20502, 'Sta_up_02', 115, 'STA*N%'),
	(20503, 'Sta_up_03', 115, 'STA*N%'),
	(20504, 'Sta_up_04', 115, 'STA*N%'),
	(20505, 'Sta_up_05', 115, 'STA*N%'),
	(20506, 'Sta_up_06', 115, 'STA*N%');
/*!40000 ALTER TABLE `SkillEffect` ENABLE KEYS */;

-- 傾印  資料表 koa_static.SkillInfo 結構
CREATE TABLE IF NOT EXISTS `SkillInfo` (
  `SkillID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AliasCode` varchar(50) DEFAULT NULL COMMENT '識別碼(企劃用)',
  `SkillName` varchar(50) DEFAULT NULL COMMENT '技能名稱標籤',
  `Icon` varchar(50) NOT NULL DEFAULT '0' COMMENT '技能Icon編號',
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

-- 正在傾印表格  koa_static.SkillInfo 的資料：~156 rows (近似值)
/*!40000 ALTER TABLE `SkillInfo` DISABLE KEYS */;
INSERT INTO `SkillInfo` (`SkillID`, `AliasCode`, `SkillName`, `Icon`, `Description`, `Energy`, `Effect`, `Cooldown`, `Duration`, `Level1`, `Level2`, `Level3`, `Level4`, `Level5`, `MaxDescription`, `MaxCondition`, `MaxConditionValue`, `MaxEffect`) VALUES
	(1, 'Lion061', '21063', 'Skill_Icon_0008', '22204', '0,0,2,1', '20403', 200, -1, 60, 120, 180, 240, 300, '22001', 41, 0, '11013'),
	(2, 'Lion062', '21064', 'Skill_Icon_0010', '22205', '0,0,0,2', '20502', 200, -1, 40, 80, 120, 160, 200, '22001', 51, 0, '11012'),
	(3, 'Lion063', '21065', 'Skill_Icon_0004', '22202', '4,0,0,0', '20204', 200, -1, 80, 160, 240, 320, 400, '22201', 1, 2, '21010'),
	(4, 'Lion064', '21066', 'Skill_Icon_0002', '22206', '0,1,1,0', '20102', 200, -1, 40, 80, 120, 160, 200, '22244', 21, 0, '4'),
	(5, 'Lion065', '21067', 'Skill_Icon_0006', '22203', '0,0,0,3', '20303', 200, -1, 60, 120, 180, 240, 300, '23095', 31, 0, '900003'),
	(6, 'Lion066', '21068', 'Skill_Icon_0004', '22202', '4,0,0,0', '20204', 200, -1, 80, 160, 240, 320, 400, '22201', 4, 1, '21010'),
	(7, 'Lion071', '21069', 'Skill_Icon_0007', '22204', '3,0,2,0', '20405', 200, 1220, 500, 1000, 1500, 2000, 2500, '22201', 71, 0, '21012'),
	(8, 'Lion072', '21070', 'Skill_Icon_0009', '22205', '0,0,0,3', '20503', 200, 720, 300, 600, 900, 1200, 1500, '22001', 33, 0, '11010'),
	(9, 'Lion073', '21071', 'Skill_Icon_0003', '22202', '2,0,2,0', '20204', 200, 960, 400, 800, 1200, 1600, 2000, '22248', 4, 1, '2'),
	(10, 'Lion074', '21072', 'Skill_Icon_0001', '22206', '2,2,0,0', '20104', 200, 960, 400, 800, 1200, 1600, 2000, '22201', 12, 0, '21010'),
	(11, 'Lion075', '21073', 'Skill_Icon_0005', '22203', '0,3,0,2', '20305', 200, 1220, 500, 1000, 1500, 2000, 2500, '23191', 42, 0, '900001'),
	(12, 'Lion076', '21074', 'Skill_Icon_0003', '22202', '1,0,3,0', '20204', 200, 720, 300, 600, 900, 1200, 1500, '22250', 51, 0, '610016'),
	(13, 'Lion081', '21075', 'Skill_Icon_0007', '22204', '0,0,3,0', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22204', 23, 0, '141020'),
	(14, 'Lion082', '21076', 'Skill_Icon_0009', '22205', '0,0,1,1', '20502', 200, 500, 200, 400, 600, 800, 1000, '22206', 62, 0, '111026'),
	(15, 'Lion083', '21077', 'Skill_Icon_0003', '22202', '2,0,0,2', '20204', 200, 960, 400, 800, 1200, 1600, 2000, '22203', 33, 0, '131020'),
	(16, 'Lion084', '21078', 'Skill_Icon_0001', '22206', '0,2,2,0', '20104', 200, 960, 400, 800, 1200, 1600, 2000, '22214', 51, 0, '420012'),
	(17, 'Lion085', '21079', 'Skill_Icon_0005', '22203', '1,0,0,2', '20303', 200, 720, 300, 600, 900, 1200, 1500, '23169', 71, 0, '900002'),
	(18, 'Lion086', '21080', 'Skill_Icon_0003', '22202', '2,0,0,0', '20202', 200, 500, 200, 400, 600, 800, 1000, '22208', 63, 0, '121020'),
	(19, 'Lion101', '21081', 'Skill_Icon_0007', '22204', '2,0,2,0', '20404', 200, 960, 400, 800, 1200, 1600, 2000, '22201', 41, 0, '21013'),
	(20, 'Lion102', '21082', 'Skill_Icon_0009', '22205', '0,0,0,2', '20502', 200, 500, 200, 400, 600, 800, 1000, '22001', 65, 0, '11011'),
	(21, 'Lion103', '21083', 'Skill_Icon_0003', '22202', '2,0,1,0', '20203', 200, 720, 300, 600, 900, 1200, 1500, '22247', 31, 0, '1'),
	(22, 'Lion104', '21084', 'Skill_Icon_0001', '22206', '0,3,0,0', '20103', 200, 720, 300, 600, 900, 1200, 1500, '22240', 21, 0, '7'),
	(23, 'Lion105', '21085', 'Skill_Icon_0005', '22203', '0,2,0,2', '20304', 200, 960, 400, 800, 1200, 1600, 2000, '22241', 51, 0, '12'),
	(24, 'Lion106', '21086', 'Skill_Icon_0001', '22206', '0,3,0,0', '20103', 200, 720, 300, 600, 900, 1200, 1500, '22242', 12, 0, '9'),
	(25, 'Lion091', '21087', 'Skill_Icon_0007', '22204', '0,0,2,1', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22001', 51, 0, '11013'),
	(26, 'Lion092', '21088', 'Skill_Icon_0009', '22205', '0,0,1,2', '20503', 200, 720, 300, 600, 900, 1200, 1500, '22213', 21, 0, '410010'),
	(27, 'Lion093', '21089', 'Skill_Icon_0003', '22202', '2,0,2,0', '20204', 200, 960, 400, 800, 1200, 1600, 2000, '22219', 11, 0, '310010'),
	(28, 'Lion094', '21090', 'Skill_Icon_0001', '22206', '1,1,0,0', '20102', 200, 500, 200, 400, 600, 800, 1000, '22201', 31, 0, '21010'),
	(29, 'Lion095', '21091', 'Skill_Icon_0005', '22203', '0,0,1,1', '20302', 200, 500, 200, 400, 600, 800, 1000, '22222', 41, 0, '510013'),
	(30, 'Lion096', '21092', 'Skill_Icon_0001', '22206', '0,2,0,2', '20104', 200, 960, 400, 800, 1200, 1600, 2000, '22216', 1, 1, '210013'),
	(31, 'Lion111', '21093', 'Skill_Icon_0007', '22204', '0,0,2,0', '20402', 200, 250, 400, 800, 1200, 1600, 2000, '22224', 42, 0, '530013'),
	(32, 'Lion112', '21094', 'Skill_Icon_0009', '22205', '0,0,0,2', '20502', 200, 250, 400, 800, 1200, 1600, 2000, '22201', 71, 0, '21012'),
	(33, 'Lion113', '21095', 'Skill_Icon_0003', '22202', '4,0,0,0', '20204', 200, 480, 800, 1600, 2400, 3200, 4000, '22001', 51, 0, '11012'),
	(34, 'Lion114', '21096', 'Skill_Icon_0001', '22206', '0,3,0,0', '20103', 200, 360, 600, 1200, 1800, 2400, 3000, '22004', 61, 0, '30017'),
	(35, 'Lion115', '21097', 'Skill_Icon_0005', '22203', '0,0,0,3', '20303', 200, 360, 600, 1200, 1800, 2400, 3000, '22201', 33, 0, '21010'),
	(36, 'Lion116', '21098', 'Skill_Icon_0001', '22206', '0,4,0,0', '20104', 200, 480, 800, 1600, 2400, 3200, 4000, '22206', 12, 0, '111020'),
	(37, 'Cat011', '21099', 'Skill_Icon_0003', '22202', '2,0,0,1', '20203', 200, 720, 300, 600, 900, 1200, 1500, '22265', 11, 0, '41001'),
	(38, 'Cat012', '21100', 'Skill_Icon_0001', '22206', '0,2,0,1', '20103', 200, 720, 300, 600, 900, 1200, 1500, '22250', 51, 0, '40001'),
	(39, 'Cat013', '21101', 'Skill_Icon_0007', '22204', '0,0,2,1', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22001', 72, 0, '11012'),
	(40, 'Cat014', '21102', 'Skill_Icon_0005', '22203', '0,0,1,2', '20303', 200, 720, 300, 600, 900, 1200, 1500, '22223', 22, 0, '520010'),
	(41, 'Cat015', '21103', 'Skill_Icon_0009', '22205', '1,0,0,2', '20503', 200, 720, 300, 600, 900, 1200, 1500, '22251', 43, 0, '41002'),
	(42, 'Cat016', '21104', 'Skill_Icon_0007', '22204', '0,0,3,0', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22216', 5, 1, '210015'),
	(43, 'Cat021', '21105', 'Skill_Icon_0003', '22202', '2,1,0,0', '20203', 200, 1440, 200, 400, 600, 800, 1000, '23002', 4, 1, '900004'),
	(44, 'Cat022', '21106', 'Skill_Icon_0001', '22206', '1,2,0,0', '20103', 200, 1440, 200, 400, 600, 800, 1000, '22201', 31, 0, '21010'),
	(45, 'Cat023', '21107', 'Skill_Icon_0007', '22204', '0,1,2,0', '20403', 200, 1440, 200, 400, 600, 800, 1000, '23118', 4, 1, '900005'),
	(46, 'Cat024', '21108', 'Skill_Icon_0005', '22203', '1,0,0,2', '20303', 200, 1440, 200, 400, 600, 800, 1000, '22204', 21, 0, '141020'),
	(47, 'Cat025', '21109', 'Skill_Icon_0009', '22205', '0,0,0,3', '20503', 200, 1440, 200, 400, 600, 800, 1000, '22001', 51, 0, '11012'),
	(48, 'Cat026', '21110', 'Skill_Icon_0007', '22204', '0,1,2,0', '20403', 200, 1440, 400, 800, 1200, 1600, 2000, '22257', 41, 0, '20,21,22,23'),
	(49, 'Cat031', '21111', 'Skill_Icon_0003', '22202', '3,0,0,0', '20203', 200, 720, 300, 600, 900, 1200, 1500, '22249', 51, 0, '3'),
	(50, 'Cat032', '21112', 'Skill_Icon_0001', '22206', '0,3,0,0', '20103', 200, 720, 300, 600, 900, 1200, 1500, '22240', 43, 0, '7'),
	(51, 'Cat033', '21113', 'Skill_Icon_0007', '22204', '0,0,3,0', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22201', 43, 0, '21013'),
	(52, 'Cat034', '21114', 'Skill_Icon_0005', '22203', '0,2,0,1', '20303', 200, 720, 300, 600, 900, 1200, 1500, '22243', 22, 0, '10'),
	(53, 'Cat035', '21115', 'Skill_Icon_0009', '22205', '0,0,0,3', '20503', 200, 720, 300, 600, 900, 1200, 1500, '22001', 72, 0, '11012'),
	(54, 'Cat036', '21116', 'Skill_Icon_0007', '22204', '0,1,2,0', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22242', 32, 0, '9'),
	(55, 'Cat041', '21117', 'Skill_Icon_0003', '22202', '3,0,0,0', '20203', 200, 360, 600, 1200, 1800, 2400, 3000, '22201', 62, 0, '21013'),
	(56, 'Cat042', '21118', 'Skill_Icon_0001', '22206', '0,3,0,0', '20103', 200, 360, 600, 1200, 1800, 2400, 3000, '23023', 12, 0, '900006'),
	(57, 'Cat043', '21119', 'Skill_Icon_0007', '22204', '0,0,3,0', '20403', 200, 360, 600, 1200, 1800, 2400, 3000, '22224', 51, 0, '530012'),
	(58, 'Cat044', '21120', 'Skill_Icon_0005', '22203', '0,0,0,3', '20303', 200, 360, 600, 1200, 1800, 2400, 3000, '22001', 71, 0, '11012'),
	(59, 'Cat045', '21121', 'Skill_Icon_0009', '22205', '0,0,0,3', '20503', 200, 360, 600, 1200, 1800, 2400, 3000, '22205', 33, 0, '151010'),
	(60, 'Cat046', '21122', 'Skill_Icon_0007', '22204', '0,0,3,0', '20403', 200, 360, 600, 1200, 1800, 2400, 3000, '22214', 23, 0, '420010'),
	(61, 'Cat051', '21123', 'Skill_Icon_0003', '22202', '2,0,1,0', '20203', 200, 720, 300, 600, 900, 1200, 1500, '22219', 31, 0, '310010'),
	(62, 'Cat052', '21124', 'Skill_Icon_0001', '22206', '1,2,0,0', '20103', 200, 720, 300, 600, 900, 1200, 1500, '22201', 61, 0, '21012'),
	(63, 'Cat053', '21125', 'Skill_Icon_0007', '22204', '1,0,2,0', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22201', 21, 0, '21010'),
	(64, 'Cat054', '21126', 'Skill_Icon_0005', '22203', '0,0,1,2', '20303', 200, 720, 300, 600, 900, 1200, 1500, '22247', 51, 0, '1'),
	(65, 'Cat055', '21127', 'Skill_Icon_0009', '22205', '1,0,0,2', '20503', 200, 720, 300, 600, 900, 1200, 1500, '22201', 41, 0, '21013'),
	(66, 'Cat056', '21128', 'Skill_Icon_0007', '22204', '0,0,2,1', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22001', 1, 1, '11012'),
	(67, 'Cat061', '21129', 'Skill_Icon_0003', '22202', '3,0,0,0', '20203', 200, 720, 300, 600, 900, 1200, 1500, '22212', 62, 0, '20012'),
	(68, 'Cat062', '21130', 'Skill_Icon_0001', '22206', '0,2,1,0', '20103', 200, 720, 300, 600, 900, 1200, 1500, '22214', 12, 0, '420010'),
	(69, 'Cat063', '21131', 'Skill_Icon_0007', '22204', '0,0,3,0', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22248', 23, 0, '2'),
	(70, 'Cat064', '21132', 'Skill_Icon_0005', '22203', '0,0,0,3', '20303', 200, 720, 300, 600, 900, 1200, 1500, '20002', 71, 0, '10012'),
	(71, 'Cat065', '21133', 'Skill_Icon_0009', '22205', '0,0,1,2', '20503', 200, 720, 300, 600, 900, 1200, 1500, '22220', 33, 0, '320010'),
	(72, 'Cat066', '21134', 'Skill_Icon_0007', '22204', '0,1,2,0', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22207', 51, 0, '110024'),
	(73, 'Fox011', '21135', 'Skill_Icon_0001', '22206', '0,2,2,0', '20104', 200, 960, 400, 800, 1200, 1600, 2000, '22215', 12, 0, '430010'),
	(74, 'Fox012', '21136', 'Skill_Icon_0005', '22203', '0,0,1,1', '20302', 200, 500, 200, 400, 600, 800, 1000, '22223', 51, 0, '520012'),
	(75, 'Fox013', '21137', 'Skill_Icon_0009', '22205', '0,0,1,2', '20503', 200, 960, 400, 800, 1200, 1600, 2000, '22249', 11, 0, '3'),
	(76, 'Fox014', '21138', 'Skill_Icon_0003', '22202', '3,0,0,0', '20203', 200, 720, 300, 600, 900, 1200, 1500, '22201', 72, 0, '21012'),
	(77, 'Fox015', '21139', 'Skill_Icon_0007', '22204', '0,0,4,0', '20404', 200, 720, 300, 600, 900, 1200, 1500, '22216', 43, 0, '210017'),
	(78, 'Fox016', '21140', 'Skill_Icon_0003', '22202', '1,0,1,0', '20202', 200, 500, 200, 400, 600, 800, 1000, '22269', 32, 0, '24,20'),
	(79, 'Fox021', '21141', 'Skill_Icon_0001', '22206', '0,3,0,0', '20103', 200, 720, 300, 600, 900, 1200, 1500, '23213', 43, 0, '900007'),
	(80, 'Fox022', '21142', 'Skill_Icon_0005', '22203', '1,0,0,1', '20302', 200, 500, 200, 400, 600, 800, 1000, '22202', 22, 0, '121020'),
	(81, 'Fox023', '21143', 'Skill_Icon_0009', '22205', '0,0,0,1', '20501', 200, 300, 100, 200, 300, 400, 500, '22001', 1, 2, '11013'),
	(82, 'Fox024', '21144', 'Skill_Icon_0003', '22202', '3,0,0,0', '20203', 200, 720, 300, 600, 900, 1200, 1500, '22201', 32, 0, '21010'),
	(83, 'Fox025', '21145', 'Skill_Icon_0007', '22204', '0,0,1,0', '20401', 200, 300, 100, 200, 300, 400, 500, '22216', 72, 0, '210012'),
	(84, 'Fox026', '21146', 'Skill_Icon_0001', '22206', '0,2,0,0', '20102', 200, 500, 200, 400, 600, 800, 1000, '22271', 4, 1, '25,21'),
	(85, 'Fox031', '21147', 'Skill_Icon_0001', '22206', '0,4,0,0', '20104', 200, 960, 400, 800, 1200, 1600, 2000, '22206', 72, 0, '111024'),
	(86, 'Fox032', '21148', 'Skill_Icon_0005', '22203', '0,1,0,2', '20303', 200, 720, 200, 400, 600, 800, 1000, '22207', 43, 0, '110039'),
	(87, 'Fox033', '21149', 'Skill_Icon_0009', '22205', '0,1,0,1', '20502', 200, 500, 300, 600, 900, 1200, 1500, '22267', 22, 0, '20,21,22,23'),
	(88, 'Fox034', '21150', 'Skill_Icon_0003', '22202', '2,2,0,0', '20204', 200, 960, 580, 1160, 1739, 2320, 2900, '23214', 11, 0, '900008'),
	(89, 'Fox035', '21151', 'Skill_Icon_0007', '22204', '0,0,2,1', '20403', 200, 720, 300, 600, 900, 1200, 1500, '22001', 32, 0, '11010'),
	(90, 'Fox036', '21152', 'Skill_Icon_0007', '22204', '0,1,1,0', '20402', 200, 500, 200, 400, 600, 800, 1000, '22275', 51, 0, '26,22'),
	(91, 'Aimee_2', '21188', 'Skill_Icon_0003', '22202', '1,0,1,0', '20212', 200, 500, 200, 400, 600, 800, 1000, '22204', 61, 0, '141030'),
	(92, 'Aimee_3', '21189', 'Skill_Icon_0003', '22202', '2,0,1,0', '20213', 200, 720, 300, 600, 900, 1200, 1500, '22204', 61, 0, '141030'),
	(93, 'Aimee_4', '21190', 'Skill_Icon_0003', '22202', '2,0,2,0', '20214', 200, 960, 400, 800, 1200, 1600, 2000, '22204', 61, 0, '141030'),
	(94, 'Aimee_5', '21191', 'Skill_Icon_0003', '22202', '3,0,2,0', '20215', 200, 1220, 500, 1000, 1500, 2000, 2500, '22204', 61, 0, '141030'),
	(95, 'Aimee_6', '21192', 'Skill_Icon_0003', '22202', '3,0,3,0', '20216', 200, 1500, 600, 1200, 1800, 2400, 3000, '22204', 61, 0, '141030'),
	(96, 'Ceto_2', '21173', 'Skill_Icon_0009', '22205', '1,0,0,1', '20512', 200, 250, 400, 800, 1200, 1600, 2000, '22201', 65, 0, '21011'),
	(97, 'Ceto_3', '21174', 'Skill_Icon_0009', '22205', '1,0,0,2', '20513', 200, 360, 600, 1200, 1800, 2400, 3000, '22201', 65, 0, '21011'),
	(98, 'Ceto_4', '21175', 'Skill_Icon_0009', '22205', '2,0,0,2', '20514', 200, 480, 800, 1600, 2400, 3200, 4000, '22201', 65, 0, '21011'),
	(99, 'Ceto_5', '21176', 'Skill_Icon_0009', '22205', '2,0,0,3', '20515', 200, 610, 1000, 2000, 3000, 4000, 5000, '22201', 65, 0, '21011'),
	(100, 'Ceto_6', '21177', 'Skill_Icon_0009', '22205', '3,0,0,3', '20516', 200, 750, 1200, 2400, 3600, 4800, 6000, '22201', 65, 0, '21011'),
	(101, 'Prudence_2', '21178', 'Skill_Icon_0005', '22203', '1,0,0,1', '20312', 200, 500, 200, 400, 600, 800, 1000, '22251', 66, 0, '41003'),
	(102, 'Prudence_3', '21179', 'Skill_Icon_0005', '22203', '1,0,0,2', '20313', 200, 720, 300, 600, 900, 1200, 1500, '22251', 66, 0, '41003'),
	(103, 'Prudence_4', '21180', 'Skill_Icon_0005', '22203', '2,0,0,2', '20314', 200, 960, 400, 800, 1200, 1600, 2000, '22251', 66, 0, '41003'),
	(104, 'Prudence_5', '21181', 'Skill_Icon_0005', '22203', '2,0,0,3', '20315', 200, 1220, 500, 1000, 1500, 2000, 2500, '22251', 66, 0, '41003'),
	(105, 'Prudence_6', '21182', 'Skill_Icon_0005', '22203', '3,0,0,3', '20316', 200, 1500, 600, 1200, 1800, 2400, 3000, '22251', 66, 0, '41003'),
	(106, 'Olé_2', '21183', 'Skill_Icon_0003', '22202', '1,1,0,0', '20212', 200, 1000, 130, 260, 400, 530, 660, '23214', 11, 0, '900009'),
	(107, 'Olé_3', '21184', 'Skill_Icon_0003', '22202', '2,1,0,0', '20213', 200, 1440, 200, 400, 600, 800, 1000, '23214', 11, 0, '900009'),
	(108, 'Olé_4', '21185', 'Skill_Icon_0003', '22202', '2,2,0,0', '20214', 200, 1920, 270, 530, 800, 1060, 1330, '23214', 11, 0, '900009'),
	(109, 'Olé_5', '21186', 'Skill_Icon_0003', '22202', '3,2,0,0', '20215', 200, 2440, 330, 660, 1000, 1330, 1660, '23214', 11, 0, '900009'),
	(110, 'Olé_6', '21187', 'Skill_Icon_0003', '22202', '3,3,0,0', '20216', 200, 3000, 400, 800, 1200, 1600, 2000, '23214', 11, 0, '900009'),
	(111, 'Oz_2', '21158', 'Skill_Icon_0009', '22205', '3,0,0,3', '20512', 200, 1500, 200, 400, 600, 800, 1000, '22201', 65, 0, '21011'),
	(112, 'Oz_3', '21159', 'Skill_Icon_0009', '22205', '3,0,0,3', '20513', 200, 1800, 300, 600, 900, 1200, 1500, '22201', 65, 0, '21011'),
	(113, 'Oz_4', '21160', 'Skill_Icon_0009', '22205', '3,0,0,3', '20514', 200, 2120, 400, 800, 1200, 1600, 2000, '22201', 65, 0, '21011'),
	(114, 'Oz_5', '21161', 'Skill_Icon_0009', '22205', '3,0,0,3', '20515', 200, 2460, 500, 1000, 1500, 2000, 2500, '22201', 65, 0, '21011'),
	(115, 'Oz_6', '21162', 'Skill_Icon_0009', '22205', '3,0,0,3', '20516', 200, 2820, 600, 1200, 1800, 2400, 3000, '22201', 65, 0, '21011'),
	(116, 'Al_2', '21198', 'Skill_Icon_0027', '22201', '2,0,0,0', '10212', 200, 500, 100, 200, 300, 400, 500, '22201', 4, 1, '21020'),
	(117, 'Al_3', '21199', 'Skill_Icon_0027', '22201', '3,0,0,0', '10213', 200, 720, 150, 300, 450, 600, 750, '22201', 4, 1, '21020'),
	(118, 'Al_4', '21200', 'Skill_Icon_0027', '22201', '4,0,0,0', '10214', 200, 960, 200, 400, 600, 800, 1000, '22201', 4, 1, '21020'),
	(119, 'Al_5', '21201', 'Skill_Icon_0027', '22201', '5,0,0,0', '10215', 200, 1220, 250, 500, 750, 1000, 1250, '22201', 4, 1, '21020'),
	(120, 'Al_6', '21202', 'Skill_Icon_0027', '22201', '6,0,0,0', '10216', 200, 1500, 300, 600, 900, 1200, 1500, '22201', 4, 1, '21020'),
	(121, 'Gottloh_2', '21153', 'Skill_Icon_0008', '22204', '1,1,0,0', '20412', 200, -1, 40, 80, 120, 160, 200, '22206', 12, 0, '111020'),
	(122, 'Gottloh_3', '21154', 'Skill_Icon_0008', '22204', '1,2,0,0', '20413', 200, -1, 60, 120, 180, 240, 300, '22206', 12, 0, '111020'),
	(123, 'Gottloh_4', '21155', 'Skill_Icon_0008', '22204', '2,2,0,0', '20414', 200, -1, 80, 160, 240, 320, 400, '22206', 12, 0, '111020'),
	(124, 'Gottloh_5', '21156', 'Skill_Icon_0008', '22204', '2,3,0,0', '20415', 200, -1, 100, 200, 300, 400, 500, '22206', 12, 0, '111020'),
	(125, 'Gottloh_6', '21157', 'Skill_Icon_0008', '22204', '3,3,0,0', '20416', 200, -1, 120, 240, 360, 480, 600, '22206', 12, 0, '111020'),
	(126, 'Milhail_2', '21208', 'Skill_Icon_0001', '22206', '0,1,0,1', '20112', 200, 500, 200, 400, 600, 800, 1000, '22261', 5, 1, '41004'),
	(127, 'Milhail_3', '21209', 'Skill_Icon_0001', '22206', '0,2,0,1', '20113', 200, 720, 300, 600, 900, 1200, 1500, '22261', 5, 1, '41005'),
	(128, 'Milhail_4', '21210', 'Skill_Icon_0001', '22206', '0,2,0,2', '20114', 200, 960, 400, 800, 1200, 1600, 2000, '22261', 5, 1, '41006'),
	(129, 'Milhail_5', '21211', 'Skill_Icon_0001', '22206', '0,3,0,2', '20115', 200, 1220, 500, 1000, 1500, 2000, 2500, '22261', 5, 1, '41007'),
	(130, 'Milhail_6', '21212', 'Skill_Icon_0001', '22206', '0,3,0,3', '20116', 200, 1500, 600, 1200, 1800, 2400, 3000, '22261', 5, 1, '41008'),
	(131, 'Aixe_2', '21193', 'Skill_Icon_0001', '22206', '1,1,0,0', '20112', 200, 500, 200, 400, 600, 800, 1000, '22202', 62, 0, '121039'),
	(132, 'Aixe_3', '21194', 'Skill_Icon_0001', '22206', '1,1,0,1', '20113', 200, 720, 300, 600, 900, 1200, 1500, '22202', 62, 0, '121039'),
	(133, 'Aixe_4', '21195', 'Skill_Icon_0001', '22206', '1,1,1,1', '20114', 200, 960, 400, 800, 1200, 1600, 2000, '22202', 62, 0, '121039'),
	(134, 'Aixe_5', '21196', 'Skill_Icon_0001', '22206', '1,1,2,1', '20115', 200, 1220, 500, 1000, 1500, 2000, 2500, '22202', 62, 0, '121039'),
	(135, 'Aixe_6', '21197', 'Skill_Icon_0001', '22206', '1,1,3,1', '20116', 200, 1500, 600, 1200, 1800, 2400, 3000, '22202', 62, 0, '121039'),
	(136, 'Oliver_2', '21168', 'Skill_Icon_0005', '22203', '0,1,0,1', '20312', 200, 500, 200, 400, 600, 800, 1000, '22242', 21, 0, '9'),
	(137, 'Oliver_3', '21169', 'Skill_Icon_0005', '22203', '0,1,0,2', '20313', 200, 720, 300, 600, 900, 1200, 1500, '22243', 21, 0, '10'),
	(138, 'Oliver_4', '21170', 'Skill_Icon_0005', '22203', '0,2,0,2', '20314', 200, 960, 400, 800, 1200, 1600, 2000, '22240', 21, 0, '18'),
	(139, 'Oliver_5', '21171', 'Skill_Icon_0005', '22203', '0,2,0,3', '20315', 200, 1220, 500, 1000, 1500, 2000, 2500, '22241', 21, 0, '19'),
	(140, 'Oliver_6', '21172', 'Skill_Icon_0005', '22203', '0,3,0,3', '20316', 200, 1500, 600, 1200, 1800, 2400, 3000, '22239', 21, 0, '11'),
	(141, 'Morot_2', '21203', 'Skill_Icon_0007', '22204', '0,0,1,1', '20412', 200, 500, 200, 400, 600, 800, 1000, '22001', 66, 0, '11011'),
	(142, 'Morot_3', '21204', 'Skill_Icon_0007', '22204', '0,0,1,1', '20413', 200, 720, 200, 400, 600, 800, 1000, '22001', 66, 0, '11011'),
	(143, 'Morot_4', '21205', 'Skill_Icon_0007', '22204', '0,0,1,1', '20414', 200, 960, 200, 400, 600, 800, 1000, '22001', 66, 0, '11011'),
	(144, 'Morot_5', '21206', 'Skill_Icon_0007', '22204', '0,0,1,1', '20415', 200, 1220, 200, 400, 600, 800, 1000, '22001', 66, 0, '11011'),
	(145, 'Morot_6', '21207', 'Skill_Icon_0007', '22204', '0,0,1,1', '20416', 200, 1500, 200, 400, 600, 800, 1000, '22001', 66, 0, '11011'),
	(146, 'Arty_2', '21163', 'Skill_Icon_0029', '22001', '0,0,0,2', '10112', 200, 500, 100, 200, 300, 400, 500, '22205', 64, 0, '151040'),
	(147, 'Arty_3', '21164', 'Skill_Icon_0029', '22001', '0,0,0,3', '10113', 200, 720, 150, 300, 450, 600, 750, '22205', 64, 0, '151040'),
	(148, 'Arty_4', '21165', 'Skill_Icon_0029', '22001', '0,0,0,4', '10114', 200, 960, 200, 400, 600, 800, 1000, '22205', 64, 0, '151040'),
	(149, 'Arty_5', '21166', 'Skill_Icon_0029', '22001', '0,0,0,5', '10115', 200, 1220, 250, 500, 750, 1000, 1250, '22205', 64, 0, '151040'),
	(150, 'Arty_6', '21167', 'Skill_Icon_0029', '22001', '0,0,0,6', '10116', 200, 1500, 300, 600, 900, 1200, 1500, '22205', 64, 0, '151040'),
	(151, 'Genesis_1', '21213', 'Skill_Icon_0029', '22001', '1,1,1,0', '10103', 200, 720, 150, 300, 450, 600, 750, '22255', 51, 0, '41009'),
	(152, 'Genesis_2', '21214', 'Skill_Icon_0009', '22205', '0,1,1,1', '20503', 200, 720, 300, 600, 900, 1200, 1500, '22266', 52, 0, '41010'),
	(153, 'Genesis_3', '21215', 'Skill_Icon_0027', '22201', '1,0,1,1', '10203', 200, 720, 150, 300, 450, 600, 750, '22257', 53, 0, '41011'),
	(154, 'Pur_Lion', '21216', 'Skill_Icon_0027', '22201', '2,0,0,2', '10204', 200, 960, 200, 400, 600, 800, 1000, '22004', 1, 1, '30026'),
	(155, 'Pur_Cat', '21217', 'Skill_Icon_0001', '22206', '0,3,0,0', '20103', 200, 720, 300, 600, 900, 1200, 1500, '22201', 5, 1, '21015'),
	(156, 'Pur_Fox', '21218', 'Skill_Icon_0005', '22203', '0,1,0,1', '20302', 200, 500, 300, 600, 900, 1200, 1500, '22270', 11, 0, '27,20');
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

-- 正在傾印表格  koa_static.SkillMaxEffect 的資料：~85 rows (近似值)
/*!40000 ALTER TABLE `SkillMaxEffect` DISABLE KEYS */;
INSERT INTO `SkillMaxEffect` (`MaxEffectID`, `EffectName`, `EffectType`, `Target`, `Formula`) VALUES
	(1, 'change_c01', 504, 0, NULL),
	(2, 'change_c02', 505, 0, NULL),
	(3, 'change_c03', 506, 0, NULL),
	(4, 'change_w01', 501, 0, NULL),
	(5, 'change_w02', 503, 0, NULL),
	(6, 'change_w03', 502, 0, NULL),
	(7, 'Add_EN_R01', 212, 0, '1'),
	(8, 'Add_EN_Y01', 213, 0, '1'),
	(9, 'Add_EN_B01', 214, 0, '1'),
	(10, 'Add_EN_G01', 215, 0, '1'),
	(11, 'Add_EN_A01', 211, 0, '1'),
	(12, 'Add_EN_Y02', 213, 0, '2'),
	(18, 'Add_EN_R02', 212, 0, '2'),
	(19, 'Add_EN_Y03', 213, 0, '3'),
	(20, 'Sub_EN_Red', 212, 0, '-Red'),
	(21, 'Sub_EN_Yellow', 213, 0, '-Yellow'),
	(22, 'Sub_EN_Blue', 214, 0, '-Blue'),
	(23, 'Sub_EN_Green', 215, 0, '-Green'),
	(24, 'Add_EN_RedBlue', 214, 0, 'Red'),
	(25, 'Add_EN_YelloRed', 212, 0, 'Yellow'),
	(26, 'Add_EN_BlueYello', 213, 0, 'Blue'),
	(27, 'Add_EN_RedGreen', 215, 0, 'Red'),
	(10012, 'H_down_12', 101, 0, '-H*1.2*N'),
	(11010, 'H_down_10', 101, 0, '-H*1%*N'),
	(11011, 'H_down_11', 101, 0, '-H*1.1%*N'),
	(11012, 'H_down_12', 101, 0, '-H*1.2%*N'),
	(11013, 'H_down_13', 101, 0, '-H*1.3%*N'),
	(20012, 'S_ADD_12', 102, 0, 'S*1.2*N*0.1'),
	(21010, 'S_UP_10', 102, 0, 'S*1%*N'),
	(21011, 'S_UP_11', 102, 0, 'S*1.1%*N'),
	(21012, 'S_UP_12', 102, 0, 'S*1.2%*N'),
	(21013, 'S_UP_13', 102, 0, 'S*1.3%*N'),
	(21015, 'S_UP_15', 102, 0, 'S*1.5%*N'),
	(21020, 'S_UP_20', 102, 0, 'S*2%*N'),
	(30017, 'HP_ADD_17', 201, 0, '1.7*N'),
	(30026, 'HP_ADD_26', 201, 0, '2.6*N'),
	(40001, 'Unity_HP_01', 201, 0, '0.4*N*Featuer'),
	(41001, 'Unity_H_01', 101, 0, '-H*0.33*N*Featuer'),
	(41002, 'Unity_S_01', 102, 0, 'S*0.43*N*Featuer'),
	(41003, 'Unity_S_02', 102, 0, 'S*0.3*N*Featuer'),
	(41004, 'Alone_Fig_01', 113, 0, '36%-(Featuer*0.4%*N)'),
	(41005, 'Alone_Fig_02', 113, 0, '45%-(Featuer*0.33%*N)'),
	(41006, 'Alone_Fig_03', 113, 0, '54%-(Featuer*0.3%*N)'),
	(41007, 'Alone_Fig_04', 113, 0, '63%-(Featuer*0.28%*N)'),
	(41008, 'Alone_Fig_05', 113, 0, '72%-(Featuer*0.26%*N)'),
	(41009, 'Unity_S_03', 102, 0, 'S*1.06%*N*Genesis'),
	(41010, 'Unity_S_04', 102, 5, '-S*0.26%*N*Genesis'),
	(41011, 'Unity_S_05', 102, 0, '100%-(Genesis*1.06%*N)'),
	(110024, 'Spd_ADD_24', 111, 0, '2.4*N'),
	(110039, 'Spd_ADD_39', 111, 0, '3.9*N'),
	(111020, 'Spd_UP_20', 111, 0, 'SPD*2%*N'),
	(111024, 'Spd_UP_24', 111, 0, 'SPD*2.4%*N'),
	(111026, 'Spd_UP_26', 111, 0, 'SPD*2.6%*N'),
	(121020, 'Pow_UP_20', 112, 0, 'POW*2%*N'),
	(121039, 'Pow_UP_39', 112, 0, 'POW*3.9%*N'),
	(131020, 'Fig_UP_20', 113, 0, 'FIG*2%*N'),
	(141020, 'Int_UP_20', 114, 0, 'INT*2%*N'),
	(141030, 'Int_UP_30', 114, 0, 'INT*3%*N'),
	(151010, 'Sta_UP_10', 115, 0, 'STA*1%*N'),
	(151040, 'Sta_UP_40', 115, 0, 'STA*4%*N'),
	(210012, 'Dune_ADD_12', 121, 0, '1.2*N'),
	(210013, 'Dune_ADD_13', 121, 0, '1.3*N'),
	(210015, 'Dune_ADD_15', 121, 0, '1.5*N'),
	(210017, 'Dune_ADD_17', 121, 0, '1.7*N'),
	(310010, 'Downwind_ADD_10', 131, 0, '1*N'),
	(320010, 'Crosswind_ADD_10', 132, 0, '1*N'),
	(410010, 'Sunny_ADD_10', 141, 0, '1*N'),
	(420010, 'Aurora_ADD_10', 142, 0, '1*N'),
	(420012, 'Aurora_ADD_12', 142, 0, '1.2*N'),
	(430010, 'Dust_ADD_10', 143, 0, '1*N'),
	(510013, 'Ground_ADD_13', 151, 0, '1.3*N'),
	(520010, 'Uphill_ADD_10', 152, 0, '1*N'),
	(520012, 'Uphill_ADD_12', 152, 0, '1.2*N'),
	(530012, 'Downhill_ADD_12', 153, 0, '1.2*N'),
	(530013, 'Downhill_ADD_13', 153, 0, '1.3*N'),
	(610016, 'Sun_ADD_16', 161, 0, '1.6*N'),
	(900001, 'Atk_S_10_5', 102, 5, '-S*0.1*N'),
	(900002, 'Atk_Int_12_4', 114, 4, 'INT*(0-1.2%)*N'),
	(900003, 'Atk_HP_10_3', 201, 3, '-1*N'),
	(900004, 'Atk_HP_15_1', 201, 1, '-1.5*N'),
	(900005, 'Atk_S_10_3', 102, 3, '-S*0.1*N'),
	(900006, 'Atk_H_10_1', 201, 1, 'H*1%*N'),
	(900007, 'Atk_Spd_13_5', 111, 5, 'SPD*(0-1.3)*N'),
	(900008, 'Atk_Pow_10_5', 112, 5, 'POW*(0-1%)*N'),
	(900009, 'Atk_Pow_11_5', 112, 5, 'POW*(0-1.1%)*N');
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

-- 正在傾印表格  koa_static.SkillPart 的資料：~90 rows (近似值)
/*!40000 ALTER TABLE `SkillPart` DISABLE KEYS */;
INSERT INTO `SkillPart` (`SkillPartID`, `PartCode`, `PartType`, `AliasCode1`, `AliasCode2`, `AliasCode3`, `SkillAffixID`) VALUES
	(1, '110106', 1, 'Lion061', NULL, NULL, 7),
	(2, '110106', 2, 'Lion062', NULL, NULL, 7),
	(3, '110106', 3, 'Lion063', NULL, NULL, 7),
	(4, '110106', 4, 'Lion064', NULL, NULL, 7),
	(5, '110106', 5, 'Lion065', NULL, NULL, 7),
	(6, '110106', 6, 'Lion066', NULL, NULL, 7),
	(7, '110207', 1, 'Lion071', NULL, NULL, 5),
	(8, '110207', 2, 'Lion072', NULL, NULL, 5),
	(9, '110207', 3, 'Lion073', NULL, NULL, 5),
	(10, '110207', 4, 'Lion074', NULL, NULL, 5),
	(11, '110207', 5, 'Lion075', NULL, NULL, 5),
	(12, '110207', 6, 'Lion076', NULL, NULL, 5),
	(13, '110208', 1, 'Lion081', NULL, NULL, 9),
	(14, '110208', 2, 'Lion082', NULL, NULL, 9),
	(15, '110208', 3, 'Lion083', NULL, NULL, 9),
	(16, '110208', 4, 'Lion084', NULL, NULL, 9),
	(17, '110208', 5, 'Lion085', NULL, NULL, 9),
	(18, '110208', 6, 'Lion086', NULL, NULL, 9),
	(19, '110109', 1, 'Lion091', NULL, NULL, 10),
	(20, '110109', 2, 'Lion092', NULL, NULL, 10),
	(21, '110109', 3, 'Lion093', NULL, NULL, 10),
	(22, '110109', 4, 'Lion094', NULL, NULL, 10),
	(23, '110109', 5, 'Lion095', NULL, NULL, 10),
	(24, '110109', 6, 'Lion096', NULL, NULL, 10),
	(25, '110110', 1, 'Lion101', NULL, NULL, 12),
	(26, '110110', 2, 'Lion102', NULL, NULL, 12),
	(27, '110110', 3, 'Lion103', NULL, NULL, 12),
	(28, '110110', 4, 'Lion104', NULL, NULL, 12),
	(29, '110110', 5, 'Lion105', NULL, NULL, 12),
	(30, '110110', 6, 'Lion106', NULL, NULL, 12),
	(31, '110211', 1, 'Lion111', NULL, NULL, 2),
	(32, '110211', 2, 'Lion112', NULL, NULL, 2),
	(33, '110211', 3, 'Lion113', NULL, NULL, 2),
	(34, '110211', 4, 'Lion114', NULL, NULL, 2),
	(35, '110211', 5, 'Lion115', NULL, NULL, 2),
	(36, '110211', 6, 'Lion116', NULL, NULL, 2),
	(37, '130301', 1, 'Fox011', NULL, NULL, 6),
	(38, '130301', 2, 'Fox012', NULL, NULL, 6),
	(39, '130301', 3, 'Fox013', NULL, NULL, 6),
	(40, '130301', 4, 'Fox014', NULL, NULL, 6),
	(41, '130301', 5, 'Fox015', NULL, NULL, 6),
	(42, '130301', 6, 'Fox016', NULL, NULL, 6),
	(43, '130302', 1, 'Fox021', NULL, NULL, 11),
	(44, '130302', 2, 'Fox022', NULL, NULL, 11),
	(45, '130302', 3, 'Fox023', NULL, NULL, 11),
	(46, '130302', 4, 'Fox024', NULL, NULL, 11),
	(47, '130302', 5, 'Fox025', NULL, NULL, 11),
	(48, '130302', 6, 'Fox026', NULL, NULL, 11),
	(49, '130303', 1, 'Fox031', NULL, NULL, 12),
	(50, '130303', 2, 'Fox032', NULL, NULL, 12),
	(51, '130303', 3, 'Fox033', NULL, NULL, 12),
	(52, '130303', 4, 'Fox034', NULL, NULL, 12),
	(53, '130303', 5, 'Fox035', NULL, NULL, 12),
	(54, '130303', 6, 'Fox036', NULL, NULL, 12),
	(55, '140301', 1, 'Cat011', NULL, NULL, 3),
	(56, '140301', 2, 'Cat012', NULL, NULL, 3),
	(57, '140301', 3, 'Cat013', NULL, NULL, 3),
	(58, '140301', 4, 'Cat014', NULL, NULL, 3),
	(59, '140301', 5, 'Cat015', NULL, NULL, 3),
	(60, '140301', 6, 'Cat016', NULL, NULL, 3),
	(61, '140102', 1, 'Cat021', NULL, NULL, 4),
	(62, '140102', 2, 'Cat022', NULL, NULL, 4),
	(63, '140102', 3, 'Cat023', NULL, NULL, 4),
	(64, '140102', 4, 'Cat024', NULL, NULL, 4),
	(65, '140102', 5, 'Cat025', NULL, NULL, 4),
	(66, '140102', 6, 'Cat026', NULL, NULL, 4),
	(67, '140303', 1, 'Cat031', NULL, NULL, 10),
	(68, '140303', 2, 'Cat032', NULL, NULL, 10),
	(69, '140303', 3, 'Cat033', NULL, NULL, 10),
	(70, '140303', 4, 'Cat034', NULL, NULL, 10),
	(71, '140303', 5, 'Cat035', NULL, NULL, 10),
	(72, '140303', 6, 'Cat036', NULL, NULL, 10),
	(73, '140204', 1, 'Cat041', NULL, NULL, 2),
	(74, '140204', 2, 'Cat042', NULL, NULL, 2),
	(75, '140204', 3, 'Cat043', NULL, NULL, 2),
	(76, '140204', 4, 'Cat044', NULL, NULL, 2),
	(77, '140204', 5, 'Cat045', NULL, NULL, 2),
	(78, '140204', 6, 'Cat046', NULL, NULL, 2),
	(79, '140105', 1, 'Cat051', NULL, NULL, 1),
	(80, '140105', 2, 'Cat052', NULL, NULL, 1),
	(81, '140105', 3, 'Cat053', NULL, NULL, 1),
	(82, '140105', 4, 'Cat054', NULL, NULL, 1),
	(83, '140105', 5, 'Cat055', NULL, NULL, 1),
	(84, '140105', 6, 'Cat056', NULL, NULL, 1),
	(85, '140206', 1, 'Cat061', NULL, NULL, 9),
	(86, '140206', 2, 'Cat062', NULL, NULL, 9),
	(87, '140206', 3, 'Cat063', NULL, NULL, 9),
	(88, '140206', 4, 'Cat064', NULL, NULL, 9),
	(89, '140206', 5, 'Cat065', NULL, NULL, 9),
	(90, '140206', 6, 'Cat066', NULL, NULL, 9);
/*!40000 ALTER TABLE `SkillPart` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
