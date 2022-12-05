USE `koa_main`;
ALTER TABLE `LeaderboardRating`
	ADD COLUMN `PlayCount` INT NOT NULL DEFAULT '0' COMMENT '該季遊玩次數' AFTER `UpdateTime`,
	ADD INDEX `PlayCount` (`PlayCount`);
