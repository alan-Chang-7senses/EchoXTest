# 信箱 - 信件已讀/領獎

## 介紹

- 用於更改玩家單一信件的已讀狀態及領獎狀態。
- 可以領取獎品並將物品放進背包。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/Mails/ReceiveMailsRewards/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userMailID | string | 信件編號所組成的 JSON 陣列字串  |
| openStatus | int | 開啟狀態(0:關閉, 1:開啟) |
| receiveStatus | int | 領獎狀態(0:未領取, 1:已領取) |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| openStatus | int | 開啟狀態(0:關閉, 1:開啟) |
| receiveStatus | int | 領獎狀態(0:未領取, 1:已領取) |
| [rewardItems](#rewardItems) | object| 獎勵物品(已累加，物品不重複)|
| errorCode | int | 領獎錯誤訊息(0:正確, 5007:信件不存在, 5008:信件道具已領取, 5006:信件道具超過堆疊上限) |

#### <span id="rewardItems">rewardItems 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
|

### Example 1
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"openStatus": 1,
		"receiveStatus": "1",
		"rewardItems": [
			{
				"itemID": -4,
				"amount": 5,
				"icon": "ItemIcon_0040"
			},
			{
				"itemID": -2,
				"amount": 3,
				"icon": "ItemIcon_0026"
			},
			{
				"itemID": 4008,
				"amount": 1,
				"icon": "ItemIcon_023_1"
			},
			{
				"itemID": 4009,
				"amount": 1,
				"icon": "ItemIcon_023_2"
			},
			{
				"itemID": 4010,
				"amount": 1,
				"icon": "ItemIcon_023_3"
			}
		],
		"errorCode": 0
	}

### Example 2
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"openStatus": 1,
		"receiveStatus": "1",
		"rewardItems": [],
		"errorCode": 5006
	}
