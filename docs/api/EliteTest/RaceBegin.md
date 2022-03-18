# 菁英測試 - 競賽開局

## 介紹

- 在菁英測試帳號登入後，於競賽開局時，提供參賽玩家編號，並回應該局競賽編號。
- 處理結果同時回傳可進場玩家及不可進場玩家編號。
- 處理過程同時紀錄相關計數。

## URL

http(s)://`域名`/EliteTest/RaceBegin/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| users | string | 參賽玩家編號所組成的 JSON 陣列字串。 |

#### users 範例

	[
		1,
		2,
		3,
		4,
		5,
		6,
		7,
		8
	]

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| raceID | int | 競賽編號，若為 0 ，表示無玩家可進場 |
| ready | array | 可進場玩家編號 |
| unready | array | 不可進場玩家編號（可能還在其它競賽中） |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "raceID": 11,
	    "ready": [
	        1,
	        2,
	        3,
	        7,
	        8
        ],
	    "unready": [
	        4,
	        5,
	        6
	    ]
	}