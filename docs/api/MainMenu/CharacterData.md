# 主介面 - 角色資料

## 介紹

- 使用於取得主介面的角色資料。
- 需要完成登入驗證才可正常使用此 API。
- 依據所提供參數 characterID 的值角色資料。

## URL

http(s)://`域名`/MainMenu/CharacterData/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| characterID | int | 角色編號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [creature](#creature) | object | 角色（生物）資訊 |

#### <span id="creature">creature 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號 |
| name | string | 角色暱稱，由使用者命名，若未編輯則與角色編號相同 |
| ele | int | [屬性](../codes/player.md#attr) |
| sync | float | 同步率 |
| level | int | 等級 |
| exp | int | 經驗值 |
| maxExp | int | 下次升級經驗值**（未實作，目前為 null）** |
| rank | int | 階級 |
| velocity | float | 速度 |
| stamina | float | 耐力 |
| intelligent | float | 聰慧 |
| breakOut | float | 爆發 |
| will | float | 鬥志 |
| dune | int | 環境適性 - 沙丘 |
| volcano | int | 環境適性 - 火山 |
| craterLake | int | 環境適性 - 亞湖 |
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
| mid | int | 耐久適性 - 中距離 |
| long | int | 耐久適性 - 長距離 |
| short | int | 耐久適性 - 短距離 |
| [skills](#skills) | array | 角色持有的技能清單陣列 |
| skillHole | array | 技能插槽陣列，陣列長度為插槽數量，陣列元素值為技能編號 |

#### <span id="skills">skills 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 技能編號 |
| name | string | 技能名稱代號 |
| type | int | [觸發類型](../codes/skill.md#triggerType) |
| ranks | array | 技能星級 1 ~ 5 的 N 值陣列 |
| [effects](#effects) | array | 技能效果陣列 |
| [maxEffects](#maxEffects) | array | 滿星技能效果陣列 |

##### <span id="effects">effects 技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [效果類型](../codes/skill.md#effectType) |
| value | int | 效果值**（未實作，目前提供 DB 紀錄之公式或欄位值）** |

##### <span id="maxEffects">maxEffects 滿星技能效果內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | 效果類型 |
| typeValue | int | 效果類型值，依據效果類型，此值代表不同意義 |
| value | int | 效果值**（未實作，目前提供 DB 紀錄之公式或欄位值）**  |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "creature": {
	        "id": 1010000000000015,
	        "name": "1010000000000015",
	        "ele": 1,
	        "sync": 53.47,
	        "level": 1,
	        "exp": 0,
	        "maxExp": null,
	        "rank": 1,
	        "velocity": 30.69,
	        "stamina": 28.74,
	        "intelligent": 30.52,
	        "breakOut": 35.32,
	        "will": 30.45,
	        "dune": 9,
	        "volcano": 2,
	        "craterLake": 1,
	        "sunny": 8,
	        "aurora": 3,
	        "sandDust": 1,
	        "flat": 10,
	        "upslope": 0,
	        "downslope": 2,
	        "sun": 1,
	        "habit": 1,
	        "mid": 10,
	        "long": 0,
	        "short": 2,
	        "skills": [
	            {
	                "id": 1,
	                "name": "name1",
	                "type": 1,
	                "level": 1,
	                "ranks": [
	                    5,
	                    10,
	                    20,
	                    30,
	                    40
	                ],
	                "effects": [
	                    {
	                        "type": 101,
	                        "value": "H-H*N%"
	                    },
	                    {
	                        "type": 101,
	                        "value": "H-H*N%"
	                    }
	                ],
	                "maxEffects": [
	                    {
	                        "type": 1,
	                        "typeValue": 1,
	                        "value": null
	                    },
	                    {
	                        "type": 1,
	                        "typeValue": 1,
	                        "value": "H-H*N%"
	                    }
	                ]
	            },
	            {
	                "id": 2,
	                "name": "name2",
	                "type": 1,
	                "level": 2,
	                "ranks": [
	                    5,
	                    10,
	                    20,
	                    30,
	                    40
	                ],
	                "effects": [
	                    {
	                        "type": 101,
	                        "value": "H-H*N%"
	                    }
	                ],
	                "maxEffects": [
	                    {
	                        "type": 1,
	                        "typeValue": 1,
	                        "value": null
	                    }
	                ]
	            }
	        ],
	        "skillHole": [
	            0,
	            1,
	            0,
	            0,
	            0,
	            2,
	            0,
	            0
	        ]
	    }
	}