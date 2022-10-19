# 角色 - 升級頁面

## 介紹

- 進入角色升級頁面時，給予所需資訊。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/UpgradePage/

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
| rank | int | 當前階級 |
| currentCoin | int | 持有金幣 |
| currentExp | int | 當前經驗值 |
| maxLevel | int | 當前最大可升級等級 |
| [levelData](#levelData) | array | 當前可升級之各等級資訊 |
| [itemData](#itemData) | array | 經驗物品資訊 |

#### <span id="levelData">levelData 內容</span>

levelData陣列中的第一個元素是當前等級

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| level | int | 等級 |
| [values](#values) | array | 該等級五圍數值 |
| levelRequireExp | int | 升到該等級所需EXP總和(該級起始經驗值) |

#### <span id="itemData">itemData 內容</span>


| 鍵值 | 值 |
|:-:|:-:|
| 道具itemID(int) | [道具內容](#itemInfo)(object) |


#### <span id="values">values 內容</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| velocity | float | 速度 |
| stamina | float | 耐力 |
| breakOut | float | 爆發 |
| will | float | 鬥志 |
| intelligent | float | 聰慧 |

#### <span id="itemInfo">道具內容</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| amount | int | 道具持有數量 |
| cost | int | 道具花費手續費 |
| exp | int | 道具提升經驗值量 |
| description | string | 道具描述代號 |
| itemName | string | 道具名稱代號 |
| icon | string | 道具圖示代號 |
| autoAmount | int | "一鍵添加"時該道具的數量 |


### Example values

    "values": {
        "velocity": 124.96,
        "stamina": 102.42,
        "breakOut": 97.94,
        "will": 102.67,
        "intelligent": 110.83
    }

### Example 道具內容

        "1003": {
            "amount": 0,
            "cost": 14000,
            "exp": 2520,
            "description": "8603",
            "itemName": "8103",
            "icon": "ItemIcon_1003",
            "autoAmount": 0
        }


### Example 回傳結果
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "rank": 1,
        "currentCoin": 46964900,
        "currentExp": 0,
        "maxLevel": 40,
        "levelData": [
            {
                "level": 1,
                "values": {
                    "velocity": 112.61,
                    "stamina": 112.61,
                    "breakOut": 112.61,
                    "will": 112.61,
                    "intelligent": 112.61
                },
                "levelRequireExp": 0
            },
            {
                "level": 2,
                "values": {
                    "velocity": 112.61,
                    "stamina": 112.61,
                    "breakOut": 112.61,
                    "will": 112.61,
                    "intelligent": 114.33
                },
                "levelRequireExp": 20
            }
        ],
        "itemData": {
            "1003": {
                "amount": 0,
                "cost": 14000,
                "exp": 2520,
                "description": "8603",
                "itemName": "8103",
                "icon": "ItemIcon_1003",
                "autoAmount": 0
            },
            "1002": {
                "amount": 0,
                "cost": 5000,
                "exp": 900,
                "description": "8602",
                "itemName": "8102",
                "icon": "ItemIcon_1002",
                "autoAmount": 0
            },
            "1001": {
                "amount": 0,
                "cost": 1000,
                "exp": 180,
                "description": "8601",
                "itemName": "8101",
                "icon": "ItemIcon_1001",
                "autoAmount": 0
            }
        }
    }