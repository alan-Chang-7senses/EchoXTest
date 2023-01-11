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
## EnergyRunOutBonus - 能量耗盡效果紀錄
| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| BonusID | [獎勵編號](../api/codes/race.md#能量耗盡獎勵) |-|
## LeaderboardRating - 角色積分排行榜

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | 會因更新而跳號 |
| SeasonID | 賽季ID | 值來自koa_main.QualifySeasonData, koa_static.QualifyingData |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
| PlayerID | 角色編號 | -10001 ~ -10007 = 新手教學 AI 角色<br>-1 ~ -10000 = 一般 AI 角色<br>1 ~ 1010000000000000 = 免費 Peta 角色<br> 大於 1010000000000000 = NFT 角色 |
| Rating | 積分 | 賽季當前的積分。會隨著玩家在該賽季比賽而改變，直到賽季結束 |
| UpdateTime | 更新時間 | 每次改寫積分時都會更新。若賽季已結束，則會與koa_log.PlayerRating中該賽季最新的一筆紀錄的LogTime相同 |

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

## RaceHP - 競賽H值紀錄
- 因HP為後端計算。需紀錄競賽中角色當下的H值。

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
| RaceRoomID | 競賽房間編號 |  |
| Status | 狀態 | 0 = 閒置<br>1 = 配對中<br>2 = 已額滿<br>3 = 已開賽 |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
| Version | Photon版號 | 不同版號字串可避免不同環境配對到同一房間中。 |
| LowBound | 下限數值 |  |
| UpBound | 上限數值 |  |
| QualifyingSeasonID| 賽季ID| 沒有紀錄值也沒有使用 |
| CreateTime | 建立時間 |  |
| UpdateTime | 更新時間 |  |
| RaceID | 競賽編號 |  |
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
| RacePlayerID | 競賽角色編號 | - |
| VerifyState | 驗證狀態 | 0 = 無<br>1 = 開局<br>2 = 起跑<br>3 = 施放技能<br>4 = 託管施放技能<br>5 = 更新數值<br>6 = 再次獲得能量<br>7 = 比賽結束<br>8 = 能量耗盡額外獎勵 |
| ServerDistance | 後端移動總距離 | ClientDistance-ServerDistance > config.RaceVerifyDistance = 可能作弊 |
| Speed | 當前速度 | - |
| ServerDistance | 後端移動總距離 | server計算,每次相關api進入更新 |
| ClientDistance | 前端移動總距離 | client計算,每次相關api進入更新 |
| IsCheat | 是否作弊 | 0 = 正常<br>1 = 作弊 |
| UpdateTime | 更新時間 | - |
| StartTime | 開始時間 | 比賽開始使間 |
| CreateTime | 建立時間 | - |

- 用來驗證比賽是否作弊，當收到使用者的API時，進行距離運算 = 時間X速度
- 當比賽結束時，前端距離 > 後端距離視為作弊

## Sessions - 使用者 Session 資料

- 當前端有訪問後端任何 API 即會產生其身份的 SessionID，登入成功後於 SessionData 欄位標記登入狀態，並會在 UserID 欄位紀錄使用者編號。
- 每當前端使用者訪問任何 API 時，該對應身分的 SessionID 即會更新 SessionExpires 欄位，以使其身份在一定時間內保持有效。
- 根據 php.ini 中設定身份過期時間及清除機率，該身份過期並被清除，該使用者將會被登出，當前預設值過期時間為 24 分鐘，1/1000 機率銷毀身份。


## StoreInfos - 交易商店資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| StoreInfoID | 商店資訊編號 | 流水號 |
| UserID | 使用者編號 | - |
| StoreID | 商店編號 | 同 koa_static.StoreData.StoreID |
| FixTradIDs | 固定商品 | 已經移除功能 |
| RandomTradIDs | 隨機商品 | 已經移除功能 |
| RefreshRemainAmounts | 剩餘刷新次數 | - |
| CreateTime | 建立時間 | - |
| UpdateTime | 更新時間 | - |

- 根據 StoreID 紀錄使用者目前有的商店資訊。

## StorePurchaseOrders - 儲值訂單資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| OrderID | 訂單編號 | - |
| UserID | 使用者編號 | - |
| TradeID | 交易序號 | - |
| ProductID | 商品Key | - |
| ItemID | [商品物品ID](../api/codes/item.md#ItemID) | - |
| Amount | 商品數量 | - |
| Plat | [儲值平台](../api/Store/GetInfos.md#PlatType)| - |
| Status | 狀態 | 0 = 取消<br>1 = 購買中<br>2 = 已購買<br>3 = 購買失敗<br>4 = 購買驗證中 |
| Message | 狀態資訊 | - |
| Receipt | 收據orMyCard交易序號 | MyCard的TradeSeq <br> 其他交易(未實作)為收據 |
| AuthCode | MyCard序號 | 跟MyCard溝通的認證碼 |
| CreateTime | 建立時間 | - |
| UpdateTime | 更新時間 | - |

- 儲值下單的紀錄，資料不能清除，因為有付費重購問題。

## StoreTrades - 交易資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| TradeID | 交易序號 | - |
| UserID | 使用者編號 | - |
| StoreID | 商店編號 | - |
| Status | 狀態 | 0 = 閒置<br>1 = 使用中 <br> 2 = 封存 |
| StoreType | 商店類型 | 1 = 一般商品<br>2 = Apple商品<br>3 = Google商品<br>4 = MyCard商品 |
| IsFix | 是否為固定商品 | 0 = 預設<br>1 = 固定商品<br>2 = 隨機商品 |
| CPIndex | 商店索引 | StoreCounters.CIndex 或<br> StorePurchase.PIndex |
| RemainInventory | 剩餘庫存量 | - |
| UpdateTime | 更新時間 | - |

- 紀錄商店中每一格商品的資訊，不管是儲值商品或一般商品，由 StoreID 去分辯，CPIndex對應不同表
- Status = 0 時，此資料可在刷新時再利用
- Status = 2 時，為儲值商店商品封存，避免儲值付款成功後，資料被異動

## StoreUserInfos - 交易商店資訊
				
| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserID | 使用者編號 | - |
| Device | 使用裝置 | 1 = iOS<br>2 = Andriod |
| ISOCurrency | 使用幣別(ISO) | MyCard需要知道手機目前幣別<br>https://www.iso.org/iso-4217-currency-codes.html | 
| Plat | [儲值平台](../api/Store/GetInfos.md#PlatType) | - |
| AutoRefreshTime | 商店自動刷新時間 | - |

- 因為開啟商店時需要記錄使用者的各個資訊，裝置、平台、幣別等，且使用AutoRefreshTime紀錄目前商店刷新的時間，需重新刷新商店只需設定此值為 0

## SystemLock - 系統鎖定API

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserID | 使用者編號 | - |
| APIName | 使用的API名稱 | - |
| LockFlag | 鎖定標記 | 0 = 解鎖<br>1 = 鎖定 |
| UpdateTime | 更新時間 | - |

- 因 API 為非同步訪問機制，當同一使用者同時間發送多次相同 API 時，可能因非同步存取資料造成結果不如預期，此部份針對資料庫可透過 Transaction 對該筆資料進行鎖定／解鎖來處理，但此期間如果可能需要新增資料筆數則無依據可鎖定／解鎖，故使用此表管控序列流程。

## UserDiamond - 使用者鑽石資訊
- 此表未啟用。無相關功能實作

## UserFreePeta - 當前可選擇免費Peta

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| FreePetaInfo | 隨機產生的三隻角色資料。為json格式。 | - |

- 使用者初次登入遊戲可獲得免費 Peta，此為隨機提供給使用者選擇與獲得，初期版本只能擇一獲得，故在前端操作未完成之前，於此表暫存提供選擇的角色資料。
- CB2 版本，使用者不需選擇而直接獲得全部角色。

## UserItems - 使用者持有物品

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserItemID | 使用者物品編號 | - |
| UserID | 使用者編號 | - |
| ItemID | [物品編號](../api/codes/item.md#ItemID) | - |
| Amount | 數量 | - |
| CreateTime | 建立時間 | - |
| UpdateTime | 更新時間 | - |


## UserMailItems - 玩家信件夾帶道具

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserMailID | 玩家信件編號 | 對應 UserMails 的 UserMailID |
| ItemID | 夾帶的道具 ID | - |
| Amount | 夾帶的道具數量 | - |

## UserMails - 玩家信件

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserMailID | 玩家信件編號 | - |
| UserID | 信件擁有者的 User ID | - |
| MailsID | 信件內文語系顯示 ID | - |
| MailArgument | 信件參數 | 傳給前端取代字元參數 |
| OpenStatus | 開啟狀態 | 0 = 關閉<br>1 = 開啟 |
| ReceiveStatus | 領取狀態 | 0 = 未領取<br>1 = 已領取 |
| CreateTime | 信件建立時間 | - |
| UpdateTime | 信件狀態更新時間 | - |
| FinishTime | 信件有效日期 | 超過存在時間為刪除信件 |

## UserPower - 使用者電力更新紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| PowerUpdateTime | 電力更新時間 | 非更新當下的時間。值為：上次更新時間 + 每點電力恢復所需時間 * 欲恢復電力點數 |

## UserPVELevel - 玩家PVE關卡資料

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| MedalAmount | 獲得獎牌數量 | 獎牌數量為0時代表該關卡尚未通關 |
| Status | 關卡狀態 | 0 = 閒置<br>1 = 進行中 |

## UserRetainPoints - 使用者留存積分

- 此為使用者初次登入系統以來的連續登入計量，初次登入有 1 分，每日登入加 1 分，若有一天未登入則不再累加，主要用於計算該使用者的留存天數。


## UserRewardTimes - 使用者領獎時間標記
| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserRewardTimeID | 使用者領獎時間ID | 流水號 |
| UserID | 使用者編號 | - |
| CoinTime | 領取金幣賽入場卷時間 | - |
| PTTime | 領取PT賽入場卷時間 | - |
| CreateTime | 建立時間 | - |
| UpdateTime | 更新時間 | - |

## Users - 使用者帳號資料

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Status | 狀態 | 0 = 禁用<br>1 = 啟用 |
| Username | 帳號 | 當使用 Metasens SSO 登入時，此欄位為 Metasens ID |
| Email | 電子信箱 | 使用 Metasens SSO 登入時，才有此欄位值 |
| PetaToken | PT幣 | 實際值需除以 100 |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |