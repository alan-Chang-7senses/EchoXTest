# 角色 - 技能

## 介紹

- 使用角色編號取得角色的技能資料。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/Skills/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號，16 碼 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [skills](#skills) | array | 角色持有的技能清單陣列 |

#### <span id="skills">skills 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能編號 |
| name | string | 技能名稱代號 |
| icon | string | 技能Icon代號 |
| description | string | 技能敘述代號 |
| level | int | 技能等級 |
| slot | int | 技能所在插槽 |
| energy | array | 使用條件能量值，陣列元素依序為 紅、黃、藍、綠 |
| cooldown | float | 冷卻時間（秒） |
| duration | float | 時效性<br>0 = 單次效果<br>大於 0 = 時效秒數<br>-1 = 持續到比賽結束 |
| ranks | array | 技能星級 1 ~ 5 的 N 值陣列 |
| maxDescription | string | 滿星效果敘述代號 |
| maxCondition | int | [滿星效果條件](../codes/skill.md#maxCondition) |
| maxConditionValue | int | 滿星效果條件值 |
| [effects](#effects) | array | 技能效果陣列 |
| [maxEffects](#maxEffects) | array | 滿星技能效果陣列 |

##### <span id="effects">effects 技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [效果類型](../codes/skill.md#effectType) |
| formulaValue | float | 公式計算結果的效果值 |

##### <span id="maxEffects">maxEffects 滿星技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [滿星效果類型](../codes/skill.md#maxEffectType) |
| target | int | [作用對象](../codes/skill.md#target) |
| formulaValue | float | 公式計算結果的效果值 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "skills": [
	        {
	            "id": 4,
	            "name": "21004",
	            "description": "22201",
	            "level": 1,
	            "slot": 1,
	            "energy": [
	                0,
	                0,
	                2,
	                2
	            ],
	            "cooldown": 2,
	            "duration": 9.8,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23012",
	            "maxCondition": 41,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 201,
	                    "formulaValue": 37.086
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 402,
	                    "target": 0,
	                    "formulaValue": 1.32
	                }
	            ]
	        },
	        {
	            "id": 5,
	            "name": "21005",
	            "description": "22203",
	            "level": 1,
	            "slot": 2,
	            "energy": [
	                2,
	                0,
	                1,
	                0
	            ],
	            "cooldown": 2,
	            "duration": 7.2,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23012",
	            "maxCondition": 31,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 203,
	                    "formulaValue": 31.9725
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 402,
	                    "target": 0,
	                    "formulaValue": 1.32
	                }
	            ]
	        },
	        {
	            "id": 6,
	            "name": "21006",
	            "description": "22202",
	            "level": 1,
	            "slot": 3,
	            "energy": [
	                1,
	                3,
	                0,
	                0
	            ],
	            "cooldown": 2,
	            "duration": 9.8,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23013",
	            "maxCondition": 11,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 202,
	                    "formulaValue": 37.086
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 10,
	                    "target": 0,
	                    "formulaValue": 10
	                }
	            ]
	        },
	        {
	            "id": 14,
	            "name": "21014",
	            "description": "22205",
	            "level": 1,
	            "slot": 4,
	            "energy": [
	                0,
	                2,
	                0,
	                2
	            ],
	            "cooldown": 2,
	            "duration": 9.8,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23010",
	            "maxCondition": 1,
	            "maxConditionValue": 1,
	            "effects": [
	                {
	                    "type": 205,
	                    "formulaValue": 30.177
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 2,
	                    "target": 0,
	                    "formulaValue": 0
	                }
	            ]
	        },
	        {
	            "id": 19,
	            "name": "21019",
	            "description": "22204",
	            "level": 1,
	            "slot": 5,
	            "energy": [
	                0,
	                0,
	                6,
	                0
	            ],
	            "cooldown": 2,
	            "duration": 15.7,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23024",
	            "maxCondition": 23,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 204,
	                    "formulaValue": 32.046
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 103,
	                    "target": 1,
	                    "formulaValue": 8.739999999999998
	                }
	            ]
	        },
	        {
	            "id": 31,
	            "name": "21031",
	            "description": "22201",
	            "level": 1,
	            "slot": 0,
	            "energy": [
	                2,
	                1,
	                0,
	                0
	            ],
	            "cooldown": 2,
	            "duration": 7.2,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23030",
	            "maxCondition": 41,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 201,
	                    "formulaValue": 37.086
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 402,
	                    "target": 0,
	                    "formulaValue": 0.99
	                }
	            ]
	        }
	    ]
	}