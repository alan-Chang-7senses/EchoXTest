# 使用者 - 當前角色

## 介紹

- 輸入使用者編號查詢使用者持有的指定角色（action = 1）。
- 輸入使用者編號設定使用者的當前角色（action = 2），若使用者於競賽中，無法使用此功能。
- 回傳所查詢或所設定的角色相關資訊。

## URL

http(s)://`域名`/User/CurrentPlayer/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| player | int | 角色編號，16 碼 |
| action | int | 動作，1 = 查詢、2 = 設定 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [player](#player) | object | 角色資訊 |
| level | int | 目前角色等級 |
| currentExp | int | 目前角色經驗值 |
| currentLevelExpMax | int | 目前角色等級最大經驗值 |
| currentLevelExpMin | int | 目前角色等級最小經驗值 |

#### <span id="player">player 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號 |
| idName | string | 給前端顯示的角色編號字串 |
| name | string | 角色暱稱，由使用者命名，若未編輯則與角色編號相同 |
| ele | int | [屬性](../codes/player.md#attr) |
| sync | float | 同步率 |
| level | int | 等級 |
| exp | int | 經驗值 |
| maxExp | int | 下次升級經驗值**（未實作，目前為 0）** |
| rank | int | 階級 |
| velocity | float | 速度 |
| stamina | float | 耐力 |
| intelligent | float | 聰慧 |
| breakOut | float | 爆發 |
| will | float | 鬥志 |
| dune | int | 環境適性 - 沙丘 |
| craterLake | int | 環境適性 - 亞湖 |
| volcano | int | 環境適性 - 火山 |
| tailwind | int | 風勢適性 - 順風 |
| crosswind | int | 風勢適性 - 側風 |
| headwind | int | 風勢適性 - 逆風 |
| sunny | int | 天氣適性 - 晴天 |
| aurora | int | 天氣適性 - 極光 |
| sandDust | int | 天氣適性 - 沙塵 |
| flat | int | 地形適性 - 平地 |
| upslope | int | 地形適性 - 上坡 |
| downslope | int | 地形適性 - 下坡 |
| sun | int | [太陽適性](../codes/player.md#sun) |
| habit | int | [比賽習慣](../codes/player.md#habit) |
| slotNumber | int | 插槽數量 |
| [skills](#skills) | array | 角色持有的技能清單陣列 |
| skillHole | array | 技能插槽陣列<br>陣列長度為插槽數量，陣列元素值為技能編號 |

#### <span id="skills">skills 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能編號 |
| name | string | 技能名稱代號 |
| icon | string | 技能Icon代號 |
| description | string | 技能敘述代號 |
| level | int | 技能等級 |
| slot | int | 所在插槽位置，0 為未裝備，1 為第一個插槽 |
| energy | array | 使用條件能量值，陣列元素依序為 紅、黃、藍、綠 |
| cooldown | float | 冷卻時間（秒） |
| duration | float | 時效性<br>0 = 單次效果<br>大於 0 = 時效秒數<br>-1 = 持續到比賽結束 |
| ranks | array | 技能星級 1 ~ 5 的 N 值陣列 |
| maxDescription | string | 滿星效果敘述代號 |
| maxCondition | int | [滿星效果條件](../codes/skill.md#maxCondition) |
| maxConditionValue | int | 滿星效果條件值 |
| attackedDesc | string | 被攻擊敘述代號 |
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
        "player": {
            "id": 1010000000000001,
            "idName": "NFT1",
            "name": "1010000000000001",
            "ele": 1,
            "sync": 0,
            "level": 1,
            "exp": 0,
            "maxExp": 0,
            "rank": 1,
            "velocity": 59.92,
            "stamina": 57.67,
            "intelligent": 63.06,
            "breakOut": 54.66,
            "will": 52.78,
            "dune": 6,
            "craterLake": 0,
            "volcano": 0,
            "tailwind": 2,
            "crosswind": 2,
            "headwind": 0,
            "sunny": 1,
            "aurora": 1,
            "sandDust": 4,
            "flat": 3,
            "upslope": 3,
            "downslope": 5,
            "sun": 0,
            "habit": 3,
            "slotNumber": 4,
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
                    "attackedDesc": "",
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
                    "attackedDesc": "",
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
                    "attackedDesc": "",
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
                    "attackedDesc": "",
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
                    "attackedDesc": "",
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
                    "attackedDesc": "",
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
            ],
            "skillHole": [
                1,
                2,
                3,
                4
            ],
            "strengthLevel": 0,
            "skeletonType": 0
        }
    }