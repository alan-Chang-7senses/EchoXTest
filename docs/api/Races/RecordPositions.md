# 競賽 - 紀錄位置

## 介紹

- 競賽開始之後，由房主定時或不定時發送場內角色位置紀錄於後端資料。
- 所紀錄位置將有利於，角色斷線返回後回到正確位置。

## URL

http(s)://`域名`/Races/RecordPositions/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [positions](#positions) | string | 參賽角色位置資訊所組成的 JSON 陣列字串。 |

#### <span id="positions"> positions 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| player | int | 角色編號 |
| position | string | 位置座標 |

#### players 範例

	[
	    {
	        "player": 1010000000000001,
	        "position": "(-313.2, -96.5, -84.6)"
	    },
	    {
	        "player": -38,
	        "position": "(-327.0, -96.5, -47.4)"
	    }
	]

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}