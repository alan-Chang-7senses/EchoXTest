USE `koa_main`;
ALTER TABLE `PlayerHolder` MODIFY COLUMN `SyncRate` MEDIUMINT(6) NOT NULL DEFAULT 0 COMMENT '同步率 X 1000000';