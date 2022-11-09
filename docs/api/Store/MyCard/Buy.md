# 商店 - 儲值購買

## 介紹

- 遊戲中儲值狀態，先建立一筆訂單資訊，以利之後和QuickSDK的溝通。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/MyCard/Buy/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradeID | int | 交易序號 |

<br>

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| orderID | int | 訂單序號 |
| AuthCode | string | MyCard 認證用 |

<br>

### Example

	{
		"error": {
			"code": 0,
			"message": ""
		},
		"orderID": 19,
		"AuthCode": "TestAuthcode"
	}