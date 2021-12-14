# 主介面 - 主要資料
## 介紹

- 使用於取得主介面的主要資料。
- 需要完成登入驗證才可正常使用此 API。
- 依據所提供參數 characterID 的值取得一個角色資料。
- 若未提供參數 characterID，則依據資料庫中的資料順序取得一個角色資料。

## URL

http(s)://`域名`/MainMenu/MainData/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| characterID | int | 角色編號（可不提供） |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| time | int | 回應時間的 Unix Time |
| name | string | 使用者暱稱 |
| money | int | 金錢 |
| energy | int | 能量 |
| roomMax | int | 開房最大人數 |
| [map](#map) | object | 場景地圖資訊 |
| [player](#player) | object | 角色資訊 |

#### <span id="map">map 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| windDirection | int | 風向 |
| windSpeed | int | 風速 |
| sceneEnv | int | 環境類型 |
| weather | int | 天氣|
| lighting | int | 明暗 |

- 風向：
	- 0 : 東。
	- 1 : 南。
	- 2 : 西。
	- 3 : 北。
- 環境類型：
	- 0：沙丘。
	- 1：亞湖。
	- 2：火山。
- 天氣：
	- 0：晴天。
	- 1：極光。
	- 2：隕石。
- 明暗：
	- 0：日照。
	- 1：背光。

#### <span id="player">player 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號，16 碼 |
| head | string | 頭部代碼 |
| body | string | 身體代碼 |
| hand | string | 手部代碼 |
| leg | string | 腿部代碼 |
| back | string | 背部代碼 |
| hat | string | 頭冠代碼 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "name": "UserNickname",
	    "money": 0,
	    "energy": 0,
	    "roomMax": 8,
	    "map": {
	        "windDirection": 1,
	        "windSpeed": 100,
	        "sceneEnv": 0,
	        "weather": 0,
	        "lighting": 0
	    },
	    "player": {
	        "id": 1010000000000005,
	        "head": "110205",
	        "body": "110101",
	        "hand": "110101",
	        "leg": "110101",
	        "back": "110103",
	        "hat": "110101"
	    }
	}