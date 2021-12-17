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
| [error](../index.md#error) | object | 錯誤代碼與訊息 |
| [creature](#creature) | object | 角色（生物）資訊 |

#### <span id="creature">creature 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號 |
| name | string | 角色暱稱，由使用者命名，若未編輯則與角色編號相同 |
| [ele](#ele) | int | 屬性 |
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
| sunny | int | 天氣適性 - 晴天 |
| aurora | int | 天氣適性 - 極光 |
| sandDust | int | 天氣適性 - 沙塵 |
| flat | int | 地形適性 - 平地 |
| upslope | int | 地形適性 - 上坡 |
| downslope | int | 地形適性 - 下坡 |
| [sun](#sun) | int | 太陽適性 |
| [habit](#habit) | int | 比賽習慣 |
| mid | int | 耐久適性 - 中距離 |
| long | int | 耐久適性 - 長距離 |
| short | int | 耐久適性 - 短距離 |
| [talent](#talent) | object | 天賦**（未實作，目前無此欄位）** |
| [skillEquip](#skillEquip) | object | 技能裝備**（未實作，目前無此欄位）** |
| skillHole | array | 技能槽**（未實作，目前無此欄位）** |

- <span id="ele">**ele 屬性**</span>
	- 0：虛無，特殊的存在。
	- 1：火。
	- 2：水。
	- 3：木。
	- 4：風。
	- 5：雷。
	- 6：土。
	- 7：光明。
	- 8：黑暗。
	- 9：渾沌。

- <span id="sun">**sun 太陽適性**</span>
	- 0：一般。
	- 1：日行性。
	- 2：夜行性。

- <span id="habit">**habit 比賽習慣**</span>
	- 1：狂衝。
	- 2：穩定。
	- 3：優先。
	- 4：蓄力。

#### <span id="talent">talent 內容</span>

> 未實作

#### <span id="skillEquip"> skillEquip 內容</span>

> 未實作

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "creature": {
	        "id": 1010000000000015,
	        "name": 1010000000000015,
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
	        "sun": 0,
	        "habit": 1,
	        "mid": 10,
	        "long": 0,
	        "short": 2
	    }
	}