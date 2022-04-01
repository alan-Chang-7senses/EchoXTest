# 使用者 - 當前角色

## 介紹

- 使用使用者編號設定使用者的當前角色，並回傳此角色相關資訊。

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

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [player](#player) | object | 角色資訊 |

#### <span id="player">player 內容</span>

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
| level | int | 技能等級 |
| slot | int | 所在插槽位置 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "player": {
	        "id": 1010000000000015,
	        "name": "1010000000000015",
	        "ele": 3,
	        "sync": 0,
	        "level": 1,
	        "exp": 0,
	        "maxExp": null,
	        "rank": 1,
	        "velocity": 30.69,
	        "stamina": 28.74,
	        "intelligent": 30.52,
	        "breakOut": 35.32,
	        "will": 30.45,
	        "dune": 10,
	        "craterLake": 0,
	        "volcano": 2,
	        "tailwind": 7,
	        "crosswind": 3,
	        "headwind": 0,
	        "sunny": 8,
	        "aurora": 3,
	        "sandDust": 1,
	        "flat": 9,
	        "upslope": 2,
	        "downslope": 1,
	        "sun": 2,
	        "habit": 1,
	        "slotNumber": 5,
	        "skills": [
	            {
	                "id": 1,
	                "level": 1,
	                "slot": 2
	            },
	            {
	                "id": 2,
	                "level": 2,
	                "slot": 4
	            },
	            {
	                "id": 4,
	                "level": 1,
	                "slot": 0
	            },
	            {
	                "id": 5,
	                "level": 1,
	                "slot": 0
	            }
	        ],
	        "skillHole": [
	            0,
	            1,
	            0,
	            2,
	            0
	        ]
	    }
	}