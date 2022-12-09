USE `koa_static`;

CREATE TABLE IF NOT EXISTS `QualifyingData` (
	`SeasonID` INT(11) NOT NULL AUTO_INCREMENT COMMENT '賽季編號',
	`SeasonName` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '企劃註解' COLLATE 'utf8mb4_general_ci',
	`Lobby` INT(11) NOT NULL DEFAULT '0' COMMENT '賽制大廳',
	`Scene` INT(10) UNSIGNED NULL DEFAULT '0' COMMENT '場地編號',
	`StartTime` INT(11) NOT NULL DEFAULT '0' COMMENT '開始時間',
	`EndTime` INT(11) NOT NULL DEFAULT '0' COMMENT '結束時間',
	`CreateTime` INT(11) NOT NULL DEFAULT '0' COMMENT '建立時間',
	PRIMARY KEY (`SeasonID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='賽季設定表';


INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (1, '', 1, 1001, 1668988800, 1669593600, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (2, '', 2, 1001, 1668988800, 1669593600, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (3, '', 3, 1001, 1668988800, 2147483647, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (4, '', 4, 1001, 1668988800, 1669593600, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (5, '', 5, 1001, 1668988800, 1669593600, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (6, '', 1, 1001, 1669593600, 1669680000, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (7, '', 2, 1001, 1669593600, 1669680000, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (8, '', 4, 1001, 1669593600, 1669680000, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (9, '', 5, 1001, 1669593600, 1669680000, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (10, '', 1, 1001, 1669680000, 1670025600, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (11, '', 2, 1001, 1669680000, 1670025600, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (12, '', 4, 1001, 1669680000, 1669766400, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (13, '', 5, 1001, 1669680000, 1669766400, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (14, '', 4, 1001, 1669766400, 1669852800, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (15, '', 5, 1001, 1669766400, 1669852800, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (16, '', 4, 1001, 1669852800, 1669939200, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (17, '', 5, 1001, 1669852800, 1669939200, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (18, '', 4, 1001, 1669939200, 1670025600, 1668988800);
INSERT INTO `QualifyingData` (`SeasonID`, `SeasonName`, `Lobby`, `Scene`, `StartTime`, `EndTime`, `CreateTime`) VALUES (19, '', 5, 1001, 1669939200, 1670025600, 1668988800);








USE `koa_main`;

CREATE TABLE IF NOT EXISTS `QualifyingSeasonData` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
	`SeasonID` INT(11) NOT NULL DEFAULT '0' COMMENT '賽季編號',
	`Lobby` INT(11) NOT NULL DEFAULT '0' COMMENT '賽制大廳',
	`Status` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '狀態 (0:關, 1:開)',
	`Assign` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否派獎 (0:無, 1:有)',
	`UpdateTime` INT(11) NOT NULL DEFAULT '0' COMMENT '更新時間',
	PRIMARY KEY (`ID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='賽季資料';