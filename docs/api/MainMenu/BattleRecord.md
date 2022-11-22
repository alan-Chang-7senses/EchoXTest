# 主介面 - 戰報資料

## 介紹

- 使用於取得玩家的戰報資料。
- 需要完成登入驗證才可正常使用此 API。
- 依據所提供參數 roomIdList 的值回傳戰報資料。

## URL

http(s)://`域名`/MainMenu/BattleRecord/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| roomIdList | int array | 房間編號陣列 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [records](#records) | array | 紀錄資訊 |
| [players](#players) | array | Peta資訊 |

#### <span id="records">records 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| roomId | int | 房間編號 |
| roomType | int | 比賽房間類別 |
| ranking | int | 名次 |
| playerID | int | 角色ID |
| score | int | 加減分(未實作，目前暫為0) |
| startTime | float | 開跑時間(Unix時間戳記) |
| endTime | float | 結束時間(Unix時間戳記) |
| [useItem](#useItem) | array | 消費的票券 |

#### <span id="useItem">useItem 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemId | int | 道具ID |
| itemIcon | string | 道具ICON名稱 |
| count | int | 消耗數量 |

#### <span id="players">players 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色ID，對應records裡的playerID |
| name | string | 角色名稱代號 |
| head | string | 角色的頭部部件ID |
| body | string | 角色的身體部件ID |
| hand | string | 角色的手部部件ID |
| leg | string | 角色的腳部部件ID |
| back | string | 角色的後背部件ID |
| hat | string | 角色的頭飾部件ID |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "records": [
	        {
	            "roomId": 16,
	            "roomType": 4,
	            "ranking": 8,
	            "playerID": 101,
	            "score": 0,
	            "useItem": [
	                {
	                    "itemId": 5100,
	                    "itemIcon": "ItemIcon_0032",
	                    "count": 1
	                }
	            ],
	            "startTime": "1668764640.927000",
	            "endTime": "1668764861.827500"
	        },
	        {
	            "roomId": 17,
	            "roomType": 1,
	            "ranking": 4,
	            "playerID": 101,
	            "score": 0,
	            "useItem": [
	                {
	                    "itemId": 5100,
	                    "itemIcon": "ItemIcon_0032",
	                    "count": 1
	                }
	            ],
	            "startTime": "1668765646.180100",
	            "endTime": "1668765770.881800"
	        },
	        {
	            "roomId": 18,
	            "roomType": 4,
	            "ranking": 8,
	            "playerID": 101,
	            "score": 0,
	            "useItem": [
	                {
	                    "itemId": 5100,
	                    "itemIcon": "ItemIcon_0032",
	                    "count": 1
	                }
	            ],
	            "startTime": "1669012874.301000",
	            "endTime": "1669013098.280800"
	        }
	    ],
	    "players": [
	        {
	            "id": 101,
	            "name": "101",
	            "head": "110106",
	            "body": "110106",
	            "hand": "110106",
	            "leg": "110106",
	            "back": "110106",
	            "hat": "110106"
	        }
	    ]
	}