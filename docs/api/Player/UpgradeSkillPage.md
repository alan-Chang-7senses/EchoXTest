# 角色 - 提升技能等級頁面

## 介紹

- 進入角色技能升級頁面時，給予所需資訊。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/UpgradeSkillPage/

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
| [skillsData](#skillsData) | array | 角色所有技能升等資訊 |

#### <span id="skillsData">skillsData 內容</span>


| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能ID |
| hasReachedLimit | bool | 技能是否到上升限制 |
| requireCoin | int | 升階所需金幣數量 |
| isCoinEnough | bool | 金幣是否足夠升階 |
| [requireItem](#requireItem) | array | 技能升階需求素材 |
| [itemHold](#itemHold) | array | 升階對應道具數量 |
| isRequireItemEnough | bool | 持有是否足夠升階 |

#### <span id="requireItem">requireItem 內容</span>


| 鍵值 | 值 |
|:-:|:-:|
| 道具itemID(int) | 需求數量(int) |
#### <span id="itemHold">itemHold 內容</span>


| 鍵值 | 值 |
|:-:|:-:|
| 道具itemID(int) | 持有數量(int) |



### Example 回傳結果
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "skillsData": [
            {
                "id": 40,
                "hasReachedLimit": false,
                "requireCoin": 218000,
                "isCoinEnough": true,
                "requireItem": {
                    "2014": 10
                },
                "itemHold": {
                    "2014": 10000
                },
                "isRequireItemEnough": true
            },
            {
                "id": 59,
                "hasReachedLimit": false,
                "requireCoin": 218000,
                "isCoinEnough": true,
                "requireItem": {
                    "2014": 10
                },
                "itemHold": {
                    "2014": 10000
                },
                "isRequireItemEnough": true
            },
            {
                "id": 80,
                "hasReachedLimit": false,
                "requireCoin": 218000,
                "isCoinEnough": true,
                "requireItem": {
                    "2013": 10
                },
                "itemHold": {
                    "2013": 10000
                },
                "isRequireItemEnough": true
            },
            {
                "id": 85,
                "hasReachedLimit": false,
                "requireCoin": 218000,
                "isCoinEnough": true,
                "requireItem": {
                    "2013": 10
                },
                "itemHold": {
                    "2013": 10000
                },
                "isRequireItemEnough": true
            },
            {
                "id": 151,
                "hasReachedLimit": false,
                "requireCoin": 218000,
                "isCoinEnough": true,
                "requireItem": {
                    "2001": 10
                },
                "itemHold": {
                    "2001": 10000
                },
                "isRequireItemEnough": true
            }
        ]
    }