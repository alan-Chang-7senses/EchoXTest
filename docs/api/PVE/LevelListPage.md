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
| [botInfo](#botInfo) | array | 機器人(AI)資訊 |
| isUnlock | bool | 是否解鎖 |
| powerRequired | int | 需求電力 |
| hasCleared | bool | 是否已過關 |
| [firstReward](#rewards) | array | 初次通關獎勵內容 |
| [sustainRewards](#rewards) | array | 固定通關獎勵內容 |
| canRush | bool | 可否使用快速通關 |
<br>

#### <span id="botInfo">botInfo 機器人(AI)內容 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| aiUserID | int | 機器人使用者ID |
| trackNumber | int | 機器人所在跑道編號 |

<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "name": "",
        "chapterMedal": 4,
        "currentPower": 300,
        "medalAmountFirst": 0,
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
        "medalAmountSecond": 0,
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
        "medalAmountThird": 0,
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
        "levels": [
            {
                "id": 101,
                "name": "",
                "description": "",
                "recommendLevel": 0,
                "currentMedalAmount": 1,
                "scene": 1001,
                "sceneName": "9001",
                "enviroment": 1,
                "weather": 3,
                "windDirection": 4,
                "windSpeed": 25,
                "lighting": 2,
                "botInfo": [
                    {
                        "aiUserID": "-1",
                        "trackNumber": 1
                    },
                    {
                        "aiUserID": "-2",
                        "trackNumber": 2
                    },
                    {
                        "aiUserID": "-3",
                        "trackNumber": 3
                    }
                ],
                "isUnlock": true,
                "powerRequired": 0,
                "hasCleared": true,
                "firstReward": [
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
                "sustainReward": [
                    {
                        "itemID": 1112,
                        "itemName": "8105",
                        "description": "8605",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0005",
                        "source": [
                            "s008"
                        ]
                    },
                    {
                        "itemID": 1122,
                        "itemName": "8107",
                        "description": "8607",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0007",
                        "source": [
                            "s009"
                        ]
                    },
                    {
                        "itemID": 1132,
                        "itemName": "8109",
                        "description": "8609",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0009",
                        "source": [
                            "s011"
                        ]
                    }
                ],
                "canRush": false
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
                "botInfo": [
                    {
                        "aiUserID": "-1",
                        "trackNumber": 1
                    },
                    {
                        "aiUserID": "-2",
                        "trackNumber": 2
                    },
                    {
                        "aiUserID": "-3",
                        "trackNumber": 3
                    }
                ],
                "isUnlock": true,
                "powerRequired": 0,
                "hasCleared": true,
                "firstReward": [
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
                "sustainReward": [
                    {
                        "itemID": 1112,
                        "itemName": "8105",
                        "description": "8605",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0005",
                        "source": [
                            "s008"
                        ]
                    },
                    {
                        "itemID": 1122,
                        "itemName": "8107",
                        "description": "8607",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0007",
                        "source": [
                            "s009"
                        ]
                    },
                    {
                        "itemID": 1132,
                        "itemName": "8109",
                        "description": "8609",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0009",
                        "source": [
                            "s011"
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
                "currentMedalAmount": 0,
                "scene": 1001,
                "sceneName": "9001",
                "enviroment": 1,
                "weather": 3,
                "windDirection": 4,
                "windSpeed": 25,
                "lighting": 2,
                "botInfo": [
                    {
                        "aiUserID": "-1",
                        "trackNumber": 1
                    },
                    {
                        "aiUserID": "-2",
                        "trackNumber": 2
                    },
                    {
                        "aiUserID": "-3",
                        "trackNumber": 3
                    }
                ],
                "isUnlock": true,
                "powerRequired": 0,
                "hasCleared": false,
                "firstReward": [
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
                "sustainReward": [
                    {
                        "itemID": 1112,
                        "itemName": "8105",
                        "description": "8605",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0005",
                        "source": [
                            "s008"
                        ]
                    },
                    {
                        "itemID": 1122,
                        "itemName": "8107",
                        "description": "8607",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0007",
                        "source": [
                            "s009"
                        ]
                    },
                    {
                        "itemID": 1132,
                        "itemName": "8109",
                        "description": "8609",
                        "itemType": 1,
                        "useType": 0,
                        "stackLimit": 99999,
                        "icon": "ItemIcon_0009",
                        "source": [
                            "s011"
                        ]
                    }
                ],
                "canRush": false
            }
        ],
        "player": {
            "level": 1,
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
                            "formulaValue": 4.5044,
                            "allRankFormulaValue": [
                                0.9008800000000001,
                                1.8017600000000003,
                                2.70264,
                                3.6035200000000005,
                                4.5044
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
                            "formulaValue": 0.9008800000000001,
                            "allRankFormulaValue": [
                                0.9008800000000001,
                                1.8017600000000003,
                                2.70264,
                                3.6035200000000005,
                                4.5044
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
                            "formulaValue": 2.2522,
                            "allRankFormulaValue": [
                                2.2522,
                                4.5044,
                                6.7566,
                                9.0088,
                                11.261
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
                            "formulaValue": 3.3783,
                            "allRankFormulaValue": [
                                3.3783,
                                6.7566,
                                10.1349,
                                13.5132,
                                16.8915
                            ]
                        },
                        {
                            "type": 201,
                            "formulaValue": 3.04047,
                            "allRankFormulaValue": [
                                3.04047,
                                6.08094,
                                9.121410000000001,
                                12.16188,
                                15.202350000000001
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
                            "formulaValue": 3.3783,
                            "allRankFormulaValue": [
                                3.3783,
                                6.7566,
                                10.1349,
                                13.5132,
                                16.8915
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
                            "formulaValue": 3.3783,
                            "allRankFormulaValue": [
                                3.3783,
                                6.7566,
                                10.1349,
                                13.5132,
                                16.8915
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