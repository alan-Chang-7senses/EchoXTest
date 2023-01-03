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
| id | int | 賽局編號 |
| [scene](#scene) | object | 場景資訊 |
| [users](#users2) | array | 各玩家的角色競賽資料陣列 |
| availableUsers | array | 可開局的使用者編號集合 |
| notAvailableUsers | array | 不可開局的使用者編號集合 |

#### <span id="scene">scene 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
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
| energyRatio | array | 依據技能能量所佔比例的能量陣列，依序為 紅,黃,藍,綠 |
| energyRandom | array | 隨機生成的能量陣列，依序為 紅,黃,藍,綠 |
| energyTotal | array | 總能量陣列，依序為 紅,黃,藍,綠 |
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
| icon | string | 技能Icon代號 |
| level | int | 技能等級 |
| slot | int | 技能所在插槽 |
| energy | array | 使用條件能量值（int），陣列元素依序為 紅、黃、藍、綠 |
| cooldown | float | 冷卻時間（秒） |
| duration | float | 時效性<br>0 = 單次效果<br>大於 0 = 時效秒數<br>-1 = 持續到比賽結束 |
| maxCondition | int | [滿星效果條件](../codes/skill.md#maxCondition) |
| maxConditionValue | int | 滿星效果條件值 |
| [effects](#effects) | array | 技能效果陣列 |
| [maxEffects](#maxEffects) | array | 滿星技能效果陣列 |


##### <span id="effects">effects 技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [效果類型](../codes/skill.md#effectType) |

##### <span id="maxEffects">maxEffects 滿星技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [滿星效果類型](../codes/skill.md#maxEffectType) |
| target | int | [作用對象](../codes/skill.md#target) |

### Example
	{
	  "error": {
	    "code": 0,
	    "message": ""
	  },
	  "id": 403,
	  "scene": {
	    "env": 1,
	    "weather": 1,
	    "windDirection": 2,
	    "windSpeed": 25,
	    "lighting": 1
	  },
	  "users": [
	    {
	      "id": 3,
	      "player": 303,
	      "energyRatio": [
	        4,
	        4,
	        1,
	        1
	      ],
	      "energyRandom": [
	        2,
	        1,
	        1,
	        2
	      ],
	      "energyTotal": [
	        6,
	        5,
	        2,
	        3
	      ],
	      "hp": 70.33,
	      "s": 7.726929400000001,
	      "h": 0.5495708460626741,
	      "startSec": 0.046760000428318294,
	      "skills": [
	        {
	          "id": 14,
	          "name": "21076",
	          "icon": "Skill_Icon_0009",
	          "level": 1,
	          "slot": 1,
	          "energy": [
	            0,
	            0,
	            1,
	            1
	          ],
	          "cooldown": 2,
	          "duration": 5,
	          "maxCondition": 62,
	          "maxConditionValue": 0,
	          "effects": [
	            {
	              "type": 115
	            },
	            {
	              "type": 201
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 111,
	              "target": 0
	            }
	          ]
	        },
	        {
	          "id": 21,
	          "name": "21091",
	          "icon": "Skill_Icon_0003",
	          "level": 1,
	          "slot": 3,
	          "energy": [
	            2,
	            1,
	            0,
	            0
	          ],
	          "cooldown": 2,
	          "duration": 7.2,
	          "maxCondition": 51,
	          "maxConditionValue": 0,
	          "effects": [
	            {
	              "type": 112
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 213,
	              "target": 0
	            }
	          ]
	        },
	        {
	          "id": 24,
	          "name": "21092",
	          "icon": "Skill_Icon_0001",
	          "level": 1,
	          "slot": 2,
	          "energy": [
	            0,
	            3,
	            0,
	            0
	          ],
	          "cooldown": 2,
	          "duration": 7.2,
	          "maxCondition": 12,
	          "maxConditionValue": 0,
	          "effects": [
	            {
	              "type": 111
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 214,
	              "target": 0
	            }
	          ]
	        },
	        {
	          "id": 76,
	          "name": "21138",
	          "icon": "Skill_Icon_0003",
	          "level": 1,
	          "slot": 4,
	          "energy": [
	            3,
	            0,
	            0,
	            0
	          ],
	          "cooldown": 2,
	          "duration": 7.2,
	          "maxCondition": 72,
	          "maxConditionValue": 0,
	          "effects": [
	            {
	              "type": 112
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 102,
	              "target": 0
	            }
	          ]
	        }
	      ]
	    },
	    {
	      "id": 6,
	      "player": 604,
	      "energyRatio": [
	        2,
	        3,
	        2,
	        3
	      ],
	      "energyRandom": [
	        0,
	        4,
	        2,
	        1
	      ],
	      "energyTotal": [
	        2,
	        7,
	        4,
	        4
	      ],
	      "hp": 68.04,
	      "s": 8.151960160000002,
	      "h": 0.44467148683392344,
	      "startSec": 0.046039076697250714,
	      "skills": [
	        {
	          "id": 126,
	          "name": "21208",
	          "icon": "Skill_Icon_0001",
	          "level": 1,
	          "slot": 2,
	          "energy": [
	            0,
	            1,
	            0,
	            1
	          ],
	          "cooldown": 2,
	          "duration": 5,
	          "maxCondition": 5,
	          "maxConditionValue": 1,
	          "effects": [
	            {
	              "type": 111
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 113,
	              "target": 0
	            }
	          ]
	        },
	        {
	          "id": 165,
	          "name": "21229",
	          "icon": "Skill_Icon_0001",
	          "level": 1,
	          "slot": 3,
	          "energy": [
	            0,
	            4,
	            0,
	            0
	          ],
	          "cooldown": 2,
	          "duration": 9.6,
	          "maxCondition": 12,
	          "maxConditionValue": 0,
	          "effects": [
	            {
	              "type": 111
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 111,
	              "target": 3
	            }
	          ]
	        },
	        {
	          "id": 180,
	          "name": "21244",
	          "icon": "Skill_Icon_0007",
	          "level": 1,
	          "slot": 4,
	          "energy": [
	            0,
	            0,
	            4,
	            0
	          ],
	          "cooldown": 2,
	          "duration": 4.8,
	          "maxCondition": 5,
	          "maxConditionValue": 1,
	          "effects": [
	            {
	              "type": 114
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 121,
	              "target": 0
	            }
	          ]
	        },
	        {
	          "id": 184,
	          "name": "21248",
	          "icon": "Skill_Icon_0009",
	          "level": 1,
	          "slot": 5,
	          "energy": [
	            1,
	            0,
	            0,
	            2
	          ],
	          "cooldown": 2,
	          "duration": 7.2,
	          "maxCondition": 41,
	          "maxConditionValue": 0,
	          "effects": [
	            {
	              "type": 115
	            },
	            {
	              "type": 201
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 112,
	              "target": 0
	            }
	          ]
	        },
	        {
	          "id": 191,
	          "name": "21255",
	          "icon": "Skill_Icon_0009",
	          "level": 1,
	          "slot": 1,
	          "energy": [
	            2,
	            0,
	            0,
	            2
	          ],
	          "cooldown": 2,
	          "duration": 9.8,
	          "maxCondition": 43,
	          "maxConditionValue": 0,
	          "effects": [
	            {
	              "type": 115
	            },
	            {
	              "type": 201
	            }
	          ],
	          "maxEffects": [
	            {
	              "type": 102,
	              "target": 0
	            }
	          ]
	        }
	      ]
	    }
	  ],
	  "availableUsers": [
	    3,
	    6
	  ],
	  "notAvailableUsers": [
	
	  ]
	}