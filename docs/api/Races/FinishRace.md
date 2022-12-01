# 競賽 - 完成競賽

## 介紹

- 當角色都到達終點後，透過此功能來完成競賽。
- 完成競賽後，玩家才可再次參與其它競賽。
- 完成競賽將核對前端與後端之角色排名。
- 完成競賽依據賽制及獎勵表發放獎勵，並回傳相關獎勵物品資訊。
- 完成競賽角色排名與經歷時間以後端紀錄為主。
- 完成競賽同時紀錄角色最快與最慢完賽時間。
- 若該賽制有領先率，則同時結算紀錄領先率回傳，否則領先率為 0，領先率為百分比之數值，例如 12.34 代表領先率為「12.34%」。

## URL

http(s)://`域名`/Races/FinishRace/

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
| [users](#users) | array | 各玩家的角色競賽結果資訊陣列 |

#### <span id="users">users 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者編號 |
| nickname | string | 使用者暱稱 |
| player | int | 角色編號 |
| ranking | int | 排名 |
| duration | float | 競賽使用時間 |
| [items](#items) | array | 獲獎物品資訊物件陣列 |
| rate | int | 積分 |

#### <span id="items">items 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 物品編號 |
| icon | string | 物品圖號 |
| amount | int | 物品數量 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "users": [
	        {
	            "id": 1,
	            "nickname": "test001",
	            "player": 1010000000000001,
	            "ranking": 1,
	            "duration": 4.982580184936523,
	            "items": [
	                {
	                    "id": -1,
	                    "icon": "ItemIcon_0029",
	                    "amount": 10
	                }
	            ],
	            "leadRate": 46.66
	        },
	        {
	            "id": 2,
	            "nickname": "test002",
	            "player": 1010000000000003,
	            "ranking": 2,
	            "duration": 4.419580184936523,
	            "items": [
	                {
	                    "id": -1,
	                    "icon": "ItemIcon_0029",
	                    "amount": 10
	                }
	            ],
	            "leadRate": 53.33
	        }
	    ]
	}