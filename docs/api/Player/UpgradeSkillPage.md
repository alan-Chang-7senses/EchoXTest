# 角色 - 提升技能等級頁面

## 介紹

- 進入角色技能升級頁面時，給予所需資訊。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/UpgradeSkillPage/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 角色編號，16 碼 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [skillsData](#skillsData) | array | 角色所有技能升等資訊 |

#### <span id="skillsData">skillsData 內容</span>


| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能ID |
| hasReachedLimit | bool | 技能是否到上升限制 |
| requireCoin | int | 升等所需金幣數量，滿級為null |
| isCoinEnough | bool | 金幣是否足夠升等，，滿級為null |
| [requireItem](#requireItem) | array | 技能升等需求素材，滿級為null | 
| [itemHold](#itemHold) | array | 升等對應道具數量，滿級為null |
| isRequireItemEnough | bool | 持有是否足夠升等，滿級為null |
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

#### <span id="requireItem">requireItem 內容</span>


| 鍵值 | 值 |
|:-:|:-:|
| 道具itemID(int) | 需求數量(int) |
#### <span id="itemHold">itemHold 內容</span>


| 鍵值 | 值 |
|:-:|:-:|
| 道具itemID(int) | 持有數量(int) |


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

### Example 回傳結果
    {
      "error": {
        "code": 0,
        "message": ""
      },
      "skillsData": [
        {
          "id": 4,
          "hasReachedLimit": false,
          "requireCoin": 10000,
          "isCoinEnough": true,
          "requireItem": {
            "2000": 10
          },
          "itemHold": {
            "2000": 0
          },
          "isRequireItemEnough": false,
          "name": "21066",
          "icon": "Skill_Icon_0002",
          "description": "22207",
          "level": 1,
          "slot": 1,
          "energy": [
            0,
            1,
            1,
            0
          ],
          "cooldown": 2,
          "duration": -1,
          "ranks": [
            0.4,
            0.8,
            1.2,
            1.6,
            2
          ],
          "maxDescription": "23307",
          "maxCondition": 21,
          "maxConditionValue": 0,
          "attackedDesc": "22244",
          "effects": [
            {
              "type": 111,
              "formulaValue": 0.34143999999999997,
              "allRankFormulaValue": [
                0.34143999999999997,
                0.6828799999999999,
                1.0243200000000001,
                1.3657599999999999,
                1.7072
              ]
            }
          ],
          "maxEffects": [
            {
              "type": 501,
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
          "id": 11,
          "hasReachedLimit": false,
          "requireCoin": 10000,
          "isCoinEnough": true,
          "requireItem": {
            "2000": 10
          },
          "itemHold": {
            "2000": 0
          },
          "isRequireItemEnough": false,
          "name": "21073",
          "icon": "Skill_Icon_0005",
          "description": "22209",
          "level": 1,
          "slot": 2,
          "energy": [
            0,
            3,
            0,
            2
          ],
          "cooldown": 2,
          "duration": 12.2,
          "ranks": [
            5,
            10,
            15,
            20,
            25
          ],
          "maxDescription": "23191",
          "maxCondition": 42,
          "maxConditionValue": 0,
          "attackedDesc": "23238",
          "effects": [
            {
              "type": 113,
              "formulaValue": 4.0995,
              "allRankFormulaValue": [
                4.0995,
                8.199,
                12.298499999999999,
                16.398,
                20.4975
              ]
            }
          ],
          "maxEffects": [
            {
              "type": 102,
              "target": 5,
              "formulaValue": -0.5,
              "allRankFormulaValue": [
                -0.5,
                -1,
                -1.5,
                -2,
                -2.5
              ]
            }
          ]
        },
        {
          "id": 36,
          "hasReachedLimit": false,
          "requireCoin": 10000,
          "isCoinEnough": true,
          "requireItem": {
            "2000": 10
          },
          "itemHold": {
            "2000": 0
          },
          "isRequireItemEnough": false,
          "name": "21098",
          "icon": "Skill_Icon_0001",
          "description": "22207",
          "level": 1,
          "slot": 3,
          "energy": [
            0,
            4,
            0,
            0
          ],
          "cooldown": 2,
          "duration": 4.8,
          "ranks": [
            8,
            16,
            24,
            32,
            40
          ],
          "maxDescription": "22207",
          "maxCondition": 12,
          "maxConditionValue": 0,
          "attackedDesc": "",
          "effects": [
            {
              "type": 111,
              "formulaValue": 6.8288,
              "allRankFormulaValue": [
                6.8288,
                13.6576,
                20.4864,
                27.3152,
                34.144
              ]
            }
          ],
          "maxEffects": [
            {
              "type": 111,
              "target": 0,
              "formulaValue": 13.6576,
              "allRankFormulaValue": [
                13.6576,
                27.3152,
                40.9728,
                54.6304,
                68.288
              ]
            }
          ]
        },
        {
          "id": 55,
          "hasReachedLimit": false,
          "requireCoin": 10000,
          "isCoinEnough": true,
          "requireItem": {
            "2000": 10
          },
          "itemHold": {
            "2000": 0
          },
          "isRequireItemEnough": false,
          "name": "21117",
          "icon": "Skill_Icon_0003",
          "description": "22208",
          "level": 1,
          "slot": 4,
          "energy": [
            3,
            0,
            0,
            0
          ],
          "cooldown": 2,
          "duration": 3.6,
          "ranks": [
            6,
            12,
            18,
            24,
            30
          ],
          "maxDescription": "22212",
          "maxCondition": 62,
          "maxConditionValue": 0,
          "attackedDesc": "",
          "effects": [
            {
              "type": 112,
              "formulaValue": 5.1216,
              "allRankFormulaValue": [
                5.1216,
                10.2432,
                15.3648,
                20.4864,
                25.608
              ]
            }
          ],
          "maxEffects": [
            {
              "type": 102,
              "target": 0,
              "formulaValue": 0.5148,
              "allRankFormulaValue": [
                0.5148,
                1.0296,
                1.5444,
                2.0592,
                2.574
              ]
            }
          ]
        },
        {
          "id": 80,
          "hasReachedLimit": false,
          "requireCoin": 10000,
          "isCoinEnough": true,
          "requireItem": {
            "2000": 10
          },
          "itemHold": {
            "2000": 0
          },
          "isRequireItemEnough": false,
          "name": "21142",
          "icon": "Skill_Icon_0005",
          "description": "22209",
          "level": 1,
          "slot": 0,
          "energy": [
            1,
            0,
            0,
            1
          ],
          "cooldown": 2,
          "duration": 5,
          "ranks": [
            2,
            4,
            6,
            8,
            10
          ],
          "maxDescription": "22208",
          "maxCondition": 22,
          "maxConditionValue": 0,
          "attackedDesc": "",
          "effects": [
            {
              "type": 113,
              "formulaValue": 1.6398,
              "allRankFormulaValue": [
                1.6398,
                3.2796,
                4.9193999999999996,
                6.5592,
                8.199
              ]
            }
          ],
          "maxEffects": [
            {
              "type": 112,
              "target": 0,
              "formulaValue": 3.4144,
              "allRankFormulaValue": [
                3.4144,
                6.8288,
                10.2432,
                13.6576,
                17.072
              ]
            }
          ]
        },
        {
          "id": 81,
          "hasReachedLimit": false,
          "requireCoin": 10000,
          "isCoinEnough": true,
          "requireItem": {
            "2000": 10
          },
          "itemHold": {
            "2000": 0
          },
          "isRequireItemEnough": false,
          "name": "21143",
          "icon": "Skill_Icon_0009",
          "description": "22211",
          "level": 1,
          "slot": 0,
          "energy": [
            0,
            0,
            0,
            1
          ],
          "cooldown": 2,
          "duration": 3,
          "ranks": [
            1,
            2,
            3,
            4,
            5
          ],
          "maxDescription": "20002",
          "maxCondition": 1,
          "maxConditionValue": 2,
          "attackedDesc": "",
          "effects": [
            {
              "type": 115,
              "formulaValue": 0.8023,
              "allRankFormulaValue": [
                0.8023,
                1.6046,
                2.4069,
                3.2092,
                4.011500000000001
              ]
            },
            {
              "type": 201,
              "formulaValue": 0.72207,
              "allRankFormulaValue": [
                0.72207,
                1.44414,
                2.16621,
                2.88828,
                3.610350000000001
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
        }
      ]
    }