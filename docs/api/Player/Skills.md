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
        "skills": [
            {
                "id": 1,
                "name": "21001",
                "description": "22204",
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
                        "type": 115,
                        "formulaValue": 0.5,
                        "allRankFormulaValue": [
                            0.5,
                            0.5,
                            0.5,
                            0.5,
                            0.5
                        ]
                    }
                ],
                "maxEffects": [
                    {
                        "type": 102,
                        "target": 0,
                        "formulaValue": 0.5,
                        "allRankFormulaValue": [
                            0.5,
                            0.5,
                            0.5,
                            0.5,
                            0.5
                        ]
                    }
                ]
            },
            {
                "id": 2,
                "name": "21002",
                "description": "22205",
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
                        "type": 115,
                        "formulaValue": 2.8835,
                        "allRankFormulaValue": [
                            2.8835,
                            5.767,
                            8.650500000000001,
                            11.534,
                            14.4175
                        ]
                    }
                ],
                "maxEffects": [
                    {
                        "type": 504,
                        "target": 0,
                        "formulaValue": 0,
                        "allRankFormulaValue": [
                            0,
                            0,
                            0,
                            0,
                            0
                        ]
                    }
                ]
            },
            {
                "id": 3,
                "name": "21003",
                "description": "22202",
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
                        "type": 112,
                        "formulaValue": 2.7329999999999997,
                        "allRankFormulaValue": [
                            2.7329999999999997,
                            5.465999999999999,
                            8.199,
                            10.931999999999999,
                            13.665
                        ]
                    }
                ],
                "maxEffects": [
                    {
                        "type": 141,
                        "target": 0,
                        "formulaValue": 10,
                        "allRankFormulaValue": [
                            10,
                            10,
                            10,
                            10,
                            10
                        ]
                    }
                ]
            },
            {
                "id": 4,
                "name": "21004",
                "description": "22201",
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
                        "type": 111,
                        "formulaValue": 2.7329999999999997,
                        "allRankFormulaValue": [
                            2.7329999999999997,
                            5.465999999999999,
                            8.199,
                            10.931999999999999,
                            13.665
                        ]
                    }
                ],
                "maxEffects": [
                    {
                        "type": 102,
                        "target": 0,
                        "formulaValue": 1.32,
                        "allRankFormulaValue": [
                            1.32,
                            1.32,
                            1.32,
                            1.32,
                            1.32
                        ]
                    }
                ]
            },
            {
                "id": 5,
                "name": "21005",
                "description": "22203",
                "level": 1,
                "slot": 0,
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
                        "type": 113,
                        "formulaValue": 2.639,
                        "allRankFormulaValue": [
                            2.639,
                            5.278,
                            7.917000000000001,
                            10.556,
                            13.195
                        ]
                    }
                ],
                "maxEffects": [
                    {
                        "type": 102,
                        "target": 0,
                        "formulaValue": 1.32,
                        "allRankFormulaValue": [
                            1.32,
                            1.32,
                            1.32,
                            1.32,
                            1.32
                        ]
                    }
                ]
            },
            {
                "id": 6,
                "name": "21006",
                "description": "22202",
                "level": 1,
                "slot": 0,
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
                        "type": 112,
                        "formulaValue": 2.7329999999999997,
                        "allRankFormulaValue": [
                            2.7329999999999997,
                            5.465999999999999,
                            8.199,
                            10.931999999999999,
                            13.665
                        ]
                    }
                ],
                "maxEffects": [
                    {
                        "type": 121,
                        "target": 0,
                        "formulaValue": 10,
                        "allRankFormulaValue": [
                            10,
                            10,
                            10,
                            10,
                            10
                        ]
                    }
                ]
            },
            {
                "id": 34,
                "name": "21034",
                "description": "22201",
                "level": 1,
                "slot": 0,
                "energy": [
                    2,
                    2,
                    2,
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
                "maxDescription": "23033",
                "maxCondition": 41,
                "maxConditionValue": 0,
                "effects": [
                    {
                        "type": 111,
                        "formulaValue": 2.7329999999999997,
                        "allRankFormulaValue": [
                            2.7329999999999997,
                            5.465999999999999,
                            8.199,
                            10.931999999999999,
                            13.665
                        ]
                    }
                ],
                "maxEffects": [
                    {
                        "type": 102,
                        "target": 0,
                        "formulaValue": 1.98,
                        "allRankFormulaValue": [
                            1.98,
                            1.98,
                            1.98,
                            1.98,
                            1.98
                        ]
                    }
                ]
            }
        ]
    }