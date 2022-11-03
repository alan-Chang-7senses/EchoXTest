USE `koa_main`;
ALTER TABLE `PlayerNFT`
	ADD COLUMN `ExternalURL` VARCHAR(255) NULL DEFAULT NULL , 
	ADD COLUMN `Image` VARCHAR(255) NULL DEFAULT NULL  COMMENT '角色圖片',
	ADD COLUMN `AnimationURL` VARCHAR(255) NULL DEFAULT NULL  COMMENT '角色音樂網址';

USE `koa_static`;

CREATE TABLE IF NOT EXISTS `MetadataActivity` (
  `ActivityName` varchar(50) NOT NULL DEFAULT '' COMMENT 'Activity 名稱',
  `Source` tinyint(4) NOT NULL DEFAULT 0 COMMENT '來源標記',
  `Native` tinyint(4) NOT NULL DEFAULT 0 COMMENT '原生種標記',
  `SkeletonType` tinyint(4) NOT NULL DEFAULT 0 COMMENT '骨架類別',
  PRIMARY KEY (`ActivityName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='NFT Metadata 的 Activity 對應表';	