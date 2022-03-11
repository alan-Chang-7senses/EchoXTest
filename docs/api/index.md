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
	- 角色：
		- [Avatar 部位](Player/AvatarParts.md)。
		- [技能](Player/Skills.md)。
	- 競賽：
		- [開局](Races/Ready.md)。
		- [角色數值](Races/PlayerValues.md)。
		- [抵達終點](Races/ReachEnd.md)。
		- [完成競賽](Races/FinishRace.md)。
	- 主介面：
		- [主要資料](MainMenu/MainData.md)。
		- [角色選擇介面資料](MainMenu/CharacterSelectData.md)。
		- [角色資料](MainMenu/CharacterData.md)。
- [HTTP 狀態碼](codes/httpCode.md)。
- [API 錯誤碼](codes/errorCode.md)。
- 常數定義：
	- [角色](codes/player.md)。
	- [場景](codes/scene.md)。
	- [技能](codes/skill.md)。
	- [競賽](codes/race.md)。