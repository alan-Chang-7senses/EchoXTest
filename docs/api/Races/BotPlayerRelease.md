# 競賽 - 釋放機器人角色

## 介紹

- 當撈取 [機器人角色](BotPlayer.md) 後，不使用於進入競賽，則需使用此功能將角色釋放，以提供其他賽局配對取得。
- 僅限機器人角色適用此功能。
- 僅限狀態為「配對中」適用此功能。
- 每次僅能釋放一個角色。

## URL

http(s)://`域名`/Races/BotPlayerRelease/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 機器人使用者編號（角色編號） |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| id | int | 被釋放的使用者編號（角色編號） |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "id": "-36",
	}