# 競賽 - 抵達終點

## 介紹

- 更改指定角色的競賽角色資料狀態為抵達終點。
- 競賽中，當角色抵達終點時，由房主發出使用此功能。
- 該角色必須與房主在相同賽局中。
- 回傳抵達終點排名及參賽總人數，以確認是否該完賽。

## URL

http(s)://`域名`/Races/ReachEnd/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| player | int | 角色編號 |
| distance | float | 移動距離 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [reach](#reach) | object | 抵達終點資料 |

#### <span id="reach"> reach 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| total | int | 參賽總人數 |
| ranking | int | 抵達終點排名 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "reach": {
	        "total": 4,
	        "ranking": 3
	    }
	}