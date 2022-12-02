# 角色 - 提升技能等級

## 介紹

- 提升角色技能等級。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/UpgradeSkill/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 角色編號，16 碼 |
| skillID | int | 技能ID |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| currentLevel | int | 升級後技能等級 |
| [nextLevelRequired](#nextLevelRequired) | array | 下級升級所需道具資訊。滿等回傳null |
| requiredCoin | int | 升級所需金幣。滿等回傳0 |
| coinHold | int | 使用者持有金幣 |
| hasNextLevelReachLimit | bool | 升級後下一級是否到當前階級限制 |

##### <span id="nextLevelRequired">itemInfos 升級所需物品資訊內容</span>

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


### Example 回傳結果
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "currentLevel": 3,
        "nextLevelRequired": [
            {
                "itemID": 2011,
                "itemName": "8111",
                "description": "8611",
                "itemType": 2,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0011",
                "source": [
                    "s002"
                ],
                "holdAmount": 879,
                "requiredAmount": 10
            }
        ],
        "requiredCoin": 218000,
        "coinHold": 7043999,
        "hasNextLevelReachLimit" : true
    }