# 競賽 - 隨機機器人角色

## 介紹

- 於競賽配對時，取得指定數量之機器人角色資料。
- 使用者於競賽中，無法使用此功能。
- 機器人角色的 UserID 等同於 PlayeriD，皆為負值。

## URL

http(s)://`域名`/Races/RandomBotPlayer/

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
| idName | string | 給前端顯示的角色編號字串 |
| nickname | string | 角色暱稱 |
| head | string | 頭部代碼（或空字串略過此部位） |
| body | string | 身體代碼（或空字串略過此部位） |
| hand | string | 手部代碼（或空字串略過此部位） |
| leg | string | 腿部代碼（或空字串略過此部位） |
| back | string | 背部代碼（或空字串略過此部位） |
| hat | string | 頭冠代碼（或空字串略過此部位） |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "players": [
	        {
	            "id": -7,
	            "idName": "Robot-7",
	            "nickname": "aichar0007",
	            "head": "140102",
	            "body": "170304",
	            "hand": "130303",
	            "leg": "140308",
	            "back": "140308",
	            "hat": "110110"
	        },
	        {
	            "id": -37,
	            "idName": "Robot-37",
	            "nickname": "aichar0037",
	            "head": "140308",
	            "body": "110211",
	            "hand": "110112",
	            "leg": "140105",
	            "back": "140301",
	            "hat": "140307"
	        },
	        {
	            "id": -24,
	            "idName": "Robot-24",
	            "nickname": "aichar0024",
	            "head": "110211",
	            "body": "110112",
	            "hand": "140303",
	            "leg": "170304",
	            "back": "110113",
	            "hat": "130301"
	        }
	    ]
	}