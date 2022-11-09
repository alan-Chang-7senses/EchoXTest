# 商店 - 補儲

## 介紹

- 玩家重新登入後，若MyCard SDK發現須補儲則使用此API進行補儲
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/MyCard/Restore/

## Method

`POST`

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
| currencies | array | 按[貨幣](GetInfos.md#Currency)順序 |
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
		]
	}