# 資料庫備註說明 - koa_static - 遊戲內容數值資料庫

## Announcement - 遊戲公告

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Type | 公告種類 | 1 = 活動<br>0 = 公告 |
| Lang | [語言](../api/codes/other.md#lang) | - |

## CompetitionsInfo - 匹配與積分計算資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| WeeksPerSeason | 賽季時長 | *此欄位已棄用* |
| ResetRate | 重置積分比例 | 千分比。1/1000 |
| NewRoomRate | 新房間機率 | 千分比。1/1000 |

## DirtyWord - 禁字集

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Type | 類型 | 0 = 暱稱禁字<br>1 = 公會名稱禁字<br>2 = 聊天室禁字 |

## FreePetaInfo - 免費Peta基礎資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Type | 類型 | 0 = 速度<br>1 = 平衡<br>2 = 持久 |


## FreeTicket - 免費門票發放時間表(日) 

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 門票編號 | - |
| Ticket_Coin | 金幣賽單人 | - |
| Ticket_PT | PT賽單人 | - |
| Ticket_Group | PT賽群體 | - |

- 紀錄可領票卷區間時間，內容為 00:00:00 (UTC + 0 時分秒)格式，可多筆區間，領完一筆，下一筆為
下次可領時間，資料用罄則從頭開始，無限輪迴。

## HintText - 提示文字															

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| HintID | 提示編號 | - |
| Lang | [語言](../api/codes/other.md#lang) | - |
| Title | 標題 | - |
| Content | 內容 | - |

- 各式所需的提示訊息，由前端設定所需時機，經由API取得

## ItemInfo - 物品資訊
														
| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| ItemID | [物品編號](../api/codes/item.md#ItemID) | - |
| ItemName | 物品名稱代號 | 前端多國語系編號 |
| Description | 物品敘述代號 | 前端多國語系編號 |
| ItemType | [物品種類](../api/codes/item.md#itemType) | - |
| icon | 圖號 | - |
| StackLimit | 堆疊上限 | - |
| UseType | [使用類型](../api/codes/item.md#useType) | - |
| EffectType | [效果類型](../api/codes/skill.md#effectType) | 0 = 無效果 |
| EffectValue | 效果值 | - |
| RewardID | 獎勵編號 | - |
| Source | 來源 | - |


## MailsInfo - 信箱

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 |  |
| MailsID | 信件編號 |  |
| Lang | [語言](../api/codes/other.md#lang) | - |
| Title | 標題 |  |
| Content | 內容 |  |
| Sender | 寄件者 |  |
| URL | 網址 | *已棄用* |

## MainBanner - 主畫面 Banner

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Lang | [語言](../api/codes/other.md#lang) | - |

## NFTItemsHandleConfig - NFT道具資料表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| MailID | 信件表 | -1 = 不使用 |
| RewardContentGroup | 獎勵內容 | -1 = 不使用 |

## QualifyingData - 賽季設定表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |

## RaceSceneVerify - 比賽場景驗證表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| SceneID | 場景 | - |
| TrackNumber | 賽道 | - |
| BeginDistance | 開始距離 | - |
| TotalDistance | 總長 | - |

- 此資料由前端提供json格式檔案更新

## RankUpItems - 升階道具表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| RankUpLevel | 升階階段 | 2 = 一升二<br>3 = 二升三<br>4 = 三升四 |
| Attribute | 升階屬性 | 1 = 火<br>2 = 水<br>3 = 木 |

## RewardContent - 獎勵內容

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| ContentGroupID | 群組編號 | - |
| ItemID | [獎勵內容](../api/codes/item.md#ItemID) | - |
| Amount | 獎勵數量 | - |
| Proportion | 獎勵權重或機率(千分比) | - |

- 須配合 RewardInfo 使用
- Proportion 欄位代表「獎勵權重」或「獎勵機率」，須依據獎勵模式決定：
	- 若為獎勵權重，則為同一群組編號（ContentGroupID 欄位）的獎勵內容比例。
	- 若為獎勵機率，則為千分比獲得機率。

## RewardInfo - 獎勵資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| RewardID | 獎勵編號 | - |
| Modes | 獎勵發放模式| 1 = 發放所有獎勵<br>2 = 玩家自選獎勵<br>3 = 依權重隨機獎勵(可重複)<br>5 = 依機率隨機獎勵 |
| Times | 發放次數 | - |
| ContentGroupID | 獎勵內容群組 | - |

- 根據發放模式，由 RewardContent 獲取群組物品，並發放給使用者。

## SceneClimate - 場景氣候

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Weather  |  [天氣](../api/codes/scene.md#weather) | - |
| WindDirection  |  [風向](../api/codes/scene.md#windDirection) | - |
| StartTime | 起始時間 | 當日 00:00:00 起的時間點秒數 |
| Lighting  |  [照明（明暗](../api/codes/scene.md#lighting) | - |

- StartTime 欄位的「當日 00:00:00 」以環境變數所設置的時區為準。

## SceneInfo - 場景主要資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| SceneEnv  |  [環境](../api/codes/scene.md#env) | - |

## SkillEffect - 技能效果表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| EffectType | [效果類型](../api/codes/skill.md#effectType) | - |
| Formula | 公式 | 計算效果所需值公式 |

- Formula 欄位為四則運算公式，將依據運算子放入適當的數值來進行計算：

| 運算子 | 說明 |
|:-:|:-:|:-:|
| H | H值，體力消耗值 |
| S | S值，每秒距離 |
| SPD | 速度 |
| POW | 爆發力 |
| FIG | 鬥志 |
| INT | 聰慧 |
| STA | 耐力 |
| HP | 剩餘體力 |
| N | 技能等級的變動值 |
| % | 除以 100 |

## SkillInfo - 技能資訊表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Energy | 能量條件 | 四個數值以逗點分隔依序如下：<br>紅,黃,藍,綠 |
| Effect | 效果 | 效果編號，可以逗點分隔設定依序發動的效果 |
| Cooldown | 冷卻時間 | 實際值需除以 100 |
| Duration | 時效性 | 0 = 無時效的單次技能<br>-1 = 持續有效<br>大於 0 除以 100 = 技能有效時間 |
| Level1 | 1級N值 | 實際值需除以 100 |
| Level2 | 2級N值 | 實際值需除以 100 |
| Level3 | 3級N值 | 實際值需除以 100 |
| Level4 | 4級N值 | 實際值需除以 100 |
| Level5 | 5級N值 | 實際值需除以 100 |
| MaxCondition | [滿等級技能條件](../api/codes/skill.md#maxCondition) | - |
| MaxEffect | 滿等技能效果 | 滿星效果編號，可以逗點分隔設定依序發動的效果 |

## SkillMaxEffect - 滿星技能效果表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| EffectType | [滿星效果類型](../api/codes/skill.md#maxEffectType) | - |
| Target | [作用對象](../api/codes/skill.md#target) | - |
| Formula | 公式 | 計算效果所需值公式 |

- Formula 欄位為四則運算公式，將依據運算子放入適當的數值來進行計算：

| 運算子 | 說明 |
|:-:|:-:|:-:|
| H | H值，體力消耗值 |
| S | S值，每秒距離 |
| SPD | 速度 |
| POW | 爆發力 |
| FIG | 鬥志 |
| INT | 聰慧 |
| STA | 耐力 |
| HP | 剩餘體力 |
| N | 技能等級的變動值 |
| % | 除以 100 |
| Dune | 環境適性值-沙丘 |
| CraterLake | 環境適性值-亞湖 |
| Volcano | 環境適性值-火山 |
| Tailwind | 風向適性值-順風 |
| Crosswind | 風向適性值-側風 |
| Headwind | 風向適性值-逆風 |
| Sunny | 天氣適性值-晴天 |
| Aurora | 天氣適性值-極光 |
| SandDust | 天氣適性值-沙塵 |
| Flat | 地形適性值-平地 |
| Upslope | 地形適性值-上坡 |
| Downslope | 地形適性值-下坡 |
| Sun | 日照適性值 |
| Featuer | 賽局中與當前角色同屬性角色數量 |
| Genesis | 賽局中創世種數量 |
| Red | 當前技能能量紅色數量 |
| Yellow | 當前技能能量黃色數量 |
| Blue | 當前技能能量藍色數量 |
| Green | 當前技能能量綠色數量 |

## SkillPart - 部位技能對照表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| PartType | 部位 | 1 = 頭部<br>2 = 身體<br>3 = 手<br>4 = 腿<br>5 = 背脊<br>6 = 頭冠 |

## SkillUpgradeItems - 升技道具表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UpgradeLevel | 升技階段 | 2 = 一升二<br>3 = 二升三<br>4 = 三升四<br>5 = 四升五 |
| SpecieCode | 技能物種DNA編號 | 11 = 獅<br>12 = 鹿<br>13 = 狐狸<br>14 = 貓<br>15 = 虎<br>16 = 狗<br>17 = 猴<br>18 = 熊<br>0 = 其他(詞綴技能、純種技能等) |

## StoreCounters - 一般商店
	
| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| CIndex | 索引值 | - |
| GroupID | 群組 | - |
| CounterID | 專櫃Id | - |
| ItemID | [物品編號](../api/codes/item.md#ItemID) | - |
| Amount | 商品數量 | - |
| Inventory | 庫存 | -1 = 無限 |
| Price | 售價 | - |
| Currency | [售價貨幣](../api/codes/item.md#ItemID) | - |
| Promotion | 促銷類型 | (僅前端顯示用)0 = 無<br>1-9 = 10-100% off<br>10 = Giveaway |

## StoreData - 商店資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| StoreID | 商店編號 | - |
| IsOpen | 是否開放 | 0 = 關閉<br>1 = 開啟 |
| MultiName | 商店名稱 | - |
| StoreType | 商店類型 | 1 = 一般商店<br>2 = APP商店<br>3 = Google商店<br>4 = MyCard |
| UIStyle | 介面類型 | - |
| FixedGroup | 固定商品專櫃群組 | - |
| StochasticGroup | 隨機商品專櫃群組 | - |
| RefreshCount | 每日刷新次數 | - |
| RefreshCost | 刷新費用 | - |
| RefreshCostCurrency | [刷新費用之貨幣](../api/codes/item.md#ItemID) | - |

## StoreProductInfo - 儲值商店品項資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| ProductID | 商品Key | - |
| MultiNo | 產品名稱 | 多語系索引 |
| Price | 售價 | - |
| ISOCurrency | 貨幣 | https://www.iso.org/iso-4217-currency-codes.html  |

## StorePurchase - 儲值商店

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| PIndex | 索引值 | - |
| GroupID | 群組 | - |
| PurchaseID | 課金Id | - |
| ItemID | [物品編號](../api/codes/item.md#ItemID) | - |
| Amount | 商品數量 | - |
| ProductID | 商品Key | - |