ALTER TABLE `CompetitionsInfo`
	CHANGE COLUMN `Explain` `ExplainName` VARCHAR(50) NULL DEFAULT NULL COMMENT '賽制識別字串(企劃用)' COLLATE 'utf8mb4_general_ci' AFTER `ID`;
