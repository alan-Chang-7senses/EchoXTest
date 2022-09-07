# 競賽 - 起跑

## 介紹

- 當玩家開局進場準備就緒後，透過此功能紀錄起跑時間。

## URL

http(s)://`域名`/Races/Start/

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