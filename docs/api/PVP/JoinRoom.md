# 競賽 - 加入房間

## 介紹

- 當玩家進入練習賽(不是晉級賽)，可透過此功能來加入選擇房間。
- 需要完成登入驗證才可正常使用此API。
- 當玩家未有房間狀態下，才可以進行加入。

## URL

http(s)://`域名`/PVP/JoinRoom/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| raceRoomID | int | 房間編號 |

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