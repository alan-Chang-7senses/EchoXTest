USE `koa_log`;

-- 傾印  資料表 koa_log.NFTCreatePlayer 結構
CREATE TABLE IF NOT EXISTS `NFTCreatePlayer` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `PlayerID` bigint(20) NOT NULL COMMENT '角色編號',
  `UserID` int(11) NOT NULL COMMENT '使用者編號',
  `MetadataURL` varchar(255) NOT NULL DEFAULT '' COMMENT '角色Metadata連結',
  `LogTime` int(11) NOT NULL COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB AUTO_INCREMENT=451 DEFAULT CHARSET=utf8mb4 COMMENT='NFT角色創建紀錄';

CREATE TABLE IF NOT EXISTS `NFTOwnershipTransfer` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `NewOnwerUserID` int(11) NOT NULL DEFAULT 0 COMMENT '轉入使用者編號(0為未知使用者)',
  `OldOnwerUserID` int(11) NOT NULL DEFAULT 0 COMMENT '轉出使用者編號(0為未知使用者)',
  `PlayerID` bigint(20) NOT NULL COMMENT '角色編號',
  `LogTime` int(11) NOT NULL COMMENT '紀錄時間',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='NFT所有權轉移紀錄';

USE `koa_static`;

INSERT INTO `MailsInfo` (`Serial`, `MailsID`, `Lang`, `Title`, `Content`, `Sender`, `URL`) VALUES
(34, 12, 0, 'Thank you for your support for Peta Rush', 'Thank you for your support for Peta Rush. We have prepared 30 "PT Tickets" as gifts to let you run free on Mars.', 'Sender: METASENSTeam', ''),
	(35, 12, 2, 'Thank you for your support for Peta Rush', 'Thank you for your support for Peta Rush. We have prepared 30 "PT Tickets" as gifts to let you run free on Mars.', 'Sender: METASENS Team', ''),
	(36, 12, 12, '感謝你對Peta Rush的支持', '感謝你對Peta Rush的支持，在此送上「PT劵」30張，讓你在火星上盡情的奔跑！', '寄件人：METASENS團隊', '');

USE `Koa_main`;
UPDATE `koa_main`.`Configs` SET `Value`='12' WHERE  `Name`='NewNFTRewardMailID';