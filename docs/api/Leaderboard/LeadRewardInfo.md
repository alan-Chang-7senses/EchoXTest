# 排行榜 - 領先率

## 介紹

- 提供目前正在進行中的賽季獎勵資訊。

## URL

http(s)://`域名`/Leaderboard/LeaderBoardRewardInfo/

## Method

`POST`

## Request

### 參數

無

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [rewardInfo](#rewardInfo) | array | 獎勵資訊列表 |

#### <span id="rewardInfo"> rewardInfo 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| seasonId | int | 賽季ID |
| lobby | int | 大廳種類 |
| [rewards](#rewards) | array | 獎勵資訊 |

#### <span id="rewards"> rewards 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| minRank | int | 起始名次 |
| maxRank | int | 結束名次 |
| [items](#items) | array | 獎勵道具資訊 |

#### <span id="items"> items 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 道具ID |
| icon | string | 道具ICON |
| amount | int | 道具數量 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "rewardInfo": [
	        {
	            "seasonId": 1,
	            "lobby": 1,
	            "rewards": [
	                {
	                    "minRank": 1,
	                    "maxRank": 2,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 95000
	                        }
	                    ]
	                },
	                {
	                    "minRank": 3,
	                    "maxRank": 4,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 0
	                        }
	                    ]
	                },
	                {
	                    "minRank": 5,
	                    "maxRank": 7,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 106875
	                        }
	                    ]
	                },
	                {
	                    "minRank": 8,
	                    "maxRank": 10,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 35625
	                        }
	                    ]
	                }
	            ]
	        },
	        {
	            "seasonId": 2,
	            "lobby": 2,
	            "rewards": [
	                {
	                    "minRank": 1,
	                    "maxRank": 3,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 0
	                        }
	                    ]
	                },
	                {
	                    "minRank": 4,
	                    "maxRank": 7,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 118750
	                        }
	                    ]
	                },
	                {
	                    "minRank": 8,
	                    "maxRank": 10,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 63333
	                        }
	                    ]
	                }
	            ]
	        },
	        {
	            "seasonId": 3,
	            "lobby": 4,
	            "rewards": [
	                {
	                    "minRank": 1,
	                    "maxRank": 1,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 63333
	                        }
	                    ]
	                },
	                {
	                    "minRank": 2,
	                    "maxRank": 5,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 7917
	                        }
	                    ]
	                },
	                {
	                    "minRank": 6,
	                    "maxRank": 9,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 0
	                        }
	                    ]
	                },
	                {
	                    "minRank": 10,
	                    "maxRank": 10,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 130625
	                        }
	                    ]
	                }
	            ]
	        },
	        {
	            "seasonId": 4,
	            "lobby": 5,
	            "rewards": [
	                {
	                    "minRank": 1,
	                    "maxRank": 4,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 130625
	                        }
	                    ]
	                },
	                {
	                    "minRank": 5,
	                    "maxRank": 10,
	                    "items": [
	                        {
	                            "itemID": -2,
	                            "icon": "ItemIcon_0026",
	                            "amount": 66500
	                        }
	                    ]
	                }
	            ]
	        }
	    ]
	}