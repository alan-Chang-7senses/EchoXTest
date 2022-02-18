# 競賽 - 開局

## 介紹

- 當玩家準備就緒，即將進入競賽時，透過此功能建立賽局。
- 為每位參與玩家建立競賽角色資料。
- 提供開始競賽的角色初始數據。

## URL

http(s)://`域名`/Races/Ready/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [users](#user1) | string | 餐賽玩家所組成的 JSON 陣列字串。 |
| trackType | int | 跑道型別 |
| trackShape | int | 賽道形狀 |
| direction | int | 角色方向 |

#### <span id="users1">users 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者編號 |
| ranking | int | 排名 |
| trackNumber | int | 賽道號碼 |
| rhythm | int | [比賽節奏](../codes/race.md#rhythm)|

#### users 範例

	[
		{
			"id": 1,
			"ranking": 1,
			"trackNumber": 1,
			"rhythm": 1
		},
		{
			"id": 2,
			"ranking": 1,
			"trackNumber": 2,
			"rhythm": 2
		}
	]
	
## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [users](#users2) | array | 各玩家的角色競賽資料陣列 |

#### <span id="users2">users 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者編號 |
| player | int | 角色編號 |
| energy | array | 能量陣列，依序為 紅,黃,藍,綠 |
| hp | float | 剩餘耐力 |
| s | float | S值 |
| h | float | H值 |
| startSecond | float | 起跑秒數 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "users": [
	        {
	            "id": 1,
	            "player": 1010000000000015,
	            "energy": [
	                "3",
	                "6",
	                "2",
	                "6"
	            ],
	            "hp": 28.74,
	            "s": 5.35038,
	            "h": 0.9962072336265884,
	            "startSecond": 0.05648334845582552
	        },
	        {
	            "id": 2,
	            "player": 1010000000000016,
	            "energy": [
	                "4",
	                "6",
	                "3",
	                "5"
	            ],
	            "hp": 32.65,
	            "s": 3.8337833333333333,
	            "h": 0.6709808588713677,
	            "startSecond": 0.05745645630828441
	        }
	    ]
	}