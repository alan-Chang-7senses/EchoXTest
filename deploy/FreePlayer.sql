USE `koa_static`;

TRUNCATE TABLE `FreePetaDNA`;

TRUNCATE TABLE `FreePetaInfo`;

INSERT INTO `FreePetaDNA` (`Serial`, `HeadDNA`, `BodyDNA`, `HandDNA`, `LegDNA`, `BackDNA`, `HatDNA`) VALUES
	(1, '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000', '110106000011010600001101060000'),
	(2, '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000', '110207000011020700001102070000'),
	(3, '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000', '110208000011020800001102080000'),
	(4, '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000', '110109000011010900001101090000'),
	(5, '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000', '110110000011011000001101100000'),
	(6, '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000', '110211000011021100001102110000');

INSERT INTO `FreePetaInfo` (`ID`, `Type`, `Constitution`, `Strength`, `Dexterity`, `Agility`, `Attribute`) VALUES
	(1, 0, 5500, 6200, 4600, 5700, 1),
	(2, 0, 5500, 6300, 4900, 5300, 1),
	(3, 0, 5400, 6200, 4600, 5800, 1),
	(4, 0, 5500, 5800, 4600, 6100, 1),
	(5, 0, 5500, 5200, 4800, 6500, 1),
	(6, 1, 5600, 5600, 5400, 5400, 1),
	(7, 1, 5700, 5300, 5300, 5700, 1),
	(8, 1, 5600, 5400, 5400, 5600, 1),
	(9, 1, 5700, 5400, 5300, 5600, 1),
	(10, 1, 5500, 5500, 5500, 5500, 1),
	(11, 2, 6100, 4400, 6700, 4800, 1),
	(12, 2, 6000, 4900, 6300, 4800, 1),
	(13, 2, 5800, 4800, 6400, 5000, 1),
	(14, 2, 6700, 4500, 6000, 4800, 1),
	(15, 2, 6200, 4900, 5900, 5000, 1);    