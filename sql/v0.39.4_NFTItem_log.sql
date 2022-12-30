USE `koa_static`;

CREATE TABLE IF NOT EXISTS `NFTItemsHandleConfig` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `OutsideCode` text NOT NULL COMMENT '外部平台代號',
  `NFTItemCode` text NOT NULL COMMENT 'NFTItem 識別碼',
  `HandleType` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '收到 NFTItemCode 的處理方式',
  `MailID` int(11) NOT NULL DEFAULT -1 COMMENT '其值為信件表的信件編號,-1 代表這個欄位用不到',
  `RewardContentGroup` int(11) NOT NULL DEFAULT -1 COMMENT '其值為獎勵內容表的群組編號,-1 代表這個欄位用不到',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE NFTItemsHandleConfig ADD UNIQUE KEY OutsideCode_NFTItemCode (`OutsideCode`(20),`NFTItemCode`(20));

USE `koa_log`;

CREATE TABLE IF NOT EXISTS `NFTItemLog` (
  `Serial` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `Email` varchar(255) NOT NULL DEFAULT '0' COMMENT '電子信箱',
  `Payload` text NOT NULL COMMENT '平台Payload',
  `IsCompleted` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否完成交易,完成為1',
  `Message` text DEFAULT NULL COMMENT '交易未完成原因訊息',
  `Timestamp` int(10) unsigned NOT NULL COMMENT '交易平台的時間戳',
  `CreateTime` int(10) unsigned NOT NULL COMMENT '紀錄建立時間',
  PRIMARY KEY (`Serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



USE `koa_main`;

INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES ('NFTItemMailDay', '3650', 'NFT道具信件過期時間(日)');
