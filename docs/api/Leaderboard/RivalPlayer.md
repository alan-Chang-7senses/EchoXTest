# 排行榜 - 排行榜 Peta 資訊

## 介紹

- 依照大廳類別提供競賽的領先率排行榜 Peta 資訊。

## URL

http(s)://`域名`/Leaderboard/RivalPlayer/

## Method

`POST`

## Request

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳](../codes/race.md#lobby) |
| player | int | 角色編號。 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [parts](#parts) | object | 當前角色 Avatar 部位 |
| [player](#player) | object | 當前角色資料 |
| [ranking](#ranking) | object | 當前角色排名資料 |

#### <span id="parts"> parts 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號，16 碼 |
| head | string | 頭部代碼（或空字串略過此部位） |
| body | string | 身體代碼（或空字串略過此部位） |
| hand | string | 手部代碼（或空字串略過此部位） |
| leg | string | 腿部代碼（或空字串略過此部位） |
| back | string | 背部代碼（或空字串略過此部位） |
| hat | string | 頭冠代碼（或空字串略過此部位） |

#### <span id="player"> player 內容</span>

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

#### <span id="ranking">ranking 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| ranking | int | 名次 |
| playCount | int | 參賽次數 |
| leadRate | float | 領先率百分比之數值，例如 12.34 代表領先率為「12.34%」(等積分制完成後會修改內容) |


### Example

#### 成功

	{
        "error": {
            "code": 0,
            "message": ""
        },
        "parts": {"
            id": 101,
            "head": "110106",
            "body": "110106",
            "hand": "110106",
            "leg": "110106",
            "back": "110106",
            "hat": "110106"
        },
        "player": {
            "id": 101,
            "idName": "101",
            "name": "101",
            "ele" : 1,
            "sync" : 0,
            "level" : 1,
            "exp" : 0,
            "maxExp" : 64,
            "rank" : 1,
            "velocity" : 64,
            "stamina" : 58.8,
            "intelligent" : 62.6,
            "breakOut" : 74.98,
            "will" : 63.86,
            "dune" : 12,
            "craterLake" : 0,
            "volcano" : 0,
            "tailwind" : 12,
            "crosswind" : 0,
            "headwind" : 0,
            "sunny" : 12,
            "aurora" : 0,
            "sandDust" : 0,
            "flat" : 12,
            "upslope" : 0,
            "downslope" : 0,
            "sun" : 0,
            "habit" : 1,
            "slotNumber" : 6,
            "skills" : [
                {
                    "id" : 1,
                    "name" : "21063",
                    "icon" : "Skill_Icon_0008",
                    "description" : "22210",
                    "level" : 1,
                    "slot" : 1,
                    "energy" : [
                        0,
                        0,
                        2,
                        1
                    ],
                    "cooldown" : 2,
                    "duration" : -1,
                    "ranks" : [
                        0.6,
                        1.2,
                        1.8,
                        2.4,
                        3
                    ],
                    "maxDescription" : "20002",
                    "maxCondition" : 5,
                    "maxConditionValue" : 1,
                    "attackedDesc" : "",
                    "effects": [
                        {
                            "type" : 114,
                            "formulaValue" : 0.37560000000000004,
                            "allRankFormulaValue" : [
                                0.37560000000000004,
                                0.7512000000000001,
                                1.1268,
                                1.5024000000000002,
                                1.8780000000000001
                            ]
                        }
                    ],
                    "maxEffects": [
                        {
                            "type" : 101,
                            "target" : 0,
                            "formulaValue" : -0.018,
                            "allRankFormulaValue" : [
                                -0.018,
                                -0.036,
                                -0.054,
                                -0.072,
                                -0.09
                            ]
                        }
                    ]
                },
                {
                    "id" : 2,
                    "name" : "21064",
                    "icon" : "Skill_Icon_0010",
                    "description" : "22211",
                    "level" : 1,
                    "slot" : 2,
                    "energy" : [
                        0,
                        0,
                        0,
                        2
                    ],
                    "cooldown" : 2,
                    "duration" : -1,
                    "ranks" : [
                        0.4,
                        0.8,
                        1.2,
                        1.6,
                        2
                    ],
                    "maxDescription" : "20002",
                    "maxCondition" : 51,
                    "maxConditionValue" : 0,
                    "attackedDesc" : "",
                    "effects" : [
                        {
                            "type" : 115,
                            "formulaValue" : 0.2352,
                            "allRankFormulaValue" : [
                                0.2352,
                                0.4704,
                                0.7055999999999999,
                                0.9408,
                                1.176
                            ]
                        },
                        {
                            "type" : 201,
                            "formulaValue" : 0.21168,
                            "allRankFormulaValue" : [
                                0.21168,
                                0.42336,
                                0.6350399999999999,
                                0.84672,
                                1.0584
                            ]
                        }
                    ],
                    "maxEffects" : [
                        {
                            "type" : 101,
                            "target" : 0,
                            "formulaValue" : -0.009600000000000001,
                            "allRankFormulaValue" : [
                                -0.009600000000000001,
                                -0.019200000000000002,
                                -0.0288,
                                -0.038400000000000004,
                                -0.048
                            ]
                        }
                    ]
                },
                {
                    "id" : 3,
                    "name" : "21066",
                    "icon" : "Skill_Icon_0004",
                    "description" : "22208",
                    "level" : 1,
                    "slot" : 3,
                    "energy" : [
                        4,
                        0,
                        0,
                        0
                    ],
                    "cooldown" : 2,
                    "duration" : -1,
                    "ranks" : [
                        0.8,
                        1.6,
                        2.4,
                        3.2,
                        4
                    ],
                    "maxDescription" : "22212",
                    "maxCondition" : 41,
                    "maxConditionValue" : 0,
                    "attackedDesc" : "",
                    "effects" : [
                        {
                            "type" : 112,
                            "formulaValue" : 0.59984,
                            "allRankFormulaValue" : [
                                0.59984,
                                1.19968,
                                1.79952,
                                2.39936,
                                2.9992
                            ]
                        }
                    ],
                    "maxEffects" : [
                        {
                            "type" : 102,
                            "target" : 0,
                            "formulaValue" : 0.10400000000000001,
                            "allRankFormulaValue" : [
                                0.10400000000000001,
                                0.20800000000000002,
                                0.312,
                                0.41600000000000004,
                                0.52
                            ]
                        }
                    ]
                },
                {
                    "id" : 4,
                    "name" : "21065",
                    "icon" : "Skill_Icon_0002",
                    "description" : "22207",
                    "level" : 1,
                    "slot" : 4,
                    "energy" : [
                        0,
                        1,
                        1,
                        0
                    ],
                    "cooldown" : 2,
                    "duration" : -1,
                    "ranks" : [
                        0.4,
                        0.8,
                        1.2,
                        1.6,
                        2
                    ],
                    "maxDescription" : "23307",
                    "maxCondition" : 21,
                    "maxConditionValue" : 0,
                    "attackedDesc" : "22244",
                    "effects" : [
                        {
                            "type" : 111,
                            "formulaValue" : 0.29992,
                            "allRankFormulaValue" : [
                                0.29992,
                                0.59984,
                                0.89976,
                                1.19968,
                                1.4996
                            ]
                        }
                    ],
                    "maxEffects" : [
                        {
                            "type" : 501,
                            "target" : 0,
                            "formulaValue" : 0,
                            "allRankFormulaValue" : [
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
                    "id" : 5,
                    "name" : "21067",
                    "icon" : "Skill_Icon_0006",
                    "description" : "22209",
                    "level" : 1,
                    "slot" : 5,
                    "energy" : [
                        0,
                        1,
                        0,
                        2
                    ],
                    "cooldown" : 2,
                    "duration" : -1,
                    "ranks" : [
                        0.6,
                        1.2,
                        1.8,
                        2.4,
                        3
                    ],
                    "maxDescription" : "23095",
                    "maxCondition" : 5,
                    "maxConditionValue" : 1,
                    "attackedDesc" : "23237",
                    "effects" : [
                        {
                            "type" : 113,
                            "formulaValue" : 0.38315999999999995,
                            "allRankFormulaValue" : [
                                0.38315999999999995,
                                0.7663199999999999,
                                1.14948,
                                1.5326399999999998,
                                1.9158
                            ]
                        }
                    ],
                    "maxEffects": [ 
                        {
                            "type" : 201,
                            "target" : 3,
                            "formulaValue" : -1.0799999999999998,
                            "allRankFormulaValue" : [
                                -1.0799999999999998,
                                -2.1599999999999997,
                                -3.24,
                                -4.319999999999999,
                                -5.3999999999999995
                            ]
                        }
                    ]
                },
                {
                    "id" : 6,
                    "name" : "21068",
                    "icon" : "Skill_Icon_0004",
                    "description" : "22208",
                    "level" : 1,
                    "slot" : 6,
                    "energy" : [
                        4,
                        0,
                        0,
                        0
                    ],
                    "cooldown" : 2,
                    "duration" : -1,
                    "ranks" : [
                        0.8,
                        1.6,
                        2.4,
                        3.2,
                        4
                    ],
                    "maxDescription" : "22212",
                    "maxCondition" : 4,
                    "maxConditionValue" : 1,
                    "attackedDesc" : "",
                    "effects" : [
                        {
                            "type" : 112,
                            "formulaValue" : 0.59984,
                            "allRankFormulaValue" : [
                                0.59984,
                                1.19968,
                                1.79952,
                                2.39936,
                                2.9992
                            ]
                        }
                    ],
                    "maxEffects" : [
                        {
                            "type" : 102,
                            "target" : 0,
                            "formulaValue" : 0.08000000000000002,
                            "allRankFormulaValue" : [
                                0.08000000000000002,
                                0.16000000000000003,
                                0.24,
                                0.32000000000000006,
                                0.4
                            ]
                        }
                    ]
                },
                {
                    "id" : 125,
                    "name" : "21157",
                    "icon" : "Skill_Icon_0008",
                    "description" : "22210",
                    "level" : 1,
                    "slot" : 0,
                    "energy" : [
                        3,
                        3,
                        0,
                        0
                    ],
                    "cooldown" : 2,
                    "duration" : -1,
                    "ranks" : [
                        2.4,
                        4.8,
                        7,
                        9.6,
                        12
                    ],
                    "maxDescription" : "22207",
                    "maxCondition" : 12,
                    "maxConditionValue" : 0,
                    "attackedDesc" : "",
                    "effects" : [
                        {
                            "type" : 114,
                            "formulaValue" : 1.5024000000000002,
                            "allRankFormulaValue" : [
                                1.5024000000000002,
                                3.0048000000000004,
                                4.382,
                                6.009600000000001,
                                7.5120000000000005
                            ]
                        }
                    ],
                    "maxEffects" : [
                        {
                            "type" : 111,
                            "target" : 0,
                            "formulaValue" : 3.59904,
                            "allRankFormulaValue" : [
                                3.59904,
                                7.19808,
                                10.4972,
                                14.39616,
                                17.9952
                            ]
                        }
                    ]
                },
                {
                    "id" : 154,
                    "name" : "21216",
                    "icon" : "Skill_Icon_0027",
                    "description" : "22212",
                    "level" : 1,
                    "slot" : 0,
                    "energy" : [
                        2,
                        0,
                        0,
                        2
                    ],
                    "cooldown" : 2,
                    "duration" : 9.6,
                    "ranks" : [
                        2,
                        4,
                        6,
                        8,
                        10
                    ],
                    "maxDescription" : "22004",
                    "maxCondition" : 1,
                    "maxConditionValue" : 1,
                    "attackedDesc" : "",
                    "effects" : [
                        {
                            "type" : 102,
                            "formulaValue" : 0.2,
                            "allRankFormulaValue" : [
                                0.2,
                                0.4,
                                0.6,
                                0.8,
                                1
                            ]
                        }
                    ],
                    "maxEffects" : [
                        {
                            "type" : 201,
                            "target" : 0,
                            "formulaValue" : 5.2,
                            "allRankFormulaValue" : [
                                5.2,
                                10.4,
                                15.600000000000001,
                                20.8,
                                26
                            ]
                        }
                    ]
                }
            ],
            "skillHole" : [
                1,
                2,
                3,
                4,
                5,
                6
            ],
            "strengthLevel" : 0,
            "skeletonType" : 0
        },
        "ranking" : {
            "ranking" : 0,
            "playCount" : 0,
            "leadRate" : 0
        }
    }