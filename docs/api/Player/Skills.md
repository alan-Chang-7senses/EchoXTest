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
| duration | int | 0 為單次效果，大於 0 為時效秒數，-1 為持續到比賽結束。 |
| formulaValue | float | 效果值**（未實作計算，固定傳回 0）** |

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
	            "id": 1,
	            "name": "21001",
	            "description": "22002",
	            "level": 1,
	            "slot": 2,
	            "energy": [
	                "0",
	                "0",
	                "2",
	                "1"
	            ],
	            "cooldown": 6.5,
	            "maxDescription": "23001",
	            "maxCondition": 11,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 102,
	                    "duration": -1,
	                    "formulaValue": 0
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 1,
	                    "target": 0,
	                    "typeValue": 1,
	                    "formulaValue": 0
	                }
	            ]
	        },
	        {
	            "id": 2,
	            "name": "21002",
	            "description": "22001",
	            "level": 2,
	            "slot": 4,
	            "energy": [
	                "1",
	                "1",
	                "1",
	                "1"
	            ],
	            "cooldown": 8,
	            "maxDescription": "23002",
	            "maxCondition": 22,
	            "maxConditionValue": 0,
	            "effects": [
	                {
	                    "type": 101,
	                    "duration": -1,
	                    "formulaValue": 0
	                }
	            ],
	            "maxEffects": [
	                {
	                    "type": 12,
	                    "target": 0,
	                    "typeValue": 1,
	                    "formulaValue": 0
	                }
	            ]
	        }
	    ]
	}