# 競賽 - 斷線恢復

## 介紹

- 當玩家於競賽中斷線，恢復連線進入競賽可透過此功能獲取競賽場中的場景與角色相關資料。

## URL

http(s)://`域名`/Races/OfflineRecovery/

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
| user | int | 使用者編號 |
| player | int | 角色編號 |
| [scene](#scene) | object | 場景資訊 |
| [players](#players) | array | 各玩家的角色競賽資料陣列 |
| [recoveryDataArray](#recoveryDataArray) | array | 斷線恢復額外儲存的資料 |

#### <span id="scene">scene 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| env | int | [環境](../codes/scene.md#env) |
| weather | int | [天氣](../codes/scene.md#weather) |
| windDirection | int | [風向](../codes/scene.md#windDirection) |
| windSpeed | int | 風速 |
| lighting | int | [照明（明暗）](../codes/scene.md#lighting) |

#### <span id="players"> players 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| user | int | 使用者編號 |
| player | int | 角色編號 |
| energy | array | 能量陣列，依序為 紅,黃,藍,綠 |
| hp | float | 剩餘耐力 |
| s | float | S值 |
| h | float | H值 |
| position | string | 所在位置，若未紀錄過位置則為 null |
| [parts](#parts) | object | 角色各部位的 Avatar 編號 |
| [skills](#skills) | array | 角色裝備的技能清單陣列 |

#### <span id="parts"> parts 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| head | string | 頭部代碼 |
| body | string | 身體代碼 |
| hand | string | 手部代碼 |
| leg | string | 腿部代碼 |
| back | string | 背部代碼 |
| hat | string | 頭冠代碼 |

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


##### <span id="recoveryDataArray">recoveryDataArray  內容</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| raceID| int | 賽局編號
| countDown| float | 倒數時間
| runTime| float | 比賽時間
| playerID| int | 角色編號
| moveDistance| int | 移動距離
| [skill](#skill) | array | 技能資料
| createTime| int | 建立時間

##### <span id="skill">skill  內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| skillID | int | 技能編號
| skillCoolTime | int | 技能冷卻時間
| normalSkillTime | int | 一般技能持續時間
| fullLVSkillTime | int | 滿星技能持續時間


### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "user": 1,
	    "player": 1010000000000001,
	    "scene": {
	        "env": 1,
	        "weather": 1,
	        "windDirection": 2,
	        "windSpeed": 50,
	        "ligthing": 1
	    },
	    "players": [
	        {
	            "user": 1,
	            "player": 1010000000000001,
	            "energy": [
	                5,
	                6,
	                3,
	                4
	            ],
	            "hp": 34.43,
	            "s": 1.0616959999999998,
	            "h": 0.966642792109256,
	            "position": "(-313.2, -96.5, -84.6)",
	            "parts": {
	                "head": "110101",
	                "body": "110101",
	                "hand": "110101",
	                "leg": "110101",
	                "back": "110101",
	                "hat": "110101"
	            },
	            "skills": [
	                {
	                    "id": 1,
	                    "name": "21001",
	                    "level": 1,
	                    "slot": 1,
	                    "energy": [
	                        0,
	                        2,
	                        0,
	                        1
	                    ],
	                    "cooldown": 2,
	                    "duration": 7.2,
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
	                    "id": 2,
	                    "name": "21002",
	                    "level": 1,
	                    "slot": 2,
	                    "energy": [
	                        2,
	                        0,
	                        2,
	                        0
	                    ],
	                    "cooldown": 2,
	                    "duration": 9.8,
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
	                    "id": 3,
	                    "name": "21003",
	                    "level": 1,
	                    "slot": 3,
	                    "energy": [
	                        0,
	                        0,
	                        0,
	                        3
	                    ],
	                    "cooldown": 2,
	                    "duration": 7.2,
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
	                    "id": 4,
	                    "name": "21004",
	                    "level": 1,
	                    "slot": 4,
	                    "energy": [
	                        0,
	                        0,
	                        2,
	                        2
	                    ],
	                    "cooldown": 2,
	                    "duration": 9.8,
	                    "maxCondition": 41,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 111
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
	                    "id": 5,
	                    "name": "21005",
	                    "level": 1,
	                    "slot": 5,
	                    "energy": [
	                        2,
	                        0,
	                        1,
	                        0
	                    ],
	                    "cooldown": 2,
	                    "duration": 7.2,
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
	                    "id": 6,
	                    "name": "21006",
	                    "level": 1,
	                    "slot": 6,
	                    "energy": [
	                        1,
	                        3,
	                        0,
	                        0
	                    ],
	                    "cooldown": 2,
	                    "duration": 9.8,
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
	                }
	            ]
	        },
	        {
	            "user": -38,
	            "player": -38,
	            "energy": [
	                7,
	                2,
	                6,
	                3
	            ],
	            "hp": 28.14,
	            "s": 0.755466666666667,
	            "h": 0.6483579356905826,
	            "position": "(-327.0, -96.5, -47.4)",
	            "parts": {
	                "head": "110102",
	                "body": "110102",
	                "hand": "110101",
	                "leg": "110101",
	                "back": "110101",
	                "hat": "110103"
	            },
	            "skills": [
	                {
	                    "id": 3,
	                    "name": "21003",
	                    "level": 1,
	                    "slot": 1,
	                    "energy": [
	                        0,
	                        0,
	                        0,
	                        3
	                    ],
	                    "cooldown": 2,
	                    "duration": 7.2,
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
	                    "id": 4,
	                    "name": "21004",
	                    "level": 1,
	                    "slot": 2,
	                    "energy": [
	                        0,
	                        0,
	                        2,
	                        2
	                    ],
	                    "cooldown": 2,
	                    "duration": 9.8,
	                    "maxCondition": 41,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 111
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
	                    "id": 5,
	                    "name": "21005",
	                    "level": 1,
	                    "slot": 3,
	                    "energy": [
	                        2,
	                        0,
	                        1,
	                        0
	                    ],
	                    "cooldown": 2,
	                    "duration": 7.2,
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
	                    "level": 1,
	                    "slot": 4,
	                    "energy": [
	                        1,
	                        0,
	                        0,
	                        0
	                    ],
	                    "cooldown": 2,
	                    "duration": -1,
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
	                    "id": 8,
	                    "name": "21008",
	                    "level": 1,
	                    "slot": 5,
	                    "energy": [
	                        0,
	                        0,
	                        0,
	                        2
	                    ],
	                    "cooldown": 2,
	                    "duration": -1,
	                    "maxCondition": 2,
	                    "maxConditionValue": 3,
	                    "effects": [
	                        {
	                            "type": 115
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
	                    "id": 18,
	                    "name": "21018",
	                    "level": 1,
	                    "slot": 6,
	                    "energy": [
	                        0,
	                        0,
	                        2,
	                        0
	                    ],
	                    "cooldown": 2,
	                    "duration": 5,
	                    "maxCondition": 43,
	                    "maxConditionValue": 0,
	                    "effects": [
	                        {
	                            "type": 114
	                        }
	                    ],
	                    "maxEffects": [
	                        {
	                            "type": 101,
	                            "target": 0
	                        }
	                    ]
	                }
	            ]
	        }
	    ],
    "recoveryDataArray": {
        "raceID": 1,
        "countDown": 0,
        "runTime": 10,
        "playerID": 1010000000000001,
        "moveDistance": 250,
        "skillData": [
					{
						"skillID": 101101,
						"skillCoolTime": 5,
						"normalSkillTime": 5,
						"fullLVSkillTime": 10
					},
					{
						"skillID": 101101,
						"skillCoolTime": 5,
						"normalSkillTime": 5,
						"fullLVSkillTime": 10
					},
					{
						"skillID": 101101,
						"skillCoolTime": 5,
						"normalSkillTime": 5,
						"fullLVSkillTime": 10
					},
					{
						"skillID": 101101,
						"skillCoolTime": 5,
						"normalSkillTime": 5,
						"fullLVSkillTime": 10
					},
					{
						"skillID": 101101,
						"skillCoolTime": 5,
						"normalSkillTime": 5,
						"fullLVSkillTime": 10
					},
					{
						"skillID": 0,
						"skillCoolTime": 0,
						"normalSkillTime": 0,
						"fullLVSkillTime": 0
					}
                ]
            }
        ],
        "createTime": 1655961018
    }
	}