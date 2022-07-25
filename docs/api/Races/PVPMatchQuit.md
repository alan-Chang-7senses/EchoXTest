# 競賽 - PVP配對離開

## 介紹

- 當玩家已經配對好房間，但想更換房間，透過此功能來退出已配對房間。

## URL

http(s)://`域名`/Races/PVPMatchQuit/

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
