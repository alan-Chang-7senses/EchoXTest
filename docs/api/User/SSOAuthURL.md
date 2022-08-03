# 使用者 - SSO 授權登入網址

## 介紹

- 使用於取得 SSO 授權登入 Metasens 帳號的網址。
- 透過此網址開啟登入頁面，進行登入或註冊帳號。
- 此網址將使用於 UniWebView 來開啟網頁。
- 透過此網頁完成登入將回應登入結果給 UniWebView 的 MessageSystem：
	- LoginFinish：登入完成（可前往大廳）。
	- LoginFirst：登入完成，但還未完成創角（前往創角、取暱稱流程）。

## URL

http(s)://`域名`/User/SSOAuthURL/

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
| url | string | 開啟 SSO 登入的網址 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "url": "https://passport.gamma.metasens.io/v1/oauth/authorize?client_id=95e7029a-b391-43d8-a77b-f23992906397&redirect_uri=http://localhost:37001/callback&response_type=code&scope=&state=h21l0pp0rpdsd40gvmagprmmuo",
	    "processTime": 0.002187013626098633
	}