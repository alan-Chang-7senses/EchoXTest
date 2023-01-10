# 資料庫備註說明 - koa_log - Log 資料庫

## BaseProcess - 基礎處理 log

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| UserID | 使用者ID | 0  = 非登入狀態 |
| Result | 處理結果 | 1 = 成功<br>0 = 失敗 |
| HttpCode | [HTTP回應狀態碼](../api/codes/httpCode.md) | - |
| Message | 處理結果訊息 | 處理成功時 = null<br>處理失敗時 = ResultData 的 message 內容<br>若 ResultData 的 code = 999，記錄額外的錯誤訊息 |

## MyCardPayment - MyCard 儲值資訊

- 提供 MyCard 撈取儲值紀錄資訊，不可刪除。
- 所需資料為 MyCard 堤供，使用於差異性查詢用(技術文件3.7) 。

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 |  |
| PaymentType | 付費方式 | 見技術文件(附錄 A) |
| TradeSeq | MyCard 交易序號 |  |
| MyCardTradeNo | 交易號碼，見技術文件(3.3.5 ) |  |
| FacTradeSeq | 廠商交易序號 | 同 StorePurchaseOrders.OrderID |
| CustomerId | 使用者編號 | 同 Users.UserID |
| Amount | 支付金額 |  |
| Currency | 支付的幣種 | 見 https://www.iso.org/iso-4217-currency-codes.html |
| TradeDateTime | 建立時間 |  |
| CreateAccountDateTime |  創立帳號時間 |  |
| CreateAccountIP | 創立帳號 IP |  |


## NFTCreatePlayer - NFT創角紀錄
- 紀錄NFT角色創立時的相關資訊。


## NFTItemLog - NFT道具使用(銷毀)紀錄

- 平台燒毀 NFT，對遊戲發送 itemRedeemed 事件的紀錄資訊。

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 |  |
| Email | 玩家遊戲內帳號綁定信箱 |  |
| Payload | 平台傳送的 Payload 資訊 |  |
| IsCompleted | 交易是否完成 | 1 = 完成<br>0 = 未完成 |
| Message | 除錯訊息 | 失敗時提示失敗原因<br>成功時提示 mailID 和 道具資訊 |
| Timestamp | 交易平台的時間戳 | 用來判斷平台是否已經傳送過此交易訊息 |
| CreateTime | 紀錄建立時間 | 紀錄建立時間 |


## NFTOwnershipTransfer - NFT角色所有權轉移紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| NewOnwerUserID | 新持有者的使用者ID | 0表示無法得知使用者 |
| OldOnwerUserID | 舊持有者的使用者ID | 0表示無法得知使用者 |

- 此log只代表在遊戲端資料庫中角色所有權的轉移。無法得知真實NFT交易的情況。


## PlayerRating - 玩家積分紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |

## PowerLog - 玩家電力變化紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Cause | 變化原因 | 0 = 自然恢復<br>1 = 系統獎勵<br>2 = 使用UCG<br>3 = 一般PVE通關<br>4 = PVE掃蕩通關 |

## PVECleared - PVE通關紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| SyncRate | 獲得同步率量 | 單位為1/1000000 |

## SeasonRankingReward - 賽季排名獎勵紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| Serial | 流水號 |  |
| SeasonID | 賽季 ID |  |
| Lobby | [大廳（賽制）](../api/codes/race.md#lobby) | - |
| Ranking | 賽季排名 |  |
| UserID | User ID |  |
| PlayerID | 賽季參賽Peta ID |  |
| Content | 獲得的賽季獎勵資訊 |  |
| LogTime | 資料建立時間 |  |

## UpgradeLevel - 角色升等紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| BonusType | 經驗加成種類 | 0 = 無加成<br>1 = 成功<br>2 = 超級成功|

## UpgradeLevel - 角色升階紀錄
 - 紀錄角色等級突破時的資料

## UpgradeSkill - 角色技能升星紀錄
 - 紀錄角色技能升星時的資料

## UserItemsLog - 使用者物品紀錄

| 欄位名稱 | 說明 | 備註 |
|:-:|:-:|:-:|
| ItemID | 物品編號 | -1 = 電力<br>-2 = 火星幣<br>-3 = 晶鑽<br>-4 = PT幣<br>大於 0 = 物品編號 |
| Cause | 原因 | 0 = 預設<br>1 = 在背包使用道具<br>2 = 信件<br>3 = 競賽<br>4 = 獲取經驗值<br>5 = 提升階級<br>6 = 提升技能等級<br>7 = 建立帳號<br>8 = PVE通關<br>9 = PVE獎牌獎勵<br>10 = 商店<br>11 = 創立NFT角色<br>12 = 新手引導獎勵 |
| Action | 動作 | 1 = 獲得<br>2 = 使用 |