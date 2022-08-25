# 競賽 - 離開房間

## 介紹

- 當玩家已經加入房間，透過此功能來離開房間。
- 需要完成登入驗證才可正常使用此 API。
- 當玩家已有房號時，才可以離開房間。

## URL

http(s)://`域名`/PVP/LeaveRoom/

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
