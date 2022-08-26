# 排行榜 - 領先率

## 介紹

- 依照大廳類別提供競賽的領先率排行榜資訊。
- 領先率為百分比之數值，例如 12.34 代表領先率為「12.34%」。

## URL

http(s)://`域名`/Leaderboard/LeadRate/

## Method

`POST`

## Request

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳](../codes/race.md#lobby) （目前只接受 1 和 2） |
| page | int | 頁碼，第 1 頁 page = 1，未提供此參數則使用預設值 1。 |
| length | int | 撈取筆數長度，未提供此參數則使用預設值 12。 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [list](#list) | array | 數據列表陣列 |
| ranking | int | 當前角色排名 |

#### <span id="list"> list 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| ranking | int | 名次 |
| nickname | string | 角色暱稱 |
| tokenName | string | 角色的 NFT 名稱 |
| leadRate | float | 領先率百分比之數值<br>例如 12.34 代表領先率為「12.34%」 |

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
	            "nickname": "1010000000000003",
	            "tokenName": "1010000000000003",
	            "leadRate": 53.33
	        },
	        {
	            "ranking": 2,
	            "nickname": "1010000000000005",
	            "tokenName": "1010000000000005",
	            "leadRate": 46.66
	        },
	        {
	            "ranking": 3,
	            "nickname": "1010000000000001",
	            "tokenName": "1010000000000001",
	            "leadRate": 46.66
	        },
	        {
	            "ranking": 4,
	            "nickname": "1010000000000007",
	            "tokenName": "1010000000000007",
	            "leadRate": 46.66
	        }
	    ],
	    "ranking": 3
	}