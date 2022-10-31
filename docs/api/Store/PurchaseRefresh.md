# 商店 - 儲值刷新

## 介紹

- 儲值完成後，需等待QuickSDK完成訂單購買，並回調後端完成加入物品資訊。
- 由此API得知訂單購買狀態，若[錯誤碼](../response.md#error)為 8009 則定期輪詢，直到完成購買。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/PurchaseRefresh/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| orderID | int | 訂單序號 |

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
			799,
			1005,
			999,
			0,
			0,
			1000
		],
		"remainInventory": -1
	}