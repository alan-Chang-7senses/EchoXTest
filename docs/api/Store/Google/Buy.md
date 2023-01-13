# 商店 - 儲值購買

## 介紹

- 遊戲中儲值狀態，先建立一筆訂單資訊，以利之後和 Google Purchase 的溝通。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/Google/Buy/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradeID | int | 交易序號 |
| productName | string | 產品名稱 |

<br>

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| orderID | string | 訂單序號 |
<br>

### Example

	{
		"error": {
			"code": 0,
			"message": ""
		},
		"orderID": 19,
	}