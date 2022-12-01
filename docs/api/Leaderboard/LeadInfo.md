# 排行榜 - 領先率

## 介紹

- 提供目前正在進行中的賽季獎勵資訊。

## URL

http(s)://`域名`/Leaderboard/LeaderBoardRatingInfo/

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
| [leaderBoards](#leaderBoards) | array | 排名資訊列表 |

#### <span id="leaderBoards"> leaderBoards 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 資料序號 |
| group | int | 排行榜主項群組ID |
| mainLeaderboardTitle | string | 主榜單字串 |
| subLeaderboardTitle | string | 子榜單字串 |
| ruleHint | string | 榜單規則字串 |
| ratingTarget | int | 計分類型(0：角色, 1：玩家) |
| rankRuleHint | int | 排名基準HINT字串 |
| [rewards](#rewards) | array | 獎勵資訊列表 |

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
	    "leaderBoards": [
	        {
	            "id": 3,
	            "group": 2,
	            "mainLeaderboardTitle": "CoinA",
	            "subLeaderboardTitle": "SubCoinA",
	            "ruleHint": "CoinARule",
	            "ratingTarget": 0,
	            "rankRuleHint": "HintCoinA",
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
	            "id": 4,
	            "group": 2,
	            "mainLeaderboardTitle": "PointA",
	            "subLeaderboardTitle": "SubPointA",
	            "ruleHint": "PointARule",
	            "ratingTarget": 0,
	            "rankRuleHint": "HintPointA",
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
	            "id": 5,
	            "group": 3,
	            "mainLeaderboardTitle": "CoinB",
	            "subLeaderboardTitle": "SubCoinB",
	            "ruleHint": "CoinBRule",
	            "ratingTarget": 0,
	            "rankRuleHint": "HintCoinB",
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
	            "id": 6,
	            "group": 3,
	            "mainLeaderboardTitle": "PointB",
	            "subLeaderboardTitle": "SubPointB",
	            "ruleHint": "PointBRule",
	            "ratingTarget": 0,
	            "rankRuleHint": "HintPointB",
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