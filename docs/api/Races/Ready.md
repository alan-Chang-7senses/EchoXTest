# 競賽 - 開局

## 介紹

- 當玩家準備就緒，即將進入競賽時，透過此功能建立賽局。
- 為每位參與玩家建立競賽角色資料。
- 提供競賽場景資訊，包含該時段的氣候資訊。
- 提供參與競賽各使用者的當前角色競賽初始數據。
- 提供各參與競賽角色的已裝備技能資訊。

## URL

http(s)://`域名`/Races/Ready/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [users](#user1) | string | 參賽玩家所組成的 JSON 陣列字串。 |
| trackType | int | [賽道類別](../codes/race.md#trackType) |
| trackShape | int | [賽道形狀](../codes/race.md#trackShape) |
| direction | int | [角色方向](../codes/player.md#direction) |

#### <span id="users1">users 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者編號 |
| ranking | int | 排名 |
| trackNumber | int | 賽道號碼 |
| rhythm | int | [比賽節奏](../codes/race.md#rhythm)|

#### users 範例

	[
		{
			"id": 1,
			"ranking": 1,
			"trackNumber": 1,
			"rhythm": 1
		},
		{
			"id": 2,
			"ranking": 1,
			"trackNumber": 2,
			"rhythm": 2
		}
	]
	
## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [scene](#scene) | object | 場景資訊 |
| [users](#users2) | array | 各玩家的角色競賽資料陣列 |

#### <span id="scene">scene 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| readySec | int | 起跑準備時間（秒） |
| env | int | [環境](../codes/scene.md#env) |
| weather | int | [天氣](../codes/scene.md#weather) |
| windDirection | int | [風向](../codes/scene.md#windDirection) |
| windSpeed | int | 風速 |
| lighting | int | [照明（明暗）](../codes/scene.md#lighting) |

#### <span id="users2">users 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者編號 |
| player | int | 角色編號 |
| energy | array | 能量陣列，依序為 紅,黃,藍,綠 |
| hp | float | 剩餘耐力 |
| s | float | S值 |
| h | float | H值 |
| startSec | float | 起跑秒數 |
| [skills](#skills) | array | 角色裝備的技能清單陣列 |


#### <span id="skills">skills 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能編號 |
| name | string | 技能名稱代號 |
| level | int | 技能等級 |
| slot | int | 技能所在插槽 |
| energy | array | 使用條件能量值（int），陣列元素依序為 紅、黃、藍、綠 |
| cooldown | float | 冷卻時間（秒） |
| maxCondition | int | [滿星效果條件](../codes/skill.md#maxCondition) |
| maxConditionValue | int | 滿星效果條件值 |
| [effects](#effects) | array | 技能效果陣列 |
| [maxEffects](#maxEffects) | array | 滿星技能效果陣列 |


##### <span id="effects">effects 技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [效果類型](../codes/skill.md#effectType) |
| duration | int | 0 = 單次效果<br>大於 0 = 時效秒數<br>-1 = 持續到比賽結束。 |

##### <span id="maxEffects">maxEffects 滿星技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [滿星效果類型](../codes/skill.md#maxEffectType) |
| target | int | [作用對象](../codes/skill.md#target) |
| typeValue | int | [滿星效果類型值](../codes/skill.md#maxEffectType) |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "scene": {
	        "readySec": 7,
	        "env": 1,
	        "weather": 1,
	        "windDirection": 2,
	        "windSpeed": 100,
	        "ligthing": 1
	    },
	    "users": [
	        {
	            "id": 1,
	            "player": 1010000000000001,
	            "energy": [
	                4,
	                7,
	                4,
	                3
	            ],
	            "hp": 34.43,
	            "s": 0.4616959999999999,
	            "h": 0.4203605462822456,
	            "startSec": 0.05532473514509443,
	            "skills": [
	                {
	                    "id": 7,
	                    "name": "21001",
	                    "level": 1,
	                    "slot": 1,
	                    "energy": [
	                        1,
	                        1,
	                        1,
	                        0
	                    ],
	                    "cooldown": 3,
	                    "maxCondition": 2,
	                    "maxConditionValue": 3,
	                    "effects": [
	                        {
	                            "type": 204,
	                            "duration": 540
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 402,
	                            "target": 0,
	                            "typeValue": 0
	                        }
	                    ]
	                },
	                {
	                    "id": 8,
	                    "name": "21002",
	                    "level": 1,
	                    "slot": 2,
	                    "energy": [
	                        2,
	                        0,
	                        1,
	                        1
	                    ],
	                    "cooldown": 4,
	                    "maxCondition": 4,
	                    "maxConditionValue": 1,
	                    "effects": [
	                        {
	                            "type": 205,
	                            "duration": 780
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 2,
	                            "target": 0,
	                            "typeValue": 1
	                        }
	                    ]
	                },
	                {
	                    "id": 9,
	                    "name": "21003",
	                    "level": 1,
	                    "slot": 3,
	                    "energy": [
	                        0,
	                        1,
	                        1,
	                        1
	                    ],
	                    "cooldown": 3,
	                    "maxCondition": 1,
	                    "maxConditionValue": 1,
	                    "effects": [
	                        {
	                            "type": 202,
	                            "duration": 540
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 12,
	                            "target": 0,
	                            "typeValue": 1
	                        }
	                    ]
	                },
	                {
	                    "id": 10,
	                    "name": "21004",
	                    "level": 1,
	                    "slot": 4,
	                    "energy": [
	                        0,
	                        1,
	                        1,
	                        2
	                    ],
	                    "cooldown": 4,
	                    "maxCondition": 41,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 201,
	                            "duration": 780
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 402,
	                            "target": 0,
	                            "typeValue": 0
	                        }
	                    ]
	                },
	                {
	                    "id": 11,
	                    "name": "21005",
	                    "level": 1,
	                    "slot": 5,
	                    "energy": [
	                        1,
	                        0,
	                        1,
	                        1
	                    ],
	                    "cooldown": 3,
	                    "maxCondition": 33,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 203,
	                            "duration": 540
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 402,
	                            "target": 0,
	                            "typeValue": 0
	                        }
	                    ]
	                },
	                {
	                    "id": 12,
	                    "name": "21006",
	                    "level": 1,
	                    "slot": 6,
	                    "energy": [
	                        1,
	                        2,
	                        1,
	                        0
	                    ],
	                    "cooldown": 4,
	                    "maxCondition": 11,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 204,
	                            "duration": 780
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 10,
	                            "target": 0,
	                            "typeValue": 1
	                        }
	                    ]
	                }
	            ]
	        },
	        {
	            "id": 2,
	            "player": 1010000000000003,
	            "energy": [
	                4,
	                4,
	                6,
	                3
	            ],
	            "hp": 36.76,
	            "s": 0.34165333333333336,
	            "h": 0.25918171243615035,
	            "startSec": 0.05710044158188782,
	            "skills": [
	                {
	                    "id": 7,
	                    "name": "21001",
	                    "level": 1,
	                    "slot": 1,
	                    "energy": [
	                        1,
	                        1,
	                        1,
	                        0
	                    ],
	                    "cooldown": 3,
	                    "maxCondition": 2,
	                    "maxConditionValue": 3,
	                    "effects": [
	                        {
	                            "type": 204,
	                            "duration": 540
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 402,
	                            "target": 0,
	                            "typeValue": 0
	                        }
	                    ]
	                },
	                {
	                    "id": 9,
	                    "name": "21003",
	                    "level": 1,
	                    "slot": 2,
	                    "energy": [
	                        0,
	                        1,
	                        1,
	                        1
	                    ],
	                    "cooldown": 3,
	                    "maxCondition": 1,
	                    "maxConditionValue": 1,
	                    "effects": [
	                        {
	                            "type": 202,
	                            "duration": 540
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 12,
	                            "target": 0,
	                            "typeValue": 1
	                        }
	                    ]
	                },
	                {
	                    "id": 10,
	                    "name": "21004",
	                    "level": 1,
	                    "slot": 3,
	                    "energy": [
	                        0,
	                        1,
	                        1,
	                        2
	                    ],
	                    "cooldown": 4,
	                    "maxCondition": 41,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 201,
	                            "duration": 780
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 402,
	                            "target": 0,
	                            "typeValue": 0
	                        }
	                    ]
	                },
	                {
	                    "id": 11,
	                    "name": "21005",
	                    "level": 1,
	                    "slot": 4,
	                    "energy": [
	                        1,
	                        0,
	                        1,
	                        1
	                    ],
	                    "cooldown": 3,
	                    "maxCondition": 33,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 203,
	                            "duration": 540
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 402,
	                            "target": 0,
	                            "typeValue": 0
	                        }
	                    ]
	                },
	                {
	                    "id": 12,
	                    "name": "21006",
	                    "level": 1,
	                    "slot": 5,
	                    "energy": [
	                        1,
	                        2,
	                        1,
	                        0
	                    ],
	                    "cooldown": 4,
	                    "maxCondition": 11,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 204,
	                            "duration": 780
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 10,
	                            "target": 0,
	                            "typeValue": 1
	                        }
	                    ]
	                }
	            ]
	        }
	    ]
	}