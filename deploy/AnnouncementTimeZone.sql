use Koa_static;

ALTER TABLE `Announcement`
	ADD COLUMN `CreateTime` INT(11) NOT NULL DEFAULT '0' COMMENT 'UI 顯示公告創建時間' AFTER `Content`,
	DROP COLUMN `CreateTime`;

UPDATE `Announcement` SET `CreateTime`=1666597667 WHERE `Serial`=1;