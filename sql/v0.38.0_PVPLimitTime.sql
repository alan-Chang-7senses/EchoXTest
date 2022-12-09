USE `koa_main`;

INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES ('PvP_B_LimitTimeData', '[{"start":"07:00:00+8:00", "end":"11:00:00+8:00"},{"start":"19:00:00+8:00", "end":"23:00:00+8:00"}]', 'PT賽每日開放時間與跑馬燈資料(JSON 物件陣列)');
INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES ('PvP_B_LimitTimeEndMarqueeID', '[27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39]', 'PT賽每日結束時各語系跑馬燈編號');
INSERT INTO `Configs` (`Name`, `Value`, `Comment`) VALUES ('PvP_B_LimitTimeStartMarqueeID', '[14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26]', 'PT賽每日開始時各語系跑馬燈編號');


INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (14, 2, 0, 0, '(0) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (15, 2, 1, 0, '(1) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (16, 2, 2, 0, '(2) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (17, 2, 3, 0, '(3) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (18, 2, 4, 0, '(4) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (19, 2, 5, 0, '(5) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (20, 2, 6, 0, '(6) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (21, 2, 7, 0, '(7) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (22, 2, 8, 0, '(8) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (23, 2, 9, 0, '(9) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (24, 2, 10, 0, '(10) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (25, 2, 11, 0, '(11) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (26, 2, 12, 0, '(12) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (27, 2, 0, 0, '(0) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (28, 2, 1, 0, '(1) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (29, 2, 2, 0, '(2) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (30, 2, 3, 0, '(3) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (31, 2, 4, 0, '(4) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (32, 2, 5, 0, '(5) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (33, 2, 6, 0, '(6) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (34, 2, 7, 0, '(7) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (35, 2, 8, 0, '(8) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (36, 2, 9, 0, '(9) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (37, 2, 10, 0, '(10) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (38, 2, 11, 0, '(11) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);
INSERT INTO `Marquee` (`Serial`, `Status`, `Lang`, `Sorting`, `Content`, `CreateTime`, `UpdateTime`) VALUES (39, 2, 12, 0, '(12) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！', 0, 0);