USE `koa_main`;
ALTER TABLE `Users`
	ADD COLUMN `Tutorial` TINYINT UNSIGNED NOT NULL DEFAULT '1' COMMENT '新手引導進度' AFTER `FirstNFTPlayerAmount`;


INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES ('TutorialRewards', '[{"Step":1, "ItemID":1003, "Amount":1}]', '新手引導獎勵');
INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES ('TutorialSceneID', '4000', '新手引導賽場編號');


INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES (-10007, -10007, 'AI Cat', 0);
INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES (-10006, -10006, 'Prudence Lion', 0);
INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES (-10005, -10005, 'Aimee Lion', 0);
INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES (-10004, -10004, 'Ceto Fox', 0);
INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES (-10003, -10003, 'Oz Fox', 0);
INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES (-10002, -10002, 'Gottloh Fox', 0);
INSERT INTO `PlayerHolder` (`PlayerID`, `UserID`, `Nickname`, `SyncRate`) VALUES (-10001, -10001, 'Airty Lion', 0);


INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `LevelBackup`, `Rank`, `Exp`) VALUES (-10007, 1, 0, 1, 0);
INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `LevelBackup`, `Rank`, `Exp`) VALUES (-10006, 1, 0, 1, 0);
INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `LevelBackup`, `Rank`, `Exp`) VALUES (-10005, 1, 0, 1, 0);
INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `LevelBackup`, `Rank`, `Exp`) VALUES (-10004, 1, 0, 1, 0);
INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `LevelBackup`, `Rank`, `Exp`) VALUES (-10003, 1, 0, 1, 0);
INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `LevelBackup`, `Rank`, `Exp`) VALUES (-10002, 1, 0, 1, 0);
INSERT INTO `PlayerLevel` (`PlayerID`, `Level`, `LevelBackup`, `Rank`, `Exp`) VALUES (-10001, 1, 0, 1, 0);


