USE `koa_main`;
ALTER TABLE `Users`
	ADD COLUMN `Tutorial` TINYINT UNSIGNED NOT NULL DEFAULT '1' COMMENT '新手引導進度' AFTER `FirstNFTPlayerAmount`;

INSERT INTO `koa_main`.`Configs` (`Name`, `Value`, `Comment`) VALUES ('TutorialRewards', '[{"Step":1, "ItemID":1003, "Amount":1}]', '新手引導獎勵');