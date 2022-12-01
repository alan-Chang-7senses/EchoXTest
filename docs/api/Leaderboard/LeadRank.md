# 排行榜 - 積分

## 介紹

- 提供競賽所有房間的排行榜資訊。

## URL

http(s)://`域名`/Leaderboard/LeaderBoardUserRanking/

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
| [rankInfo](#rankInfo) | array | 各房間的排行資訊 |
| [totalrankInfo](#totalrankInfo) | object | 總積分的排行榜資訊 |

#### <span id="rankInfo"> rankInfo 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [rankInfo](#rank) | array | 名次資訊 |
| [ownRank](#rank) | object | Peta的排名資訊(若玩家正在使用的PETA未比賽過，則無此欄) |
| lobby | int | 房間種類 |

#### <span id="totalrankInfo"> totalrankInfo 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [rankInfo](#rank) | array | 名次資訊 |
| [ownRank](#rank) | object | 玩家的排名資訊(若玩家所有PETA都未比賽過，則無此欄) |

#### <span id="rank"> rank結構內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userId | int | 使用者ID |
| petaId | int | Peta ID |
| petaName | string | Peta暱稱 |
| rate | int | 積分 |
| rank | int | 排名 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "rankInfo": [
	        {
	            "rankInfo": [
	                {
	                    "id": 1510,
	                    "rate": 9999,
	                    "rank": 1
	                },
	                {
	                    "id": 1157,
	                    "rate": 9962,
	                    "rank": 2
	                },
	                {
	                    "id": 1609,
	                    "rate": 9903,
	                    "rank": 3
	                },
	                .
	                .
	                .
	            ],
	            "ownRank": {
	                "id": 101,
	                "rate": 5179,
	                "rank": 139
	            },
	            "lobby": 1
	        },
	        {
	            "rankInfo": [
	                {
	                    "id": 703,
	                    "rate": 9947,
	                    "rank": 1
	                },
	                {
	                    "id": 405,
	                    "rate": 9932,
	                    "rank": 2
	                },
	                {
	                    "id": 910,
	                    "rate": 9929,
	                    "rank": 3
	                },
	                .
	                .
	                .
	            ],
	            "ownRank": {
	                "id": 101,
	                "rate": 5898,
	                "rank": 109
	            },
	            "lobby": 2
	        },
	        {
	            "rankInfo": [
	                {
	                    "id": 2407,
	                    "rate": 9962,
	                    "rank": 1
	                },
	                {
	                    "id": 2501,
	                    "rate": 9941,
	                    "rank": 2
	                },
	                {
	                    "id": 1110,
	                    "rate": 9910,
	                    "rank": 3
	                },
	                .
	                .
	                .
	            ],
	            "ownRank": {
	                "id": 101,
	                "rate": 86,
	                "rank": 306
	            },
	            "lobby": 4
	        },
	        {
	            "rankInfo": [
	                {
	                    "id": 610,
	                    "rate": 9995,
	                    "rank": 1
	                },
	                {
	                    "id": 621,
	                    "rate": 9988,
	                    "rank": 2
	                },
	                {
	                    "id": 1617,
	                    "rate": 9937,
	                    "rank": 3
	                },
	                .
	                .
	                .
	            ],
	            "ownRank": {
	                "id": 101,
	                "rate": 9772,
	                "rank": 5
	            },
	            "lobby": 5
	        }
	    ],
	    "totalrankInfo": {
	        "rankInfo": [
	            {
	                "id": 11,
	                "rate": 985088,
	                "rank": 1
	            },
	            {
	                "id": 3,
	                "rate": 89878,
	                "rank": 2
	            },
	            {
	                "id": 10,
	                "rate": 89601,
	                "rank": 3
	            },
	            .
	            .
	            .
	        ],
	        "ownRank": {
	            "id": 1,
	            "rate": 80446,
	            "rank": 8
	        }
	    }
	}