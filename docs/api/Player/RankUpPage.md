# 角色 - 升階頁面

## 介紹

- 進入角色升階頁面時，給予所需資訊。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/RankUpPage/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 角色編號，16 碼 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [currentRank](#currentRank) | array | 目前階級資訊 |
| [nextRank](#nextRank) | array | 下個階級資訊 |
| [requireItemDust](#requireItemDust) | array | 粉塵資訊 |
| [requireItemCrystal](#requireItemCrystal) | array | 晶石資訊 |
| [requireCoin](#requireCoin) | array | 金幣資訊 |

#### <span id="currentRank">currentRank 內容</span>


| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| rank | int | 當前階級 |
| maxLevel | int | 當前階級最高等級 |
| skillLevelMax | int | 當前階級技能最高等級 |
#### <span id="nextRank">nextRank 內容</span>


| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| rank | int | 下個階級 |
| maxLevel | int | 下個階級最高等級 |
| skillLevelMax | int | 下個階級技能最高等級 |
#### <span id="requireItemDust">requireItemDust 內容</span>


| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 道具ItemID |
| amount | int | 持有數量 |
| requireAmount | int | 升階需求數量 |
#### <span id="requireItemCrystal">requireItemCrystal 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 道具ItemID |
| amount | int | 持有數量 |
| requireAmount | int | 升階需求數量 |
#### <span id="requireCoin">requireCoin 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| requireAmount | int | 升階需求金幣量 |
| isEnough | bool | 是否足夠升階 |

### Example 回傳結果
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "currentRank": {
            "rank": 3,
            "maxLevel": 90,
            "skillLevelMax": 4
        },
        "nextRank": {
            "rank": 4,
            "maxLevel": 100,
            "skillLevelMax": 5
        },
        "requireItemDust": {
            "itemID": 1111,
            "amount": 1061,
            "requireAmount": 90
        },
        "requireItemCrystal": {
            "itemID": 1112,
            "amount": 9987,
            "requireAmount": 18
        },
        "requireCoin": {
            "requireAmount": 342000,
            "isEnough": true
        }
    }