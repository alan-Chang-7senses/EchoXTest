# 競賽 - PVP配對離開

## 介紹

- 當玩家已經配對好房間，但想更換房間，透過此功能來退出已配對房間。
- 需要完成登入驗證才可正常使用此 API。
- 當玩家已在配對狀態下，才可以離開配對。

## URL

http(s)://`域名`/PVP/PVPMatchQuit/

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
