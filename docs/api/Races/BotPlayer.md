# 競賽 - 機器人角色

## 介紹

- 於競賽配對時，取得指定數量之機器人角色資料。
- 使用者於競賽中，無法使用此功能。
- 被取得的機器人角色帳號狀態將變更為「配對中」，無法再被取得。
- 若不使用取得的機器人角色進入競賽，需再使用 [釋放機器人角色](BotPlayerRelease.md) 將其釋放。
- 機器人角色的 UserID 等同於 PlayeriD，皆為負值。

## URL

http(s)://`域名`/Player/BotPlayer/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| amount | int | 欲取得的機器人數量 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [players](#players) | array | 各機器人角色的資料陣列 |

#### <span id="players"> players 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號（同使用者編號） |
| name | string | 角色暱稱 |
| head | string | 頭部代碼 |
| body | string | 身體代碼 |
| hand | string | 手部代碼 |
| leg | string | 腿部代碼 |
| back | string | 背部代碼 |
| hat | string | 頭冠代碼 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "players": [
	        {
	            "id": -37,
	            "name": "aichar0037",
	            "head": "110102",
	            "body": "110101",
	            "hand": "110101",
	            "leg": "110101",
	            "back": "110104",
	            "hat": "110103"
	        },
	        {
	            "id": -36,
	            "name": "aichar0036",
	            "head": "110101",
	            "body": "110104",
	            "hand": "110101",
	            "leg": "110101",
	            "back": "110101",
	            "hat": "110205"
	        },
	        {
	            "id": -35,
	            "name": "aichar0035",
	            "head": "110101",
	            "body": "110103",
	            "hand": "110101",
	            "leg": "110101",
	            "back": "110101",
	            "hat": "110103"
	        }
	    ],
	}