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
| storeID | string | 商店編號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| refreshRemain | int | 剩餘刷新次數 |
| randomPurchase | array | 儲值商店隨機[物品](StoreInfo.md##purchase)(可空) |
| randomCounters | array | 一般商店隨機[物品](StoreInfo.md##counters)(可空) |
<br>

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}