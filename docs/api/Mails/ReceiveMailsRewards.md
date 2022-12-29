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
|

#### <span id="rewardItems">rewardItems 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
|

### Example
	{
		"error": {
			"code": 0,
			"message": ""
		},
   		"openStatus": 1,
	    "receiveStatus": "1",
		"rewardItems": [
			{
				"itemID": 1001,
				"amount": 1,
				"icon": "ItemIcon_1001"
			},
			{
				"itemID": 1002,
				"amount": 2,
				"icon": "ItemIcon_1002"
			},
			{
				"itemID": 1003,
				"amount": 3,
				"icon": "ItemIcon_1003"
			}
		]
	}
