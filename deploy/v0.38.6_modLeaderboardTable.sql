use `koa_static`;


ALTER TABLE `Leaderboard`
	CHANGE COLUMN `Group` `GroupID` TINYINT(4) NULL DEFAULT NULL COMMENT '排行榜主項群組編號' AFTER `Serial`;

