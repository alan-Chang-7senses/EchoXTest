# 使用者 - 當前使用者資訊

## 介紹

- 取得使用者帳號的當前資訊。

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
| vitality | int | 體力 |
| money | int | 金錢 |
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
	        "userID": 1,
	        "nickname": "test001",
	        "level": 1,
	        "exp": 0,
	        "vitality": 0,
	        "money": 0,
	        "player": 1010000000000001,
	        "scene": 1,
	        "race": 7
	    }
	}