# 競賽 - 發動技能

## 介紹

- 競賽過程中，用於發動指定技能以及滿等級技能效果。
- 發動技能後，取回本身當前競賽數值、受影響角色競賽數值。
- 發動技能時，可透過 players 參數傳入角色資訊已更新當前競賽資訊。

## URL

http(s)://`域名`/Races/LaunchSkill/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能 ID |
| launchMax | int | 是否發動滿星技能，0 = 否、1 = 是 |
| [players](#players) | string | 角色資訊物件陣列的 JSON 字串<br>未提供此參數則不處理 |

#### <span id="players"> players 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號 |
| hp | float | 剩餘耐力 |

#### players 範例

	[
	    {
	        "id": 1,
	        "hp": 12.33
	    },
	    {
	        "id": -1,
	        "hp": 22.34
	    },
	    {
	        "id": 100,
	        "hp": 99.99
	    }
	]

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| launchMax | int | 是否發動滿星技能，0 = 否、1 = 是 |
| launchMaxResult | int | 滿星技能發動結果，0 = 失敗、1 = 成功 |
| weather | int | [天氣](../codes/scene.md#weather) |
| windDirection | int | [風向](../codes/scene.md#windDirection) |
| [self](#self) | object | 本身角色使用技能後的競賽資料 |
| [others](#others) | array | 其它受技能影響的角色競賽資料陣列 |

#### <span id="self">self 內容</span>


| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| s | float | S值 |
| h | float | H值 |
| hp | float | 剩餘耐力 |
| energy | array | 能量陣列，依序為 紅,黃,藍,綠 |
| maxHP | float | 最大耐力 |

#### <span id="others">others 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 受影響的角色編號 |
| s | float | S值 |
| h | float | H值 |
| hp | float | 剩餘耐力 |
| energy | array | 能量陣列，依序為 紅,黃,藍,綠 |
| maxHP | float | 最大耐力 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "launchMax": 1,
	    "launchMaxResult": 1,
	    "weather": 1,
	    "windDirection": 3,
	    "self": {
	        "h": 1.4755329288145624,
	        "s": 1.8694079999999997,
	        "hp": 34.43,
	        "energy": [
	            4,
	            0,
	            4,
	            2
	        ],
			"maxHP":99.75
	    },
	    "others": [
	        {
	            "id": -38,
	            "h": 0.6806318380282879,
	            "s": 1.00936,
	            "hp": 36.76,
	            "energy": [
	                4,
	                1,
	                7,
	                5
	            ],
				"maxHP":99.75
	        }
	    ]
	}