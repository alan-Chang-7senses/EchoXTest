# PVE - 關卡列表頁面

## 介紹

- 在章節頁面點擊指定關卡，取得該章節中所有關卡的資訊。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVE/LevelListPage/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| chapterID | int | 章節編號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| name | string | 章節名稱代號 |
| currentMedal | int | 章節當前獲得獎牌總數 |
| currentPower | int | 使用者當前電力 |
| medalAmountFirst | int | 章節第一階段獎勵所需獎牌數量 |
| [rewardFirst](#rewards) | array | 章節第一階段獎勵內容 |
| medalAmountSecond | int | 章節第二階段獎勵所需獎牌數量 |
| [rewardSecond](#rewards) | array | 章節第二階段獎勵內容 |
| medalAmountThird | int | 章節第三階段獎勵所需獎牌數量 |
| [rewardThrid](#rewards) | array | 章節第三階段獎勵內容 |
| [rewardAcepptedInfo](#rewardAcepptedInfo) | object | 各階段獎牌獎勵的領取狀態 |
| [levels](#levels) | array | 各關卡資訊集合 |


<br>

#### <span id="rewards">rewards 獎勵內容 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 獎勵物品編號 |
| itemName | string | 獎勵物品名稱代號 |
| description | string | 獎勵物品描述代號 |
| itemType | int | [物品種類](../codes/item.md#itemType) |
| useType | int | [使用種類](../codes/item.md#useType) |
| stackLimit | int | 堆疊上限 |
| icon | string | 物品圖示代號 |
| source | array | 來源／出處 代號陣列 |
<br>

#### <span id="rewardAcepptedInfo">rewards 各階段獎牌獎勵的領取狀態內容 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| firstReward | bool | 第一階段獎勵是否已領取 |
| secondReward | bool | 第二階段獎勵是否已領取 |
| thirdReward | bool | 第三階段獎勵是否已領取 |
<br>

#### <span id="levels">levels 各關卡內容 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 關卡編號 |
| name | string | 關卡名稱代號 |
| description | string | 關卡描述代號 |
| recommendLevel | int | 關卡推薦等級 |
| currentMedalAmount | int | 關卡當前獎牌數量(未通關為0) |
| scene | int | 場景編號 |
| sceneName | string | 場景名稱代號 |
| enviroment | int | [環境](../codes/scene.md#env) |
| weather | int | [天氣](../codes/scene.md#weather) |
| windDirection | int | [風向](../codes/scene.md#windDirection) |
| windSpeed | int | 風速 |
| lighting | int | [照明（明暗）](../codes/scene.md#lighting) |
| isUnlock | bool | 是否解鎖 |
| powerRequired | int | 需求電力 |
| hasCleared | bool | 是否已過關 |
| [firstReward](#rewards) | array | 初次通關獎勵內容 |
| [sustainRewards](#rewards) | array | 固定通關獎勵內容 |
| canRush | bool | 可否使用快速通關 |
<br>


<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "name": "",
        "chapterMedal": 8,
        "currentPower": 300,
        "medalAmountFirst": 3,
        "rewardFirst": [
            {
                "itemID": 1111,
                "itemName": "8104",
                "description": "8604",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0004",
                "source": [
                    "pvp3"
                ]
            },
            {
                "itemID": 1121,
                "itemName": "8106",
                "description": "8606",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0006",
                "source": [
                    "s012"
                ]
            },
            {
                "itemID": 1131,
                "itemName": "8108",
                "description": "8608",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0008",
                "source": [
                    "s010"
                ]
            }
        ],
        "medalAmountSecond": 6,
        "rewardSecond": [
            {
                "itemID": 1111,
                "itemName": "8104",
                "description": "8604",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0004",
                "source": [
                    "pvp3"
                ]
            },
            {
                "itemID": 1121,
                "itemName": "8106",
                "description": "8606",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0006",
                "source": [
                    "s012"
                ]
            },
            {
                "itemID": 1131,
                "itemName": "8108",
                "description": "8608",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0008",
                "source": [
                    "s010"
                ]
            }
        ],
        "medalAmountThird": 9,
        "rewardThrid": [
            {
                "itemID": 1111,
                "itemName": "8104",
                "description": "8604",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0004",
                "source": [
                    "pvp3"
                ]
            },
            {
                "itemID": 1121,
                "itemName": "8106",
                "description": "8606",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0006",
                "source": [
                    "s012"
                ]
            },
            {
                "itemID": 1131,
                "itemName": "8108",
                "description": "8608",
                "itemType": 1,
                "useType": 0,
                "stackLimit": 99999,
                "icon": "ItemIcon_0008",
                "source": [
                    "s010"
                ]
            }
        ],
        "rewardAcepptedInfo": {
            "firstReward": false,
            "secondReward": true,
            "thirdReward": false
        },
        "levels": [
            {
                "id": 101,
                "name": "",
                "description": "",
                "recommendLevel": 0,
                "currentMedalAmount": 3,
                "scene": 1001,
                "sceneName": "9001",
                "enviroment": 1,
                "weather": 3,
                "windDirection": 4,
                "windSpeed": 25,
                "lighting": 2,
                "isUnlock": true,
                "powerRequired": 0,
                "hasCleared": true,
                "firstReward": [
                    {
                        "itemID": -3,
                        "itemName": "8134",
                        "description": "8634",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 2147483647,
                        "icon": "ItemIcon_0028",
                        "source": []
                    }
                ],
                "sustainReward": [
                    {
                        "itemID": 2013,
                        "itemName": "8113",
                        "description": "8613",
                        "itemType": 2,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0012",
                        "source": [
                            "s004"
                        ]
                    },
                    {
                        "itemID": 2017,
                        "itemName": "8114",
                        "description": "8614",
                        "itemType": 2,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0016",
                        "source": [
                            "s005"
                        ]
                    }
                ],
                "canRush": true
            },
            {
                "id": 102,
                "name": "",
                "description": "",
                "recommendLevel": 0,
                "currentMedalAmount": 3,
                "scene": 1001,
                "sceneName": "9001",
                "enviroment": 1,
                "weather": 3,
                "windDirection": 4,
                "windSpeed": 25,
                "lighting": 2,
                "isUnlock": true,
                "powerRequired": 0,
                "hasCleared": true,
                "firstReward": [
                    {
                        "itemID": -3,
                        "itemName": "8134",
                        "description": "8634",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 2147483647,
                        "icon": "ItemIcon_0028",
                        "source": []
                    }
                ],
                "sustainReward": [
                    {
                        "itemID": 2013,
                        "itemName": "8113",
                        "description": "8613",
                        "itemType": 2,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0012",
                        "source": [
                            "s004"
                        ]
                    },
                    {
                        "itemID": 2017,
                        "itemName": "8114",
                        "description": "8614",
                        "itemType": 2,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0016",
                        "source": [
                            "s005"
                        ]
                    }
                ],
                "canRush": true
            },
            {
                "id": 103,
                "name": "",
                "description": "",
                "recommendLevel": 0,
                "currentMedalAmount": 2,
                "scene": 1001,
                "sceneName": "9001",
                "enviroment": 1,
                "weather": 3,
                "windDirection": 4,
                "windSpeed": 25,
                "lighting": 2,
                "isUnlock": true,
                "powerRequired": 0,
                "hasCleared": true,
                "firstReward": [
                    {
                        "itemID": -3,
                        "itemName": "8134",
                        "description": "8634",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 2147483647,
                        "icon": "ItemIcon_0028",
                        "source": []
                    }
                ],
                "sustainReward": [
                    {
                        "itemID": 2013,
                        "itemName": "8113",
                        "description": "8613",
                        "itemType": 2,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0012",
                        "source": [
                            "s004"
                        ]
                    },
                    {
                        "itemID": 2017,
                        "itemName": "8114",
                        "description": "8614",
                        "itemType": 2,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0016",
                        "source": [
                            "s005"
                        ]
                    }
                ],
                "canRush": false
            }
        ],
        "player": {
            "level": 40,
            "id": 202,
            "idName": "202",
            "name": "202",
            "skills": [
                {
                    "id": 3,
                    "name": "21065",
                    "icon": "Skill_Icon_0004",
                    "description": "22208",
                    "level": 5,
                    "slot": 1,
                    "energy": [
                        4,
                        0,
                        0,
                        0
                    ],
                    "cooldown": 2,
                    "duration": -1,
                    "ranks": [
                        0.8,
                        1.6,
                        2.4,
                        3.2,
                        4
                    ],
                    "maxDescription": "22212",
                    "maxCondition": 1,
                    "maxConditionValue": 2,
                    "attackedDesc": "",
                    "effects": [
                        {
                            "type": 112,
                            "formulaValue": 5.0564,
                            "allRankFormulaValue": [
                                1.01128,
                                2.02256,
                                3.0338399999999996,
                                4.04512,
                                5.0564
                            ]
                        }
                    ]
                },
                {
                    "id": 6,
                    "name": "21068",
                    "icon": "Skill_Icon_0004",
                    "description": "22208",
                    "level": 1,
                    "slot": 2,
                    "energy": [
                        4,
                        0,
                        0,
                        0
                    ],
                    "cooldown": 2,
                    "duration": -1,
                    "ranks": [
                        0.8,
                        1.6,
                        2.4,
                        3.2,
                        4
                    ],
                    "maxDescription": "22212",
                    "maxCondition": 4,
                    "maxConditionValue": 1,
                    "attackedDesc": "",
                    "effects": [
                        {
                            "type": 112,
                            "formulaValue": 1.01128,
                            "allRankFormulaValue": [
                                1.01128,
                                2.02256,
                                3.0338399999999996,
                                4.04512,
                                5.0564
                            ]
                        }
                    ]
                },
                {
                    "id": 44,
                    "name": "21106",
                    "icon": "Skill_Icon_0001",
                    "description": "22207",
                    "level": 1,
                    "slot": 3,
                    "energy": [
                        1,
                        2,
                        0,
                        0
                    ],
                    "cooldown": 2,
                    "duration": 14.4,
                    "ranks": [
                        2,
                        4,
                        6,
                        8,
                        10
                    ],
                    "maxDescription": "22212",
                    "maxCondition": 31,
                    "maxConditionValue": 0,
                    "attackedDesc": "",
                    "effects": [
                        {
                            "type": 111,
                            "formulaValue": 2.5282,
                            "allRankFormulaValue": [
                                2.5282,
                                5.0564,
                                7.5846,
                                10.1128,
                                12.640999999999998
                            ]
                        }
                    ]
                },
                {
                    "id": 53,
                    "name": "21115",
                    "icon": "Skill_Icon_0009",
                    "description": "22211",
                    "level": 1,
                    "slot": 4,
                    "energy": [
                        0,
                        0,
                        0,
                        3
                    ],
                    "cooldown": 2,
                    "duration": 7.2,
                    "ranks": [
                        3,
                        6,
                        9,
                        12,
                        15
                    ],
                    "maxDescription": "20002",
                    "maxCondition": 72,
                    "maxConditionValue": 0,
                    "attackedDesc": "",
                    "effects": [
                        {
                            "type": 115,
                            "formulaValue": 3.7923,
                            "allRankFormulaValue": [
                                3.7923,
                                7.5846,
                                11.376900000000001,
                                15.1692,
                                18.961499999999997
                            ]
                        },
                        {
                            "type": 201,
                            "formulaValue": 3.4130700000000003,
                            "allRankFormulaValue": [
                                3.4130700000000003,
                                6.8261400000000005,
                                10.239210000000002,
                                13.652280000000001,
                                17.06535
                            ]
                        }
                    ]
                },
                {
                    "id": 67,
                    "name": "21129",
                    "icon": "Skill_Icon_0003",
                    "description": "22208",
                    "level": 1,
                    "slot": 0,
                    "energy": [
                        3,
                        0,
                        0,
                        0
                    ],
                    "cooldown": 2,
                    "duration": 7.2,
                    "ranks": [
                        3,
                        6,
                        9,
                        12,
                        15
                    ],
                    "maxDescription": "22212",
                    "maxCondition": 62,
                    "maxConditionValue": 0,
                    "attackedDesc": "",
                    "effects": [
                        {
                            "type": 112,
                            "formulaValue": 3.7923,
                            "allRankFormulaValue": [
                                3.7923,
                                7.5846,
                                11.376900000000001,
                                15.1692,
                                18.961499999999997
                            ]
                        }
                    ]
                },
                {
                    "id": 82,
                    "name": "21144",
                    "icon": "Skill_Icon_0003",
                    "description": "22208",
                    "level": 1,
                    "slot": 0,
                    "energy": [
                        3,
                        0,
                        0,
                        0
                    ],
                    "cooldown": 2,
                    "duration": 7.2,
                    "ranks": [
                        3,
                        6,
                        9,
                        12,
                        15
                    ],
                    "maxDescription": "22212",
                    "maxCondition": 32,
                    "maxConditionValue": 0,
                    "attackedDesc": "",
                    "effects": [
                        {
                            "type": 112,
                            "formulaValue": 3.7923,
                            "allRankFormulaValue": [
                                3.7923,
                                7.5846,
                                11.376900000000001,
                                15.1692,
                                18.961499999999997
                            ]
                        }
                    ]
                }
            ],
            "skillHole": [
                3,
                6,
                44,
                53
            ]
        }
    }