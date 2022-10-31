# 商店 - 商品購買

## 介紹

- 遊戲中一般商品的購買。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/BuyGoods/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradeID | int |  交易序號 |
<br>

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| currencies | array | 按[貨幣](GetInfos.md#Currency)順序 |
| remainInventory | int | 剩餘庫存數量 |
<br>

### Example

	{
		"error": {
			"code": 0,
			"message": ""
		},
		"currencies": [
			699,
			1005,
			999,
			0,
			0,
			1001
		],
		"remainInventory": 1
	}