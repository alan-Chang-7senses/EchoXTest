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
| formulaValue | float | 效果值 |

##### <span id="maxEffects">maxEffects 滿星技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [滿星效果類型](../codes/skill.md#maxEffectType) |
| target | int | [作用對象](../codes/skill.md#target) |
| typeValue | int | [滿星效果類型值](../codes/skill.md#maxEffectType) |
| formulaValue | float | 效果值**（未實作計算，固定傳回 0）** |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "skills": [
	        {
	            "id": 10,
	            "name": "21004",
	            "description": "22201",
	            "level": 1,
	            "slot": 1,
	            "energy": [
	                0,
	                1,
	                1,
	                2
	            ],
	            "cooldown": 4,
	            "duration": 7.8,
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
	                    "formulaValue": 38.852
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 402,
	                    "target": 0,
	                    "typeValue": 0,
	                    "formulaValue": 0
	                }
	            ]
	        },
	        {
	            "id": 11,
	            "name": "21005",
	            "description": "22203",
	            "level": 1,
	            "slot": 2,
	            "energy": [
	                1,
	                0,
	                1,
	                1
	            ],
	            "cooldown": 3,
	            "duration": 5.4,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23012",
	            "maxCondition": 33,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 203,
	                    "formulaValue": 33.495
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 402,
	                    "target": 0,
	                    "typeValue": 0,
	                    "formulaValue": 0
	                }
	            ]
	        },
	        {
	            "id": 12,
	            "name": "21006",
	            "description": "22204",
	            "level": 1,
	            "slot": 3,
	            "energy": [
	                1,
	                2,
	                1,
	                0
	            ],
	            "cooldown": 4,
	            "duration": 7.8,
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
	                    "type": 204,
	                    "formulaValue": 33.572
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 10,
	                    "target": 0,
	                    "typeValue": 1,
	                    "formulaValue": 0
	                }
	            ]
	        },
	        {
	            "id": 20,
	            "name": "21014",
	            "description": "22205",
	            "level": 1,
	            "slot": 4,
	            "energy": [
	                1,
	                2,
	                0,
	                1
	            ],
	            "cooldown": 4,
	            "duration": 7.8,
	            "ranks": [
	                5,
	                10,
	                15,
	                20,
	                25
	            ],
	            "maxDescription": "23009",
	            "maxCondition": 1,
	            "maxConditionValue": 1,
	            "effects": [
	                {
	                    "type": 205,
	                    "formulaValue": 31.614
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 2,
	                    "target": 0,
	                    "typeValue": 2,
	                    "formulaValue": 0
	                }
	            ]
	        },
	        {
	            "id": 25,
	            "name": "21019",
	            "description": "22204",
	            "level": 1,
	            "slot": 5,
	            "energy": [
	                2,
	                1,
	                1,
	                2
	            ],
	            "cooldown": 6,
	            "duration": 13.5,
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
	                    "formulaValue": 33.572
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 401,
	                    "target": 1,
	                    "typeValue": 0,
	                    "formulaValue": 0
	                }
	            ]
	        },
	        {
	            "id": 37,
	            "name": "21031",
	            "description": "22201",
	            "level": 1,
	            "slot": 0,
	            "energy": [
	                1,
	                1,
	                1,
	                0
	            ],
	            "cooldown": 3,
	            "duration": 5.4,
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
	                    "formulaValue": 38.852
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 402,
	                    "target": 0,
	                    "typeValue": 0,
	                    "formulaValue": 0
	                }
	            ]
	        }
	    ]
	}