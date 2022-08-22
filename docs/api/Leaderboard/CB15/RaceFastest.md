# 排行榜 - CB 1.5 - 競賽最快速排行榜

## 介紹

- CB 1.5 期間，提供使用者完賽最快速排行數據。
- 可依指定頁碼提供數據，預設為第 1 頁。
- 可依指定長度提供每次撈取筆數，預設為 12 筆。

## URL

http(s)://`域名`/Leaderboard/CB15/RaceFastest/

## Method

`GET`

## Request

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| page | int | 頁碼，第 1 頁 page = 1，未提供此參數則使用預設值 1。 |
| length | int | 撈取筆數長度，未提供此參數則使用預設值 12。 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [list](#list) | array | 數據列表陣列 |

#### <span id="list"> list 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| ranking | int | 名次 |
| nickname | string | 玩家帳號暱稱 |
| duration | string | 完賽使用時間，格式為「時:分:秒.毫秒」 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "list": [
	        {
	            "ranking": 1,
	            "nickname": "test0001",
	            "duration": "00:00:30.120"
	        },
	        {
	            "ranking": 2,
	            "nickname": "test0002",
	            "duration": "00:00:30.320"
	        }
	    ]
	}
