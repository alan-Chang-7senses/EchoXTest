# King of Animals Games API

## 介紹

- 提供遊戲前端（Unity）存取資料以及處理遊戲邏輯的後端功能。

## 目錄

- [API 回應規格](response.md)。
- API 功能：
	- 使用者（玩家）：
		- [登入](User/Login.md)。
		- [登出](User/Logout.md)。
		- [當前場景](User/CurrentScene.md)。
		- [當前角色](User/CurrentPlayer.md)。
		- [當前使用者資訊](User/CurrentInfo.md)。
	- 角色：
		- [Avatar 部位](Player/AvatarParts.md)。
		- [技能](Player/Skills.md)。
	- 競賽：
		- [機器人角色](Races/BotPlayer.md)。
		- [釋放機器人角色](Races/BotPlayerRelease.md)。
		- [開局](Races/Ready.md)。
		- [紀錄位置](Races/RecordPositions.md)。
		- [角色數值](Races/PlayerValues.md)。
		- [託管角色數值](Races/HostedPlayerValues.md)。
		- [發動技能](Races/LaunchSkill.md)。
		- [排名](Races/Rankings.md)。
		- [抵達終點](Races/ReachEnd.md)。
		- [完成競賽](Races/FinishRace.md)。
	- 主介面：
		- [主要資料](MainMenu/MainData.md)。
		- [角色選擇介面資料](MainMenu/CharacterSelectData.md)。
		- [角色資料](MainMenu/CharacterData.md)。
	- 菁英測試：
		- [登入](EliteTest/Login.md)。
		- [使用者資料](EliteTest/UserInfo.md)。
		- [競賽開局](EliteTest/RaceBegin.md)。
		- [競賽完成](EliteTest/RaceFinish.md)。
		- [積分排行](EliteTest/ScoreList.md)。
		- [最快排行](EliteTest/FastestList.md)。
- [HTTP 狀態碼](codes/httpCode.md)。
- [API 錯誤碼](codes/errorCode.md)。
- 常數定義：
	- [角色](codes/player.md)。
	- [場景](codes/scene.md)。
	- [技能](codes/skill.md)。
	- [競賽](codes/race.md)。
