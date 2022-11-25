# 商店 - 儲值刷新

## 介紹

- 儲值完成後，需等待MyCard完成訂單購買，後端查詢是否完成交易。
- 由此API得知訂單購買狀態，若[錯誤碼](../../codes/errorCode.md)為 8009 則定期輪詢，直到完成購買。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/MyCard/Refresh/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| orderID | string | 訂單序號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| currencies | array | 按[貨幣](GetInfos.md#Currency)順序 |
| tradID | int | 商品流水號 |
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
    	"tradeID": 34,
		"remainInventory": -1
	}