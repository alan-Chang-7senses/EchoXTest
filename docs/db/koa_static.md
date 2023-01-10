# 資料庫備註說明 - koa_static - 遊戲內容數值資料庫

## Announcement - 遊戲公告

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Type | 公告種類 | 1 = 活動<br>0 = 公告 |
| Lang | [語言](../api/codes/other.md#lang) | - |

## CompetitionsInfo - 匹配與積分計算資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| ID | 此欄位等同[大廳（賽制）](../api/codes/race.md#lobby)的值 |-|
| WeeksPerSeason | 賽季時長 | *此欄位已棄用* |
| ResetRate | 重置積分比例 | 千分比。1/1000 |
| NewRoomRate | 新房間機率 | 千分比。1/1000 |
| TicketId | 門票道具編號 | 數值為ItemInfo的ItemID |

## DirtyWord - 禁字集

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Type | 類型 | 0 = 暱稱禁字<br>1 = 公會名稱禁字<br>2 = 聊天室禁字 |

## FreePetaInfo - 免費Peta基礎資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Type | 類型 | 0 = 速度<br>1 = 平衡<br>2 = 持久 |

## ItemCharge - 升級道具使用手續費
- 內容為升級道具之道具ID與使用時的手續費(火星幣)數量。
- 目前只應用在角色"升級"功能。

## ItemInfo - 物品資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| ItemType | [物品種類](../api/codes/item.md#itemType) | - |
| UseType | [使用類型](../api/codes/item.md#useType) | - |
| EffectType | [效果類型](../api/codes/skill.md#effectType) | 0 = 無效果 |

## Leaderboard - 賽季排行榜資料

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| GroupID | 排行榜主項群組編號 | - |
| MainLeaderboradName | 主榜單字串 | - |
| SubLeaderboardName | 子榜單字串 | - |
| CompetitionRuleHint | 榜單規則字串 | - |
| SeasonID | 該榜單賽季識別碼 | - |
| SeasonName | 該榜單賽季企劃識別碼 | - |
| RecordType | 計分類型 | 0 = 角色<br>1 = 玩家 |
| RankRuleHint | 排名基準提示字串 | - |

## MailsInfo - 信箱

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| MailsID | 信件編號 | 信件內文的資料索引 |
| Lang | [語言](../api/codes/other.md#lang) | - |
| Title | 信件標題 | - |
| Content | 信件內容 | - |
| Sender | 寄件者 | - |
| URL | 網址 | *已棄用* |

## MailsItems - 信箱道具資料

- 提供一組信件的範本資料，供後台手動寄信給遊戲玩家使用。

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| MailsID | 信件編號 | 信件內文的資料索引 |
| StartTime | 開始時間 | - |
| EndTime | 結束時間 | - |
| RewardID | 獎勵編號 | - |

## MainBanner - 主畫面 Banner

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| Status | 狀態 | 1 = 啟用 |
| Lang | [語言](../api/codes/other.md#lang) | - |
| ImageURL | 圖片網址 | - |
| PageType | [Banner 目標類型](../api/codes/other.md#bannerTarget)  | - |
| PageURL | 頁面網址 | - |


## MetadataActivity - 角色NFT Metadata的對應

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Source | 來源標記 | 對應koa_main.PlayerNFT的欄位 |
| Native | 原生種標記 | 對應koa_main.PlayerNFT的欄位 |
| SkeletonType | 骨架類別 | 對應koa_main.PlayerNFT的欄位 |
| CreateRewardItemID | 創角獎勵之道具編號 | 對應ItemInfo的ItemID |

## NFTItemsHandleConfig - NFT道具資料表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| OutsideCode | 外部平台代號 | - |
| NFTItemCode | NFTItem 識別碼 | - |
| HandleType | 收到 NFTItemCode 的處理方式 | 0 = 發送信件 |
| MailID | 信件表 | -1 = 不使用 |
| RewardContentGroup | 獎勵內容 | -1 = 不使用 |

## PVEChapter - PVE章節內容

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| RewardIDFirst | 第一階段獎勵編號 | 對應RewardInfo表中的RewardID |
| RewardIDSecond | 第二階段獎勵編號 | 對應RewardInfo表中的RewardID |
| RewardIDThird | 第三階段獎勵編號 | 對應RewardInfo表中的RewardID |

## PVELevel - PVE關卡設定表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| FirstItemIDs | 初次通關獎勵之道具編號 | 對應ItemInfo的ItemID<br>複數物品時可用逗號隔開 |
| SustainItemIDs | 固定通關獎勵之道具編號 | 對應ItemInfo的ItemID<br>複數物品時可用逗號隔開 |
| UserTrackNumber | 玩家角色競賽時所在跑道 | 1~8跑道編號 |
| FirstAI | 第一隻機器人角色編號 | 對應koa_main.PlayerNFT中的PlayerID |
| FirstAITrackNumber | 第一隻機器人競賽時所在跑道 | 1~8跑道編號 |
| SecondAI | 第二隻機器人角色編號 | 對應koa_main.PlayerNFT中的PlayerID |
| SecondAITrackNumber | 第二隻機器人競賽時所在跑道 | 1~8跑道編號 |
| ThirdAI | 第三隻機器人角色編號 | 對應koa_main.PlayerNFT中的PlayerID |
| ThirdAITrackNumber | 第三隻機器人競賽時所在跑道 | 1~8跑道編號 |

## QualifyingData - 賽季設定表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| SeasonID | 賽季編號 | - |
| SeasonName | 企劃註解 | - |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
| Scene | 場地編號 | - |
| StartTime | 開始時間 | - |
| EndTime | 結束時間 | - |
| CreateTime | 建立時間 | - |

## RankUpItems - 升階道具表

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| RankUpLevel | 升階階段 | 2 = 一升二<br>3 = 二升三<br>4 = 三升四 |
| Attribute | 升階屬性 | 1 = 火<br>2 = 水<br>3 = 木 |
| DustItemID | 粉塵道具ID | 對應ItemInfo的ItemID |
| CrystalItemID | 晶石道具ID | 對應ItemInfo的ItemID |

## RewardContent - 獎勵內容

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| ItemID | 獎勵內容 | -1 = 電力<br>-2 = 火星幣<br>-3 = 晶鑽<br>-4 = PT幣<br>大於 0 = 物品編號 |

- Proportion 欄位代表「獎勵權重」或「獎勵機率」，須依據獎勵模式決定：
	- 若為獎勵權重，則為同一群組編號（ContentGroupID 欄位）的獎勵內容比例。
	- 若為獎勵機率，則為千分比獲得機率。

## RewardInfo - 獎勵資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Modes | 獎勵發放模式| 1 = 發放所有獎勵<br>2 = 玩家自選獎勵<br>3 = 依權重隨機獎勵(可重複)<br>5 = 依機率隨機獎勵 |

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
| SceneType | [跑道類別](../api/codes/scene.md#type) | - |

## SeasonRankingRewardNew - 賽季獎勵清單

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 | - |
| SeasonID | 賽季編號 | - |
| SeasonName | 企劃註解 | - |
| Rank | 賽季排名 | - |
| RewarID | 獎勵編號 | - |

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
| BaseItemID | 基礎道具ID | 對應ItemInfo的ItemID |
| ChipItemID | 高階道具ID | 對應ItemInfo的ItemID |

## StoreCounters - 一般商店

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Inventory | 庫存 | -1 = 無限 |
| Promotion | 促銷類型 | 0 = 無<br>1-9 = 10-100% off<br>10 = Giveaway |

## StoreData - 商店資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| IsOpen | 是否開放 | 0 = 關閉<br>1 = 開啟 |
| StoreType | 商店類型 | 1 = 一般商店<br>2 = APP商店<br>3 = Google商店<br>4 = MyCard |

## UpgradeBonus - 角色升級經驗加成Buff

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| BonusID | 加成Buff編號 |1 = 大成功<br>2 = 超級成功|
| Multiplier | 加成倍率 | 值須除以100 |

## UpgradeMode - 角色升級模式

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Mode | 升級模式 | 1 = 普通培養 |
| ChargeMultiply | 手續費倍率 | 須除以100 |
| BigBonusProbability | 大成功倍率 | 須除以100 |
| UltimateBonusProbability | 超級成功倍率 | 須除以100 |
- 當前已廢棄省錢培養、豪爽培養功能。故只有普通培養資料。

## VoiceFileInfo - 播報員資訊

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| VoiceId | 流水號 | - |
| FileName | 音效檔名稱 | - |
| Trigger | 觸發點，同一觸發點的音效檔資訊會被視為同一群組 | - |
| DelayTime | 觸發事件之後延遲播放的秒數 | - |
| Volumn | 音量初始倍率 | - |
| ExInfo | 額外資訊，用以提供給觸發點進行判斷使用 | - |

















