# 競賽 - 排名

## 介紹

- 當角色排名變更時，由房主發出此功能來更新排名。
- 已經到達終點的角色，排名不會變更。

## URL

http(s)://`域名`/Races/Rankings/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [players](#players) | string | 參賽角色所組成的 JSON 陣列字串。 |

#### <span id="players">players 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號 |
| ranking | int | 排名 |

#### players 範例

	[
		{
			"id": 1010000000000015,
			"ranking": 1
		},
		{
			"id": 1010000000000003,
			"ranking": 2
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