USE `koa_static`;

-- 傾印  資料表 koa_static.Announcement 結構
CREATE TABLE IF NOT EXISTS `Announcement` (
  `Serial` int(11) NOT NULL DEFAULT 0 COMMENT '流水號',
  `ID` int(11) NOT NULL DEFAULT 0 COMMENT '公告編號',
  `Type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '公告種類',
  `Lang` tinyint(4) NOT NULL DEFAULT 0 COMMENT '語言代號',
  `GraphURL` text NOT NULL DEFAULT '' COMMENT '圖片URL',
  `Title` text NOT NULL DEFAULT '' COMMENT '公告標題',
  `Content` text NOT NULL DEFAULT '' COMMENT '公告內文',
  `CreateTime` varchar(50) NOT NULL DEFAULT '' COMMENT '創建時間。非發佈時間',
  `PublishTime` int(11) NOT NULL DEFAULT 0 COMMENT '發佈時間',
  `FinishTime` int(11) NOT NULL DEFAULT 0 COMMENT '結束時間',
  PRIMARY KEY (`Serial`) USING BTREE,
  KEY `Lang` (`Lang`),
  KEY `PublishTime` (`PublishTime`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='公告看板資料集合';

INSERT INTO `Announcement` (`Serial`, `ID`, `Type`, `Lang`, `GraphURL`, `Title`, `Content`, `CreateTime`, `PublishTime`, `FinishTime`) VALUES
	(1, 1, 2, 12, '...', '我是標題', '我是公告\r\n我是公告\r\n我是公告', '0000-00-00', 1666597667, 2666607667);

