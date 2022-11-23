# 串接 - Metasens - 用戶是否存在

## 介紹

- 提供 Metasens 確認指定用戶是否存在。
- 詢問結果：
	- 用戶已存在：HTTP Status code 200，error code 0。
	- 用戶不存在：HTTP Status code 200，error code 2001。
	- 其它：參照 [HTTP 狀態碼](../../codes/httpCode.md) 與  [API 錯誤碼](../../codes/errorCode.md)。

## URL

### Beta
https://petarush-api-i6jbglt3vq-de.a.run.app/Interfaces/Metasens/UserExist

### Gamma
https://petarush-api-test-i6jbglt3vq-de.a.run.app/Interfaces/Metasens/UserExist

### Production
https://petarush-api-prod-i6jbglt3vq-de.a.run.app/Interfaces/Metasens/UserExist

## 驗證

使用 PetaRush 串接 NFT 所申請的 id 及 api secret 來組成驗證碼：

### 驗證流程

1. id: `12345`，api secret: `password`。
2. `base64_encode('12345:password')`：`YWxpY2U6c3VwZXJtYW4=`。
3. HTTP request header: `Authorization: Basic YWxpY2U6c3VwZXJtYW4=`。

### SSO 與 Game 環境對應

- Game Beta => SSO Gamma。
- Game Gamma => SSO Production。
- Game Production => SSO Production。

## Method

`GET`／`POST`

## Payload

### 參數欄位

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| email | string | 用戶信箱 |

### Example

	{
	  "emaiil": "zhiwei.lian@7senses.com"
	}
	
## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../../response.md#error)） |
| [data](#data) | object | 結果資料 |

#### <span id="data"> data 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userID | int | 遊戲內的用戶編號 |
| ssoID | string | 用戶透過 SSO 登入所獲得的 id |
| nickname | string | 用戶自訂暱稱，若用戶還未完成自訂暱稱，此值與 ssoID 相同 |

### Example

#### 用戶已存在

*HTTP Status code 200*

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "data": {
	        "userID": 31,
	        "ssoID": "96e279a6-ac83-4480-ae31-358cde8af3d7",
	        "nickname": "Zhiwei"
	    }
	}

#### 用戶不存在

*HTTP Status code 200*

	{
	    "error": {
	        "code": 2001,
	        "message": "User zhiwei.lain@7senses.com does not exist."
	    }
	}

#### 請求失敗

*HTTP Status code 400*
	
	{
	    "error": {
	        "code": 26,
	        "message": "The property 'email' not exist"
	    }
	}