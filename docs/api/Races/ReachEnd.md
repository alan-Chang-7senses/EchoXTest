# 競賽 - 抵達終點

## 介紹

- 當角色於競賽中，角色抵達終點時使用此功能。
- 更改使用者當前角色的競賽角色資料狀態為抵達終點。

## URL

http(s)://`域名`/Races/ReachEnd/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

無

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