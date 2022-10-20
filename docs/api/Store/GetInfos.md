# 商店 - 資訊

## 介紹

- 遊戲中各種商城的資訊，提供貨幣交易、資源兌換的需求。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/GetInfos/

## Method

`GET`

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
| currencies | array | 按[貨幣](#Currency)順序 |
| stores | array | [商店資訊](#storeData) |


#### <span id="storeData">商店資訊</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| storeInfoID | int | 商店編號 |
| uiStyle | int | [介面類型](#UIStyle) |
| autoRefreshTime | int | 自動刷新剩餘時間(s) |
| refreshRemain | int | 剩餘刷新次數 |
| refreshMax | int | 每日刷新次數 |
| resetRefreshTime | int | 剩餘刷新次數重置時間(s) |
| refreshCost | int | 刷新費用 |
| refreshCurrency | int | 刷新費用之[貨幣](#Currency) |
| fixPurchase | array | 固定[儲值商店商品](#purchase)(可空) |
| randomPurchase | array | 隨機[儲值商店商品](#purchase)(可空) |
| fixCounters | array | 固定[一般商店商品](#counters)(可空) |
| randomCounters | array | 隨機[一般商店商品](#counters)(可空) |
<br>

#### <span id="purchase">儲值商店商品</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradID | int | 交易序號 |
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
| name | string | 物品名稱 |
| IAP | string | ios product |
| IAB | string | android product |
<br>

#### <span id="counters">一般商店商品</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradID | int | 交易序號 |
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
| name | string | 物品名稱 |
| remainInventory | int | 剩餘庫存數量(-1:不限 0:售罄) |
| price | int | 售價 |
| currency | int | [貨幣](#Currency)種類 |
| promotion | int | 折扣 |
<br>

#### <span id="UIStyle">介面類型</span>
| 編碼 | 定義 |
|:-:|:-:|
| 1 | 固定商品 12 件、隨機商品 0 件 |
| 2 | 固定商品 8 件、隨機商品 4 件 |
| 3 | 固定商品 4 件、隨機商品 8 件 |
| 4 | 固定商品 0 件、隨機商品 12 件 |
<br>

#### <span id="Currency">貨幣</span>
| 編碼 | 定義 |
|:-:|:-:|
| 1 | 火星幣 |
| 2 | 寶石 |
| 3 | PT幣 |
| 4 | 火星幣賽入場券 |
| 5 | PT幣賽入場券 |
| 6 | 群體賽入場券 |
<br>

### Example

	{
		"error": {
			"code": 0,
			"message": ""
		},
		"currencies": [
			0,
			0
		],
		"stores": [
			{
				"storeInfoID": 1,
				"uiStyle": 1,
				"autoRefreshTime": 85116,
				"refreshRemain": 0,
				"refreshMax": 0,
				"resetRefreshTime": 27516,
				"refreshCost": 0,
				"refreshCurrency": 0,
				"fixPurchase": [
					{
						"tradID": 1,
						"itemID": -3,
						"amount": 1,
						"icon": "ItemIcon_0028",
						"name": "8134",
						"IAP": "com.petarush.IAP150",
						"IAB": "exampleSku150"
					},
					{
						"tradID": 7,
						"itemID": -3,
						"amount": 1,
						"icon": "ItemIcon_0028",
						"name": "8134",
						"IAP": "com.petarush.IAP60",
						"IAB": "exampleSku60"
					},
					{
						"tradID": 2,
						"itemID": -3,
						"amount": 1,
						"icon": "ItemIcon_0028",
						"name": "8134",
						"IAP": "com.petarush.IAP240",
						"IAB": ""
					}
				],
				"randomPurchase": [],
				"fixCounters": [],
				"randomCounters": []
			},
			{
				"storeInfoID": 2,
				"uiStyle": 2,
				"autoRefreshTime": 85116,
				"refreshRemain": 6,
				"refreshMax": 6,
				"resetRefreshTime": 27516,
				"refreshCost": 50,
				"refreshCurrency": 1,
				"fixPurchase": [],
				"randomPurchase": [],
				"fixCounters": [
					{
						"tradID": 4,
						"itemID": 1001,
						"amount": 1,
						"icon": "ItemIcon_1001",
						"name": "8101",
						"remainInventory": 3,
						"price": 100,
						"currency": 1,
						"promotion": 3
					}
				],
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
						"amount": 1,
						"icon": "ItemIcon_1001",
						"name": "8101",
						"remainInventory": 3,
						"price": 100,
						"currency": 1,
						"promotion": 3
					}
				]
			},
			{
				"storeInfoID": 3,
				"uiStyle": 3,
				"autoRefreshTime": 85116,
				"refreshRemain": 3,
				"refreshMax": 3,
				"resetRefreshTime": 27516,
				"refreshCost": 100,
				"refreshCurrency": 2,
				"fixPurchase": [],
				"randomPurchase": [],
				"fixCounters": [],
				"randomCounters": []
			},
			{
				"storeInfoID": 4,
				"uiStyle": 4,
				"autoRefreshTime": 85116,
				"refreshRemain": 3,
				"refreshMax": 3,
				"resetRefreshTime": 27516,
				"refreshCost": 200,
				"refreshCurrency": 2,
				"fixPurchase": [],
				"randomPurchase": [],
				"fixCounters": [],
				"randomCounters": []
			}
		]
	}