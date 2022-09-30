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
| currentLevel | int | 當前等級 |
| [currentRank](#currentRank) | array | 目前階級資訊 |
| [nextRank](#nextRank) | array | 下個階級資訊 |
| [itemInfos](#itemInfos) | array | 升階所需物品資訊 |
| [requireCoin](#requireCoin) | array | 金幣資訊 |
| canRankUp | bool | 可否升階 |

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

##### <span id="itemInfos">itemInfos 升級所需物品資訊內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| itemName | string | 物品名稱編號 |
| description | string | 物品描述編號 |
| itemType | int | [物品種類](../codes/item.md#itemType) |
| useType | int | [使用種類](../codes/item.md#useType) |
| stackLimit | int | 物品堆疊上限 |
| icon | string | 物品圖示代號 |
| source | array | 來源／出處 代號陣列 |
| holdAmount | int | 使用者物品持有數量 |
| requiredAmount | int | 升級所需道具數量 |

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
        "currentLevel": 1,
        "currentRank": {
            "rank": 1,
            "maxLevel": 40,
            "skillLevelMax": 2
        },
        "nextRank": {
            "rank": 2,
            "maxLevel": 70,
            "skillLevelMax": 3
        },
        "itemInfos": [
            {
                "itemID": 1131,
                "itemName": "8108",
                "description": "8608",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0008",
                "source": [
                    "s010"
                ],
                "holdAmount": 0,
                "requiredAmount": 30
            },
            {
                "itemID": 1132,
                "itemName": "8109",
                "description": "8609",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0009",
                "source": [
                    "s011"
                ],
                "holdAmount": 0,
                "requiredAmount": 0
            }
        ],
        "requireCoin": "requireAmount",
        "canRankUp": false
    }