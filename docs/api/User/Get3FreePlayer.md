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
| icon | string | 技能Icon代號 |
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
| formulaValue | float | 公式計算結果的效果值 |
| allRankFormulaValue | array | 各等級公式計算結果的效果值 |

##### <span id="maxEffects">maxEffects 滿星技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [滿星效果類型](../codes/skill.md#maxEffectType) |
| target | int | [作用對象](../codes/skill.md#target) |
| formulaValue | float | 公式計算結果的效果值 |
| allRankFormulaValue | array | 各等級公式計算結果的效果值 |
### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "players": [
            {
                "number": "1",
                "type": 0,
                "ele": 1,
                "velocity": 56.08,
                "stamina": 46.92,
                "intelligent": 50.72,
                "breakOut": 66.65,
                "will": 55.8,
                "habit": 1,
                "dna": {
                    "head": "110207",
                    "body": "110211",
                    "hand": "130302",
                    "leg": "110110",
                    "back": "110106",
                    "hat": "110207"
                },
                "skills": [
                    {
                        "id": 7,
                        "name": "21069",
                        "icon": "Skill_Icon_0007",
                        "description": "22204",
                        "energy": [
                            3,
                            0,
                            2,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 12.2,
                        "ranks": [
                            5,
                            10,
                            15,
                            20,
                            25
                        ],
                        "maxDescription": "22201",
                        "maxCondition": 71,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 114,
                                "formulaValue": 2.536,
                                "allRankFormulaValue": [
                                    2.536,
                                    5.072,
                                    7.608,
                                    10.144,
                                    12.68
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 102,
                                "target": 0,
                                "formulaValue": 0.39599999999999996,
                                "allRankFormulaValue": [
                                    0.39599999999999996,
                                    0.7919999999999999,
                                    1.188,
                                    1.5839999999999999,
                                    1.9799999999999998
                                ]
                            }
                        ]
                    },
                    {
                        "id": 32,
                        "name": "21094",
                        "icon": "Skill_Icon_0009",
                        "description": "22205",
                        "energy": [
                            0,
                            0,
                            0,
                            2
                        ],
                        "cooldowm": 2,
                        "duration": 2.5,
                        "ranks": [
                            4,
                            8,
                            12,
                            16,
                            20
                        ],
                        "maxDescription": "22201",
                        "maxCondition": 71,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 115,
                                "formulaValue": 1.8768,
                                "allRankFormulaValue": [
                                    1.8768,
                                    3.7536,
                                    5.6304,
                                    7.5072,
                                    9.384
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 102,
                                "target": 0,
                                "formulaValue": 0.31679999999999997,
                                "allRankFormulaValue": [
                                    0.31679999999999997,
                                    0.6335999999999999,
                                    0.9503999999999999,
                                    1.2671999999999999,
                                    1.5839999999999999
                                ]
                            }
                        ]
                    },
                    {
                        "id": 81,
                        "name": "21143",
                        "icon": "Skill_Icon_0009",
                        "description": "22205",
                        "energy": [
                            0,
                            0,
                            0,
                            1
                        ],
                        "cooldowm": 2,
                        "duration": 3,
                        "ranks": [
                            1,
                            2,
                            3,
                            4,
                            5
                        ],
                        "maxDescription": "22001",
                        "maxCondition": 1,
                        "maxConditionValue": 2,
                        "effects": [
                            {
                                "type": 115,
                                "formulaValue": 0.4692,
                                "allRankFormulaValue": [
                                    0.4692,
                                    0.9384,
                                    1.4076,
                                    1.8768,
                                    2.346
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 101,
                                "target": 0,
                                "formulaValue": -0.00858,
                                "allRankFormulaValue": [
                                    -0.00858,
                                    -0.01716,
                                    -0.025740000000000002,
                                    -0.03432,
                                    -0.04290000000000001
                                ]
                            }
                        ]
                    },
                    {
                        "id": 22,
                        "name": "21084",
                        "icon": "Skill_Icon_0001",
                        "description": "22206",
                        "energy": [
                            0,
                            3,
                            0,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 7.2,
                        "ranks": [
                            3,
                            6,
                            9,
                            12,
                            15
                        ],
                        "maxDescription": "22240",
                        "maxCondition": 21,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 111,
                                "formulaValue": 1.9995000000000003,
                                "allRankFormulaValue": [
                                    1.9995000000000003,
                                    3.9990000000000006,
                                    5.9985,
                                    7.998000000000001,
                                    9.9975
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 212,
                                "target": 0,
                                "formulaValue": 1,
                                "allRankFormulaValue": [
                                    1,
                                    1,
                                    1,
                                    1,
                                    1
                                ]
                            }
                        ]
                    },
                    {
                        "id": 5,
                        "name": "21067",
                        "icon": "Skill_Icon_0006",
                        "description": "22203",
                        "energy": [
                            0,
                            0,
                            0,
                            3
                        ],
                        "cooldowm": 2,
                        "duration": -1,
                        "ranks": [
                            0.6,
                            1.2,
                            1.8,
                            2.4,
                            3
                        ],
                        "maxDescription": "23095",
                        "maxCondition": 31,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 113,
                                "formulaValue": 0.3348,
                                "allRankFormulaValue": [
                                    0.3348,
                                    0.6696,
                                    1.0044,
                                    1.3392,
                                    1.6739999999999997
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 201,
                                "target": 3,
                                "formulaValue": -0.6,
                                "allRankFormulaValue": [
                                    -0.6,
                                    -1.2,
                                    -1.8,
                                    -2.4,
                                    -3
                                ]
                            }
                        ]
                    },
                    {
                        "id": 12,
                        "name": "21074",
                        "icon": "Skill_Icon_0003",
                        "description": "22202",
                        "energy": [
                            1,
                            0,
                            3,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 7.2,
                        "ranks": [
                            3,
                            6,
                            9,
                            12,
                            15
                        ],
                        "maxDescription": "22250",
                        "maxCondition": 51,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 112,
                                "formulaValue": 1.9995000000000003,
                                "allRankFormulaValue": [
                                    1.9995000000000003,
                                    3.9990000000000006,
                                    5.9985,
                                    7.998000000000001,
                                    9.9975
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 161,
                                "target": 0,
                                "formulaValue": 4.800000000000001,
                                "allRankFormulaValue": [
                                    4.800000000000001,
                                    9.600000000000001,
                                    14.4,
                                    19.200000000000003,
                                    24
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                "number": "2",
                "type": 1,
                "ele": 1,
                "velocity": 53.54,
                "stamina": 56.92,
                "intelligent": 53.54,
                "breakOut": 54.81,
                "will": 57.35,
                "habit": 2,
                "dna": {
                    "head": "110211",
                    "body": "130302",
                    "hand": "110208",
                    "leg": "140102",
                    "back": "140303",
                    "hat": "140303"
                },
                "skills": [
                    {
                        "id": 31,
                        "name": "21093",
                        "icon": "Skill_Icon_0007",
                        "description": "22204",
                        "energy": [
                            0,
                            0,
                            2,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 2.5,
                        "ranks": [
                            4,
                            8,
                            12,
                            16,
                            20
                        ],
                        "maxDescription": "22224",
                        "maxCondition": 42,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 114,
                                "formulaValue": 2.1416,
                                "allRankFormulaValue": [
                                    2.1416,
                                    4.2832,
                                    6.4248,
                                    8.5664,
                                    10.708
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 153,
                                "target": 0,
                                "formulaValue": 5.2,
                                "allRankFormulaValue": [
                                    5.2,
                                    10.4,
                                    15.600000000000001,
                                    20.8,
                                    26
                                ]
                            }
                        ]
                    },
                    {
                        "id": 80,
                        "name": "21142",
                        "icon": "Skill_Icon_0005",
                        "description": "22203",
                        "energy": [
                            1,
                            0,
                            0,
                            1
                        ],
                        "cooldowm": 2,
                        "duration": 5,
                        "ranks": [
                            2,
                            4,
                            6,
                            8,
                            10
                        ],
                        "maxDescription": "22202",
                        "maxCondition": 22,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 113,
                                "formulaValue": 1.147,
                                "allRankFormulaValue": [
                                    1.147,
                                    2.294,
                                    3.4410000000000003,
                                    4.588,
                                    5.735
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 112,
                                "target": 0,
                                "formulaValue": 2.1924,
                                "allRankFormulaValue": [
                                    2.1924,
                                    4.3848,
                                    6.5772,
                                    8.7696,
                                    10.962
                                ]
                            }
                        ]
                    },
                    {
                        "id": 15,
                        "name": "21077",
                        "icon": "Skill_Icon_0003",
                        "description": "22202",
                        "energy": [
                            2,
                            0,
                            0,
                            2
                        ],
                        "cooldowm": 2,
                        "duration": 9.6,
                        "ranks": [
                            4,
                            8,
                            12,
                            16,
                            20
                        ],
                        "maxDescription": "22203",
                        "maxCondition": 33,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 112,
                                "formulaValue": 2.1924,
                                "allRankFormulaValue": [
                                    2.1924,
                                    4.3848,
                                    6.5772,
                                    8.7696,
                                    10.962
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 113,
                                "target": 0,
                                "formulaValue": 4.588,
                                "allRankFormulaValue": [
                                    4.588,
                                    9.176,
                                    13.764,
                                    18.352,
                                    22.94
                                ]
                            }
                        ]
                    },
                    {
                        "id": 46,
                        "name": "21108",
                        "icon": "Skill_Icon_0005",
                        "description": "22203",
                        "energy": [
                            1,
                            0,
                            0,
                            2
                        ],
                        "cooldowm": 2,
                        "duration": 14.4,
                        "ranks": [
                            2,
                            4,
                            6,
                            8,
                            10
                        ],
                        "maxDescription": "22204",
                        "maxCondition": 21,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 113,
                                "formulaValue": 1.147,
                                "allRankFormulaValue": [
                                    1.147,
                                    2.294,
                                    3.4410000000000003,
                                    4.588,
                                    5.735
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 114,
                                "target": 0,
                                "formulaValue": 2.1416,
                                "allRankFormulaValue": [
                                    2.1416,
                                    4.2832,
                                    6.424799999999999,
                                    8.5664,
                                    10.708
                                ]
                            }
                        ]
                    },
                    {
                        "id": 53,
                        "name": "21115",
                        "icon": "Skill_Icon_0009",
                        "description": "22205",
                        "energy": [
                            0,
                            0,
                            0,
                            3
                        ],
                        "cooldowm": 2,
                        "duration": 7.2,
                        "ranks": [
                            3,
                            6,
                            9,
                            12,
                            15
                        ],
                        "maxDescription": "22001",
                        "maxCondition": 72,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 115,
                                "formulaValue": 1.7076,
                                "allRankFormulaValue": [
                                    1.7076,
                                    3.4152,
                                    5.1228,
                                    6.8304,
                                    8.538
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 101,
                                "target": 0,
                                "formulaValue": -0.02376,
                                "allRankFormulaValue": [
                                    -0.02376,
                                    -0.04752,
                                    -0.07128,
                                    -0.09504,
                                    -0.1188
                                ]
                            }
                        ]
                    },
                    {
                        "id": 54,
                        "name": "21116",
                        "icon": "Skill_Icon_0007",
                        "description": "22204",
                        "energy": [
                            0,
                            1,
                            2,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 7.2,
                        "ranks": [
                            3,
                            6,
                            9,
                            12,
                            15
                        ],
                        "maxDescription": "22242",
                        "maxCondition": 32,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 114,
                                "formulaValue": 1.6062,
                                "allRankFormulaValue": [
                                    1.6062,
                                    3.2124,
                                    4.8186,
                                    6.4248,
                                    8.031
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 214,
                                "target": 0,
                                "formulaValue": 1,
                                "allRankFormulaValue": [
                                    1,
                                    1,
                                    1,
                                    1,
                                    1
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                "number": "3",
                "type": 2,
                "ele": 1,
                "velocity": 47.62,
                "stamina": 64.67,
                "intelligent": 54.11,
                "breakOut": 50.58,
                "will": 59.18,
                "habit": 2,
                "dna": {
                    "head": "110106",
                    "body": "140204",
                    "hand": "110208",
                    "leg": "140303",
                    "back": "130301",
                    "hat": "140206"
                },
                "skills": [
                    {
                        "id": 1,
                        "name": "21063",
                        "icon": "Skill_Icon_0008",
                        "description": "22204",
                        "energy": [
                            0,
                            0,
                            2,
                            1
                        ],
                        "cooldowm": 2,
                        "duration": -1,
                        "ranks": [
                            0.6,
                            1.2,
                            1.8,
                            2.4,
                            3
                        ],
                        "maxDescription": "22001",
                        "maxCondition": 41,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 114,
                                "formulaValue": 0.32466,
                                "allRankFormulaValue": [
                                    0.32466,
                                    0.64932,
                                    0.97398,
                                    1.29864,
                                    1.6232999999999997
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 101,
                                "target": 0,
                                "formulaValue": -0.005148000000000001,
                                "allRankFormulaValue": [
                                    -0.005148000000000001,
                                    -0.010296000000000001,
                                    -0.015444000000000001,
                                    -0.020592000000000003,
                                    -0.025740000000000002
                                ]
                            }
                        ]
                    },
                    {
                        "id": 56,
                        "name": "21118",
                        "icon": "Skill_Icon_0001",
                        "description": "22206",
                        "energy": [
                            0,
                            3,
                            0,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 3.6,
                        "ranks": [
                            6,
                            12,
                            18,
                            24,
                            30
                        ],
                        "maxDescription": "23023",
                        "maxCondition": 12,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 111,
                                "formulaValue": 3.0348,
                                "allRankFormulaValue": [
                                    3.0348,
                                    6.0696,
                                    9.1044,
                                    12.1392,
                                    15.174
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 201,
                                "target": 1,
                                "formulaValue": 0.039599999999999996,
                                "allRankFormulaValue": [
                                    0.039599999999999996,
                                    0.07919999999999999,
                                    0.1188,
                                    0.15839999999999999,
                                    0.198
                                ]
                            }
                        ]
                    },
                    {
                        "id": 15,
                        "name": "21077",
                        "icon": "Skill_Icon_0003",
                        "description": "22202",
                        "energy": [
                            2,
                            0,
                            0,
                            2
                        ],
                        "cooldowm": 2,
                        "duration": 9.6,
                        "ranks": [
                            4,
                            8,
                            12,
                            16,
                            20
                        ],
                        "maxDescription": "22203",
                        "maxCondition": 33,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 112,
                                "formulaValue": 2.0232,
                                "allRankFormulaValue": [
                                    2.0232,
                                    4.0464,
                                    6.0696,
                                    8.0928,
                                    10.116
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 113,
                                "target": 0,
                                "formulaValue": 4.7344,
                                "allRankFormulaValue": [
                                    4.7344,
                                    9.4688,
                                    14.203199999999999,
                                    18.9376,
                                    23.672
                                ]
                            }
                        ]
                    },
                    {
                        "id": 52,
                        "name": "21114",
                        "icon": "Skill_Icon_0005",
                        "description": "22203",
                        "energy": [
                            0,
                            2,
                            0,
                            1
                        ],
                        "cooldowm": 2,
                        "duration": 7.2,
                        "ranks": [
                            3,
                            6,
                            9,
                            12,
                            15
                        ],
                        "maxDescription": "22243",
                        "maxCondition": 22,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 113,
                                "formulaValue": 1.7753999999999999,
                                "allRankFormulaValue": [
                                    1.7753999999999999,
                                    3.5507999999999997,
                                    5.3262,
                                    7.1015999999999995,
                                    8.877
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 215,
                                "target": 0,
                                "formulaValue": 1,
                                "allRankFormulaValue": [
                                    1,
                                    1,
                                    1,
                                    1,
                                    1
                                ]
                            }
                        ]
                    },
                    {
                        "id": 77,
                        "name": "21139",
                        "icon": "Skill_Icon_0007",
                        "description": "22204",
                        "energy": [
                            0,
                            0,
                            4,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 7.2,
                        "ranks": [
                            3,
                            6,
                            9,
                            12,
                            15
                        ],
                        "maxDescription": "22216",
                        "maxCondition": 43,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 114,
                                "formulaValue": 1.6232999999999997,
                                "allRankFormulaValue": [
                                    1.6232999999999997,
                                    3.2465999999999995,
                                    4.8699,
                                    6.493199999999999,
                                    8.1165
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 121,
                                "target": 0,
                                "formulaValue": 5.1,
                                "allRankFormulaValue": [
                                    5.1,
                                    10.2,
                                    15.299999999999999,
                                    20.4,
                                    25.5
                                ]
                            }
                        ]
                    },
                    {
                        "id": 72,
                        "name": "21134",
                        "icon": "Skill_Icon_0007",
                        "description": "22204",
                        "energy": [
                            0,
                            1,
                            2,
                            0
                        ],
                        "cooldowm": 2,
                        "duration": 7.2,
                        "ranks": [
                            3,
                            6,
                            9,
                            12,
                            15
                        ],
                        "maxDescription": "22207",
                        "maxCondition": 51,
                        "maxConditionValue": 0,
                        "effects": [
                            {
                                "type": 114,
                                "formulaValue": 1.6232999999999997,
                                "allRankFormulaValue": [
                                    1.6232999999999997,
                                    3.2465999999999995,
                                    4.8699,
                                    6.493199999999999,
                                    8.1165
                                ]
                            }
                        ],
                        "maxEffects": [
                            {
                                "type": 111,
                                "target": 0,
                                "formulaValue": 7.199999999999999,
                                "allRankFormulaValue": [
                                    7.199999999999999,
                                    14.399999999999999,
                                    21.599999999999998,
                                    28.799999999999997,
                                    36
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    }