INSERT INTO `PlayerNFT` (`PlayerID`, `ItemName`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`, `Source`, `StrengthLevel`, `SkeletonType`, `TradeCount`, `ExternalURL`, `Image`, `AnimationURL`) VALUES (-10007, NULL, 6000, 4900, 6100, 6100, 1, '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000', '130301000013030100001303010000', '0000000000000000', 00, 0, 0, 00, 0, NULL, NULL, NULL);
INSERT INTO `PlayerNFT` (`PlayerID`, `ItemName`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`, `Source`, `StrengthLevel`, `SkeletonType`, `TradeCount`, `ExternalURL`, `Image`, `AnimationURL`) VALUES (-10006, NULL, 6400, 5000, 6500, 5200, 3, '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '0000000000000000', 00, 0, 0, 00, 0, NULL, NULL, NULL);
INSERT INTO `PlayerNFT` (`PlayerID`, `ItemName`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`, `Source`, `StrengthLevel`, `SkeletonType`, `TradeCount`, `ExternalURL`, `Image`, `AnimationURL`) VALUES (-10005, NULL, 6100, 4900, 5900, 6200, 2, '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '0000000000000000', 00, 0, 0, 00, 0, NULL, NULL, NULL);
INSERT INTO `PlayerNFT` (`PlayerID`, `ItemName`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`, `Source`, `StrengthLevel`, `SkeletonType`, `TradeCount`, `ExternalURL`, `Image`, `AnimationURL`) VALUES (-10004, NULL, 6100, 5200, 5400, 5400, 1, '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '0000000000000000', 00, 0, 0, 00, 0, NULL, NULL, NULL);
INSERT INTO `PlayerNFT` (`PlayerID`, `ItemName`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`, `Source`, `StrengthLevel`, `SkeletonType`, `TradeCount`, `ExternalURL`, `Image`, `AnimationURL`) VALUES (-10003, NULL, 6300, 5300, 6600, 4900, 3, '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '0000000000000000', 00, 0, 0, 00, 0, NULL, NULL, NULL);
INSERT INTO `PlayerNFT` (`PlayerID`, `ItemName`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`, `Source`, `StrengthLevel`, `SkeletonType`, `TradeCount`, `ExternalURL`, `Image`, `AnimationURL`) VALUES (-10002, NULL, 6100, 6200, 5900, 4900, 2, '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '0000000000000000', 00, 0, 0, 00, 0, NULL, NULL, NULL);
INSERT INTO `PlayerNFT` (`PlayerID`, `ItemName`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`, `Achievement`, `Native`, `Source`, `StrengthLevel`, `SkeletonType`, `TradeCount`, `ExternalURL`, `Image`, `AnimationURL`) VALUES (-10001, NULL, 6000, 6100, 5600, 5400, 1, '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '0000000000000000', 00, 0, 0, 00, 0, NULL, NULL, NULL);


INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 55, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 56, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 58, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 60, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 61, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 63, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 67, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10007, 100, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 55, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 56, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 58, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 60, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 61, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 63, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 67, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10006, 100, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 55, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 56, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 58, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 60, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 61, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 63, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 67, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10005, 100, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 55, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 56, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 58, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 60, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 61, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 63, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 67, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10004, 100, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 55, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 56, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 58, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 60, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 61, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 63, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 67, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10003, 100, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 55, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 56, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 58, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 60, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 61, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 63, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 67, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10002, 100, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 55, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 56, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 58, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 60, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 61, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 63, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 67, 1, 0, 0);
INSERT INTO `PlayerSkill` (`PlayerID`, `SkillID`, `Level`, `LevelBackup`, `Slot`) VALUES (-10001, 100, 1, 0, 0);


INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Email`, `Level`, `Exp`, `PetaToken`, `Coin`, `Power`, `Diamond`, `Player`, `Scene`, `Race`, `Lobby`, `Room`, `CreateTime`, `UpdateTime`, `NFTPlayerAmount`, `FirstNFTPlayerAmount`, `Tutorial`) VALUES (-10007, 0, 'Robot022', 'AI Cat', NULL, NULL, 1, 0, 0, 0, 0, 0, -10007, 1001, 0, 0, 0, 0, 1669091507, 0, NULL, 1);
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Email`, `Level`, `Exp`, `PetaToken`, `Coin`, `Power`, `Diamond`, `Player`, `Scene`, `Race`, `Lobby`, `Room`, `CreateTime`, `UpdateTime`, `NFTPlayerAmount`, `FirstNFTPlayerAmount`, `Tutorial`) VALUES (-10006, 0, 'Robot021', 'Prudence Lion', NULL, NULL, 1, 0, 0, 0, 0, 0, -10006, 1001, 0, 0, 0, 0, 1669091507, 0, NULL, 1);
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Email`, `Level`, `Exp`, `PetaToken`, `Coin`, `Power`, `Diamond`, `Player`, `Scene`, `Race`, `Lobby`, `Room`, `CreateTime`, `UpdateTime`, `NFTPlayerAmount`, `FirstNFTPlayerAmount`, `Tutorial`) VALUES (-10005, 0, 'Robot020', 'Aimee Lion', NULL, NULL, 1, 0, 0, 0, 0, 0, -10005, 1001, 0, 0, 0, 0, 1669091507, 0, NULL, 1);
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Email`, `Level`, `Exp`, `PetaToken`, `Coin`, `Power`, `Diamond`, `Player`, `Scene`, `Race`, `Lobby`, `Room`, `CreateTime`, `UpdateTime`, `NFTPlayerAmount`, `FirstNFTPlayerAmount`, `Tutorial`) VALUES (-10004, 0, 'Robot019', 'Ceto Fox', NULL, NULL, 1, 0, 0, 0, 0, 0, -10004, 1001, 0, 0, 0, 0, 1669091507, 0, NULL, 1);
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Email`, `Level`, `Exp`, `PetaToken`, `Coin`, `Power`, `Diamond`, `Player`, `Scene`, `Race`, `Lobby`, `Room`, `CreateTime`, `UpdateTime`, `NFTPlayerAmount`, `FirstNFTPlayerAmount`, `Tutorial`) VALUES (-10003, 0, 'Robot018', 'Oz Fox', NULL, NULL, 1, 0, 0, 0, 0, 0, -10003, 1001, 0, 0, 0, 0, 1669091507, 0, NULL, 1);
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Email`, `Level`, `Exp`, `PetaToken`, `Coin`, `Power`, `Diamond`, `Player`, `Scene`, `Race`, `Lobby`, `Room`, `CreateTime`, `UpdateTime`, `NFTPlayerAmount`, `FirstNFTPlayerAmount`, `Tutorial`) VALUES (-10002, 0, 'Robot017', 'Gottloh Fox', NULL, NULL, 1, 0, 0, 0, 0, 0, -10002, 1001, 0, 0, 0, 0, 1669091507, 0, NULL, 1);
INSERT INTO `Users` (`UserID`, `Status`, `Username`, `Nickname`, `Password`, `Email`, `Level`, `Exp`, `PetaToken`, `Coin`, `Power`, `Diamond`, `Player`, `Scene`, `Race`, `Lobby`, `Room`, `CreateTime`, `UpdateTime`, `NFTPlayerAmount`, `FirstNFTPlayerAmount`, `Tutorial`) VALUES (-10001, 0, 'Robot016', 'Airty Lion', NULL, NULL, 1, 0, 0, 0, 0, 0, -10001, 1001, 0, 0, 0, 0, 1669091507, 0, NULL, 1);

