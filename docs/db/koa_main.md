# 資料庫備註說明 - koa_main - 遊戲主要資料庫

## ConfigVersions - 版本設置

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 狀態(0=關) | 1 = 啟用<br>0 = 關閉 |
| FeatureFlag | 該版本的功能開關 | JSON 物件格式字串 |

- FeatureFlag 欄位需與前端搭配定義 Flag，格式如下：

		{
		    "ReceiveTicket":false
		}
		
## LeaderboardRating - 角色積分排行榜

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| SeasonID | 賽季 ID | - |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
| PlayerID | 賽季參賽 Peta ID | - |
| Rating | 賽季積分 | - |
| UpdateTime | 賽季積分更新時間 | - |
| PlayCount | Peta 參賽次數 | - |

## Marquee - 跑馬燈

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| Status | 狀態 | 1 = 啟用 |
| Lang | [語言](../api/codes/other.md#lang) | - |
| Sorting | 排序權重 | - |
| Content | 跑馬燈內文 | - |
| CreateTime | 建立時間 | - |
| UpdateTime | 更新時間 | - |

## PlayerHolder - 角色持有資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| PlayerID | 角色編號 | -10001 ~ -10007 = 新手教學 AI 角色<br>-1 ~ -10000 = 一般 AI 角色<br>1 ~ 1010000000000000 = 免費 Peta 角色<br> 大於 1010000000000000 = NFT 角色 |
| SyncRate | 同步率 | 單位為1/1000000 |

## PlayerLevel - 角色等級（養成）數值

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| PlayerID | 角色編號 | -10001 ~ -10007 = 新手教學 AI 角色<br>-1 ~ -10000 = 一般 AI 角色<br>1 ~ 1010000000000000 = 免費 Peta 角色<br> 大於 1010000000000000 = NFT 角色 |

- LevelBackup 欄位為競賽期間的等級暫存值，離開競賽後即會再次歸零。

## PlayerrCounts - 角色計量數值

- 此表未啟用，無相關功能。

## PlayerSkill - 角色技能等級

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| PlayerID | 角色編號 | -10001 ~ -10007 = 新手教學 AI 角色<br>-1 ~ -10000 = 一般 AI 角色<br>1 ~ 1010000000000000 = 免費 Peta 角色<br> 大於 1010000000000000 = NFT 角色 |
| Slot | 插槽位置 | 0 = 未裝備<br>大於 0 = 已裝備的技能位置 |

- 若該角色技能的 Slot 欄位皆為 0，後端 API 將依序回傳預設插槽位置給遊戲前端。

## QualifyingSeasonData - 賽季資料

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| ID | 流水號 | - |
| SeasonID | 賽季 ID | - |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
| Status | 狀態 | 0 = 關<br>1 = 開 |
| Assign | 是否派獎 | 0 = 無<br>1 = 有 |
| UpdateTime | 資料更新時間 | - |

## RaceBeginHours - 競賽開局時間計量

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |

## RacePlayer - 競賽角色資料

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| RaceNumber | 參與比賽號碼 | 本賽局的第幾位參賽者 |
| Status | 狀態 | 0 = 初始（預設）<br>1 = 資料更新<br>2 = 抵達終點<br>3 = 賽局結束<br>4 = 棄賽<br>5 = 開跑<br>6 = 賽局被釋放（結束） |
| Position | 所在位置 | (無作用）|
| Direction | 角色方向 | 1 = 東<br>2 = 南<br>3 = 西<br>4 = 北 |
| Energy | 剩餘能量 | 四個數值以逗點分隔依序如下：<br>紅,黃,藍,綠 |
| TrackType | 賽道類別| 1 = 平地<br>2 = 上坡<br>3 = 下坡 |
| TrackShape | 賽道形狀 | 1 = 直道<br>2 = 彎道 |
| Rhythm | 比賽節奏| 1 = 全力衝刺<br>2 = 平常速度<br>3 = 保留體力 |
| HP | 剩餘耐力| 實際值需除以 100 |

- Status 欄位為 5，表示該局時間過久未結束，可能為異常賽局，已由系統自動釋放賽局，以免使用者被卡在比賽中而無法繼續進行遊戲。

## RacePlayerEffect - 競賽角色效果

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| EffectType | [效果類型）](../api/codes/skill.md#effectType) | - |

## RacePlayerSkill - 競賽角色發動技能

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| LaunchMax | 是否發動滿星效果 | 0 = 否<br>1 = 是 |
| Result | 滿星發動結果 | 0 = 失敗<br>1 = 成功 |

## RaceRooms - 競賽房間

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 狀態 | 0 = 閒置<br>1 = 配對中<br>2 = 已額滿<br>3 = 已開賽 |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
| Version | Photon版號 | 不同版號字串可避免不同環境配對到同一房間中。 |
| RaceRoomSeats | 競賽席次房間編號 | JSON 陣列字串<br>配對於此房間中的使用者編號 |

## Races - 競賽資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 狀態 | 0 = 初始（預設）<br>1 = 資料更新<br>2 = 抵達（即將結束）<br>3 = 賽局結束<br>4 = 放棄<br>5 = 開跑<br>6 = 過期釋放 |
| Weather | [天氣](../api/codes/scene.md#weather) | - |
| WindDirection | [風向](../api/codes/scene.md#windDirection) | - |
| RacePlayerIDs | 競賽角色資料編號 | JSON 物件字串<br>角色編號與競賽角色編號成對資料 |

- RacePlayerIDs 欄位格式如下：

		{
		    "PlayerID_1":"RacePlayerID_1",
		    "PlayerID_2":"RacePlayerID_2"
		    ...
		}

- RacePlayerIDs 欄位範例如下：

		{
		    "1201":"131",
		    "-10007":"132",
		    "-10004":"133",
		    "-10005":"134",
		    "-10006":"135",
		    "-10003":"136",
		    "-10001":"137",
		    "-10002":"138"
		}
		
## RaceVerify - 比賽驗證表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| VerifyState | 驗證狀態 | 0 = 無<br>1 = 開局<br>2 = 起跑<br>3 = 施放技能<br>4 = 託管施放技能<br>5 = 更新數值<br>6 = 再次獲得能量<br>比賽結束<br>能量耗盡額外獎勵 |
| ServerDistance | 後端移動總距離 | ClientDistance-ServerDistance > config.RaceVerifyDistance = 可能作弊 |
| IsCheat | 是否作弊 | 0 = 正常<br>1 = 作弊 |

## Sessions - 使用者 Session 資料

- 當前端有訪問後端任何 API 即會產生其身份的 SessionID，登入成功後於 SessionData 欄位標記登入狀態，並會在 UserID 欄位紀錄使用者編號。
- 每當前端使用者訪問任何 API 時，該對應身分的 SessionID 即會更新 SessionExpires 欄位，以使其身份在一定時間內保持有效。
- 根據 php.ini 中設定身份過期時間及清除機率，該身份過期並被清除，該使用者將會被登出，當前預設值過期時間為 24 分鐘，1/1000 機率銷毀身份。

## StorePurchaseOrders - 儲值訂單資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 狀態 | 0 = 取消<br>1 = 購買中<br>2 = 已購買<br>3 = 購買失敗<br>4 = 購買驗證中 |

## StoreTrades - 交易資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 狀態 | 0 = 閒置<br>1 = 使用中 |
| IsFix | 是否為固定商品 | 0 = 預設<br>1 = 固定商品<br>2 = 隨機商品 |

## StoreUserInfos - 交易商店資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Device | 使用裝置 | 1 = iOS<br>2 = Andriod |
| ISOCurrency | 使用幣別(ISO) | MyCard需要知道手機目前幣別 | 

## SystemLock - 系統鎖定API

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| LockFlag | 鎖定標記 | 0 = 解鎖<br>1 = 鎖定 |

- 因 API 為非同步訪問機制，當同一使用者同時間發送多次相同 API 時，可能因非同步存取資料造成結果不如預期，此部份針對資料庫可透過 Transaction 對該筆資料進行鎖定／解鎖來處理，但此期間如果可能需要新增資料筆數則無依據可鎖定／解鎖，故使用此表管控序列流程。

## UserFreePeta - 當前可選擇免費Peta

- 使用者初次登入遊戲可獲得免費 Peta，此為隨機提供給使用者選擇與獲得，初期版本只能擇一獲得，故在前端操作未完成之前，於此表暫存提供選擇的角色資料。
- CB2 版本，使用者不需選擇而直接獲得全部角色。

## UserMailItems - 玩家信件夾帶道具

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserMailID | 玩家信件編號 | 對應 UserMails 的 UserMailID |
| ItemID | 夾帶的道具 ID | - |
| Amount | 夾帶的道具數量 | - |

## UserMails - 玩家信件

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserMailID | 信件流水號 | - |
| UserID | 信件擁有者的 User ID | - |
| MailsID | 信件內文語系顯示 ID | - |
| MailArgument | 信件參數 | 傳給前端取代字元參數 |
| OpenStatus | 開啟狀態 | 0 = 關閉<br>1 = 開啟 |
| ReceiveStatus | 領取狀態 | 0 = 未領取<br>1 = 已領取 |
| CreateTime | 信件建立時間 | - |
| UpdateTime | 信件狀態更新時間 | - |
| FinishTime | 信件有效日期 | - |

## UserPVELevel - 玩家PVE關卡資料

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 關卡狀態 | 0 = 閒置<br>1 = 進行中 |

## UserRetainPoints - 使用者留存積分

- 此為使用者初次登入系統以來的連續登入計量，初次登入有 1 分，每日登入加 1 分，若有一天未登入則不再累加，主要用於計算該使用者的留存天數。

## Users - 使用者帳號資料

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 狀態 | 0 = 禁用<br>1 = 啟用 |
| Username | 帳號 | 當使用 Metasens SSO 登入時，此欄位為 Metasens ID |
| Email | 電子信箱 | 使用 Metasens SSO 登入時，才有此欄位值 |
| PetaToken | PT幣 | 實際值需除以 100 |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
