# 使用者 - 當前使用者資訊

## 介紹

- 取得使用者帳號的當前資訊。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/User/CurrentInfo/

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
| [info](#info) | object | 使用者資訊 |

#### <span id="info">info 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userID | int | 使用者編號 |
| nickname | string | 暱稱 |
| level | int | 等級 |
| exp | int | 經驗值 |
| petaToken | int | Peta 虛擬幣 |
| coin | int | 遊戲金幣 |
| power | int | 電力 |
| diamond | int | 遊戲鑽石 |
| player | int | 當前角色 ID |
| scene | int | 當前場景 ID |
| race | int | 當前競賽 ID，0 = 非競賽中 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "info": {
	        "userID": 4,
	        "nickname": "test004",
	        "level": 1,
	        "exp": 0,
	        "petaToken": 0,
	        "coin": 0,
	        "power": 0,
	        "diamond": 0,
	        "player": 1010000000000007,
	        "scene": 1,
	        "race": 0
	    }
	}