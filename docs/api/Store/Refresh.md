# 商店 - 刷新

## 介紹

- 遊戲中商店刷新隨機商品。
- 只更新隨機商品。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/Refresh/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| storeInfoID | int | 商店編號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| currencies | array | 按[貨幣](GetInfos.md#Currency)順序 |
| refreshRemain | int | 剩餘刷新次數 |
| randomPurchase | array | 隨機[儲值商店商品](StoreInfo.md##purchase)(可空) |
| randomCounters | array | 隨機[一般商店商品](StoreInfo.md##counters)(可空) |
<br>

### Example

	{
		"error": {
			"code": 0,
			"message": ""
		},
		"refreshRemain": 4,
		"currencies": [
			799,
			1003,
			999,
			0,
			0,
			1000
		],
		"randomPurchase": [],
		"randomCounters": [
			{
				"tradID": 5,
				"itemID": 1001,
				"amount": 1,
				"icon": "ItemIcon_1001",
				"name": "8101",
				"remainInventory": 3,
				"price": 100,
				"currency": 1,
				"promotion": 3
			},
			{
				"tradID": 6,
				"itemID": 1001,
				"amount": 3,
				"icon": "ItemIcon_1001",
				"name": "8101",
				"remainInventory": 3,
				"price": 100,
				"currency": 1,
				"promotion": 3
			}
		]
	}