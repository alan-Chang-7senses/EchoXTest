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