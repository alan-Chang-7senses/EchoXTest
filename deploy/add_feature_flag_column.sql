USE `koa_main`;
ALTER TABLE `ConfigVersions`
	ADD COLUMN `FeatureFlag` TEXT NOT NULL COMMENT '介面開關資料(JSON)' AFTER `Avatar`;
