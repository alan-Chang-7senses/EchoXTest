# 競賽 - PVP配對

## 介紹

- 當玩家進入遊戲大廳時，透過此功能來建立配對房間。

## URL

http(s)://`域名`/Races/PVPMatch/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳](../codes/race.md#trackType) |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| raceRoomID | int | 房間編號 |
| extraMatchSeconds | int | 開局配對延長等待秒數 |
| maxMatchSeconds | int | 開局配對基本等待秒數 |



### Example
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"raceRoomID": 63,
		"extraMatchSeconds": "120",
		"maxMatchSeconds": "600"
	}
