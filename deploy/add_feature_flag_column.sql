USE `koa_main`;
ALTER TABLE `ConfigVersions`
	ADD COLUMN `FeatureFlag` TEXT NOT NULL COMMENT '�����}�����(JSON)' AFTER `Avatar`;
