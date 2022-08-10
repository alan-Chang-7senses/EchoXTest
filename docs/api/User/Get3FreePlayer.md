# 使用者 - 隨機三隻免費Peta

## 介紹

- 用於在免費Peta三選一中，取得隨機三隻免費Peta的資訊。

## URL

http(s)://`域名`/User/FreePlayer/Get3FreePlayer/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數
無

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [players](#players) | object | 隨機三隻角色資訊 |

#### <span id="players">players 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| number | int | 在三隻角色中的編號 |
| type | int | [數值特性](#type) |
| ele | int | [屬性](../codes/player.md#attr) |
| rank | int | 階級 |
| velocity | float | 速度 |
| stamina | float | 耐力 |
| intelligent | float | 聰慧 |
| breakOut | float | 爆發 |
| will | float | 鬥志 |
| habit | int | [比賽習慣](../codes/player.md#habit) |
| dna | array | 各部位dna前六碼編碼 |
| [skills](#skills) | array | 角色持有的技能清單陣列 |

#### <span id="type">type 內容</span>
| 編碼 | 定義 |
|:-:|:-:|
| 0 | 速度型 |
| 1 | 平衡型 |
| 2 | 持久型 |


#### <span id="skills">skills 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能編號 |
| name | string | 技能名稱代號 |
| icon | int | 技能Icon代號 |
| description | string | 技能敘述代號 |
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
		"players": [
			{
				"number": 1,
				"type": 0,
				"ele": 1,
				"velocity": 44.01,
				"stamina": 36.71,
				"intelligent": 43.41,
				"breakOut": 42.21,
				"will": 36.71,
				"habit": 3,
				"dna": {
					"head": "140301",
					"body": "130302",
					"hand": "140105",
					"leg": "110208",
					"back": "110208",
					"hat": "110106"
				},
				"skills": [
					{
						"id": 2,
						"name": "21002",
						"description": "22205",
						"energy": [
							2,
							0,
							2,
							0
						],
						"cooldowm": 2,
						"duration": 9.8,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23008",
						"maxCondition": 4,
						"maxConditionValue": 1,
						"effects": [
							{
								"type": 115
							}
						],
						"maxEffects": [
							{
								"type": 504,
								"target": 0
							}
						]
					},
					{
						"id": 6,
						"name": "21006",
						"description": "22202",
						"energy": [
							1,
							3,
							0,
							0
						],
						"cooldowm": 2,
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
								"type": 112
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
						"id": 13,
						"name": "21013",
						"description": "22204",
						"energy": [
							2,
							0,
							0,
							0
						],
						"cooldowm": 2,
						"duration": 5,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23021",
						"maxCondition": 23,
						"maxConditionValue": 0,
						"effects": [
							{
								"type": 114
							}
						],
						"maxEffects": [
							{
								"type": 143,
								"target": 0
							}
						]
					},
					{
						"id": 16,
						"name": "21016",
						"description": "22201",
						"energy": [
							1,
							0,
							1,
							0
						],
						"cooldowm": 2,
						"duration": 5,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23013",
						"maxCondition": 12,
						"maxConditionValue": 0,
						"effects": [
							{
								"type": 111
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
						"id": 17,
						"name": "21017",
						"description": "22203",
						"energy": [
							0,
							0,
							0,
							1
						],
						"cooldowm": 2,
						"duration": 3,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23023",
						"maxCondition": 2,
						"maxConditionValue": 3,
						"effects": [
							{
								"type": 113
							}
						],
						"maxEffects": [
							{
								"type": 101,
								"target": 0
							}
						]
					},
					{
						"id": 59,
						"name": "21059",
						"description": "22205",
						"energy": [
							0,
							3,
							1,
							0
						],
						"cooldowm": 2,
						"duration": 6.1,
						"ranks": [
							8,
							16,
							24,
							32,
							40
						],
						"maxDescription": "23050",
						"maxCondition": 22,
						"maxConditionValue": 0,
						"effects": [
							{
								"type": 115
							}
						],
						"maxEffects": [
							{
								"type": 123,
								"target": 0
							}
						]
					}
				]
			},
			{
				"number": 2,
				"type": 1,
				"ele": 1,
				"velocity": 43.81,
				"stamina": 38.81,
				"intelligent": 42.61,
				"breakOut": 39.31,
				"will": 38.51,
				"habit": 3,
				"dna": {
					"head": "130303",
					"body": "140204",
					"hand": "110208",
					"leg": "140301",
					"back": "110106",
					"hat": "130301"
				},
				"skills": [
					{
						"id": 5,
						"name": "21005",
						"description": "22203",
						"energy": [
							2,
							0,
							1,
							0
						],
						"cooldowm": 2,
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
								"type": 113
							}
						],
						"maxEffects": [
							{
								"type": 102,
								"target": 0
							}
						]
					},
					{
						"id": 7,
						"name": "21007",
						"description": "22204",
						"energy": [
							1,
							0,
							0,
							0
						],
						"cooldowm": 2,
						"duration": -1,
						"ranks": [
							1,
							1,
							2,
							2,
							3
						],
						"maxDescription": "23014",
						"maxCondition": 32,
						"maxConditionValue": 0,
						"effects": [
							{
								"type": 114
							}
						],
						"maxEffects": [
							{
								"type": 102,
								"target": 0
							}
						]
					},
					{
						"id": 15,
						"name": "21015",
						"description": "22202",
						"energy": [
							0,
							1,
							0,
							0
						],
						"cooldowm": 2,
						"duration": 3,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23022",
						"maxCondition": 11,
						"maxConditionValue": 0,
						"effects": [
							{
								"type": 112
							}
						],
						"maxEffects": [
							{
								"type": 101,
								"target": 0
							}
						]
					},
					{
						"id": 16,
						"name": "21016",
						"description": "22201",
						"energy": [
							1,
							0,
							1,
							0
						],
						"cooldowm": 2,
						"duration": 5,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23013",
						"maxCondition": 12,
						"maxConditionValue": 0,
						"effects": [
							{
								"type": 111
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
						"id": 52,
						"name": "21052",
						"description": "22205",
						"energy": [
							2,
							0,
							0,
							0
						],
						"cooldowm": 2,
						"duration": 9.8,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23013",
						"maxCondition": 4,
						"maxConditionValue": 1,
						"effects": [
							{
								"type": 115
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
						"id": 62,
						"name": "21062",
						"description": "22202",
						"energy": [
							0,
							0,
							3,
							0
						],
						"cooldowm": 2,
						"duration": 4.5,
						"ranks": [
							8,
							16,
							24,
							32,
							40
						],
						"maxDescription": "23007",
						"maxCondition": 22,
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
				"number": 3,
				"type": 2,
				"ele": 1,
				"velocity": 37.21,
				"stamina": 44.51,
				"intelligent": 40.21,
				"breakOut": 39.01,
				"will": 42.11,
				"habit": 2,
				"dna": {
					"head": "130302",
					"body": "130301",
					"hand": "110106",
					"leg": "110208",
					"back": "140105",
					"hat": "130302"
				},
				"skills": [
					{
						"id": 1,
						"name": "21001",
						"description": "22204",
						"energy": [
							0,
							2,
							0,
							1
						],
						"cooldowm": 2,
						"duration": 7.2,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23007",
						"maxCondition": 2,
						"maxConditionValue": 3,
						"effects": [
							{
								"type": 114
							}
						],
						"maxEffects": [
							{
								"type": 102,
								"target": 0
							}
						]
					},
					{
						"id": 3,
						"name": "21003",
						"description": "22202",
						"energy": [
							0,
							0,
							0,
							3
						],
						"cooldowm": 2,
						"duration": 7.2,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23011",
						"maxCondition": 1,
						"maxConditionValue": 1,
						"effects": [
							{
								"type": 112
							}
						],
						"maxEffects": [
							{
								"type": 141,
								"target": 0
							}
						]
					},
					{
						"id": 6,
						"name": "21006",
						"description": "22202",
						"energy": [
							1,
							3,
							0,
							0
						],
						"cooldowm": 2,
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
								"type": 112
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
						"id": 16,
						"name": "21016",
						"description": "22201",
						"energy": [
							1,
							0,
							1,
							0
						],
						"cooldowm": 2,
						"duration": 5,
						"ranks": [
							5,
							10,
							15,
							20,
							25
						],
						"maxDescription": "23013",
						"maxCondition": 12,
						"maxConditionValue": 0,
						"effects": [
							{
								"type": 111
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
						"id": 58,
						"name": "21058",
						"description": "22204",
						"energy": [
							0,
							2,
							0,
							2
						],
						"cooldowm": 2,
						"duration": 6.1,
						"ranks": [
							8,
							16,
							24,
							32,
							40
						],
						"maxDescription": "23010",
						"maxCondition": 4,
						"maxConditionValue": 1,
						"effects": [
							{
								"type": 114
							}
						],
						"maxEffects": [
							{
								"type": 506,
								"target": 0
							}
						]
					},
					{
						"id": 61,
						"name": "21061",
						"description": "22202",
						"energy": [
							1,
							0,
							0,
							2
						],
						"cooldowm": 2,
						"duration": 4.5,
						"ranks": [
							8,
							16,
							24,
							32,
							40
						],
						"maxDescription": "23012",
						"maxCondition": 5,
						"maxConditionValue": 1,
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
			}
		]
	}