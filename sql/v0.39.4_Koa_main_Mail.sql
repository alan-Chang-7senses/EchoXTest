USE `koa_main`;

INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES ('MailShowLimit', '100', '信件顯示上限');

ALTER TABLE `UserMails`
	ADD INDEX `OpenStatus` (`OpenStatus`),
	ADD INDEX `ReceiveStatus` (`ReceiveStatus`),
	ADD INDEX `FinishTime` (`FinishTime`);


USE `koa_static`;

CREATE TABLE IF NOT EXISTS `MailsItems` (
	`Serials` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '流水號',
	`MailsID` INT(10) UNSIGNED NOT NULL COMMENT '信件編號',
	`StartTime` INT(11) NOT NULL DEFAULT '0' COMMENT '開始時間',
	`EndTime` INT(11) NOT NULL DEFAULT '0' COMMENT '結束時間',
	`RewardID` INT(11) NOT NULL DEFAULT '0' COMMENT '獎勵編號',
	PRIMARY KEY (`Serials`) USING BTREE
)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='信件道具資料';

