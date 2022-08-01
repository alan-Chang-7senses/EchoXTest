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
		- [持有物品](User/Items.md)。
		- [隨機三隻免費Peta](User/Get3FreePlayer.md)。
		- [完成免費Peta三選一](User/FinishFreePlayer.md)。
			
	- 角色：
		- [Avatar 部位](Player/AvatarParts.md)。
		- [技能](Player/Skills.md)。
		- [儲存技能](Player/SetSkill.md)。
		- [設定暱稱](Player/SetNickname.md)。
	- 競賽：
		- [機器人角色](Races/BotPlayer.md)。
		- [釋放機器人角色](Races/BotPlayerRelease.md)。
		- [PVP配對](Races/PVPMatch.md)。
		- [PVP配對離開](Races/PVPMatchQuit.md)。		
		- [開局](Races/Ready.md)。
		- [紀錄位置](Races/RecordPositions.md)。
		- [斷線恢復](Races/OfflineRecovery.md)。
		- [斷線恢復(取多人資料)](Races/OfflineRecoveryData.md)。
		- [角色數值](Races/PlayerValues.md)。
		- [託管角色數值](Races/HostedPlayerValues.md)。
		- [發動技能](Races/LaunchSkill.md)。
		- [託管發動技能](Races/HostedLaunchSkill.md)。
		- [能量耗盡獎勵](Races/BonusEnergyRunOut.md)。
		- [能量再獲得](Races/EnergyAgain.md)。
		- [排名](Races/Rankings.md)。
		- [抵達終點](Races/ReachEnd.md)。
		- [完成競賽](Races/FinishRace.md)。
	- 公告：
		- [跑馬燈](Notices/Marquee.md)。
	- 主介面：
		- [主要資料](MainMenu/MainData.md)。
		- [角色選擇介面資料](MainMenu/CharacterSelectData.md)。
		- [角色資料](MainMenu/CharacterData.md)。
	- 信箱:
		- [查詢信件](Mails/GetMails.md)。
		- [信件已讀/領獎](Mails/ReceiveMailsRewards.md)。
		- [刪除信件](Mails/DeleteMails.md)。
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
	- [物品](codes/item.md)。
	- [其它](codes/other.md)。
