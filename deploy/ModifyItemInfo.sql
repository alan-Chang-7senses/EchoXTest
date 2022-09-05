USE `koa_static`;

UPDATE `ItemInfo` SET `EffectValue` = 180 WHERE `ItemID` = 1001;
UPDATE `ItemInfo` SET `EffectValue` = 900 WHERE `ItemID` = 1002;
UPDATE `ItemInfo` SET `EffectValue` = 2520 WHERE `ItemID` = 1003;

USE `koa_log`;


CREATE TABLE IF NOT EXISTS `Upgrade` (
  `Serial` int(11) NOT NULL AUTO_INCREMENT,
  `PlayerID` bigint(20) NOT NULL DEFAULT 0,
  `SkillID` int(11) DEFAULT NULL COMMENT '技能ID',
  `SkillLevelDelta` int(11) NOT NULL DEFAULT 0 COMMENT '技能等級變化量',
  `CoinDelta` int(11) NOT NULL DEFAULT 0 COMMENT '金幣變化量',
  `BonusType` int(11) DEFAULT NULL COMMENT '強化加成類型',
  `ExpDelta` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色經驗值變化量',
  `RankDelta` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '角色階級變化量',
  `LogTime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Serial`,`LogTime`),
  KEY `PlayerID` (`PlayerID`),
  KEY `SkillID` (`SkillID`),
  KEY `RankDelta` (`RankDelta`),
  KEY `ExpDelta` (`ExpDelta`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COMMENT='升級、升階、升技能LOG';