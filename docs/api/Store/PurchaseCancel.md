# 商店 - 儲值取消

## 介紹

- 遊戲中儲值狀態，取消訂單購買。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/PurchaseCancel/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| orderID | int | 訂單序號 |

<br>

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
<br>

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}