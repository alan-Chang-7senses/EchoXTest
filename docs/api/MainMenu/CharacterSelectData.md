# 主介面 - 角色選擇介面資料

## 介紹

- 使用於取得主介面的角色選擇介面資料。
- 需要完成登入驗證才可正常使用此 API。
- 依據所提供參數取得指定範圍內的多個角色資料。
- 提供使用者的角色持有總數。
- 如果只要取得持有角色總數，count 參數可直接填 0。

## URL

http(s)://`域名`/MainMenu/CharacterSelectData/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| offset | int | 偏移量，即從 0 開始的取得資料起始數 |
| count | int | 數量，即此次取得資料的最大數量 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| total | int | 持有角色總數 |
| [players](#players) | array | 角色資料的物件陣列 |

#### <span id="players">players 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號，16 碼 |
| head | string | 頭部代碼（或空字串略過此部位） |
| body | string | 身體代碼（或空字串略過此部位） |
| hand | string | 手部代碼（或空字串略過此部位） |
| leg | string | 腿部代碼（或空字串略過此部位） |
| back | string | 背部代碼（或空字串略過此部位） |
| hat | string | 頭冠代碼（或空字串略過此部位） |
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

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "total": 4,
	    "players": [
	        {
	            "id": 101,
	            "head": "110106",
	            "body": "110106",
	            "hand": "110106",
	            "leg": "110106",
	            "back": "110106",
	            "hat": "110106",
	            "idName": "101",
	            "name": "101",
	            "ele": 1,
	            "sync": 0,
	            "level": 100,
	            "exp": 0,
	            "maxExp": 0,
	            "rank": 1,
	            "velocity": 93.68,
	            "stamina": 89.52,
	            "intelligent": 93.7,
	            "breakOut": 107.31,
	            "will": 95.09,
	            "dune": 12,
	            "craterLake": 0,
	            "volcano": 0,
	            "tailwind": 12,
	            "crosswind": 0,
	            "headwind": 0,
	            "sunny": 12,
	            "aurora": 0,
	            "sandDust": 0,
	            "flat": 12,
	            "upslope": 0,
	            "downslope": 0,
	            "sun": 0,
	            "habit": 1
	        },
	        {
	            "id": 102,
	            "head": "110207",
	            "body": "110207",
	            "hand": "110207",
	            "leg": "110207",
	            "back": "110207",
	            "hat": "110207",
	            "idName": "102",
	            "name": "102",
	            "ele": 2,
	            "sync": 0,
	            "level": 100,
	            "exp": 0,
	            "maxExp": 0,
	            "rank": 1,
	            "velocity": 94.61,
	            "stamina": 89.37,
	            "intelligent": 93.08,
	            "breakOut": 106.38,
	            "will": 95.87,
	            "dune": 12,
	            "craterLake": 0,
	            "volcano": 0,
	            "tailwind": 0,
	            "crosswind": 12,
	            "headwind": 0,
	            "sunny": 0,
	            "aurora": 12,
	            "sandDust": 0,
	            "flat": 0,
	            "upslope": 12,
	            "downslope": 0,
	            "sun": 1,
	            "habit": 1
	        },
	        {
	            "id": 103,
	            "head": "180303",
	            "body": "180309",
	            "hand": "",
	            "leg": "180110",
	            "back": "180208",
	            "hat": "180211",
	            "idName": "103",
	            "name": "103",
	            "ele": 1,
	            "sync": 0,
	            "level": 100,
	            "exp": 0,
	            "maxExp": 0,
	            "rank": 1,
	            "velocity": 85.15,
	            "stamina": 93.89,
	            "intelligent": 91.78,
	            "breakOut": 88.35,
	            "will": 89.28,
	            "dune": 12,
	            "craterLake": 0,
	            "volcano": 0,
	            "tailwind": 2,
	            "crosswind": 4,
	            "headwind": 6,
	            "sunny": 2,
	            "aurora": 4,
	            "sandDust": 6,
	            "flat": 2,
	            "upslope": 4,
	            "downslope": 6,
	            "sun": 0,
	            "habit": 4
	        },
	        {
	            "id": 104,
	            "head": "170302",
	            "body": "170201",
	            "hand": "170201",
	            "leg": "110113",
	            "back": "110112",
	            "hat": "110113",
	            "idName": "104",
	            "name": "104",
	            "ele": 1,
	            "sync": 0,
	            "level": 100,
	            "exp": 0,
	            "maxExp": 0,
	            "rank": 1,
	            "velocity": 101.7,
	            "stamina": 98.13,
	            "intelligent": 103.54,
	            "breakOut": 101.44,
	            "will": 97.19,
	            "dune": 12,
	            "craterLake": 0,
	            "volcano": 0,
	            "tailwind": 6,
	            "crosswind": 2,
	            "headwind": 4,
	            "sunny": 6,
	            "aurora": 3,
	            "sandDust": 3,
	            "flat": 6,
	            "upslope": 3,
	            "downslope": 3,
	            "sun": 0,
	            "habit": 3
	        }
	    ],
	    "processTime": 0.0039899349212646484
	}