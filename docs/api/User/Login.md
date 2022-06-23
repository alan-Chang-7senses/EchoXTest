# 使用者 - 登入

## 介紹

- 使用於一般登入。
- 透過提供帳號密碼完成登入驗證。
- 需要玩家身份才能存取資料的 API 功能，須先完成此登入驗證。
- 每個使用者僅限一個登入身份，重複登入則後踢前。

## URL

http(s)://`域名`/User/Login/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| account | string | 使用者帳號，限制為 4 ~ 16 碼的英數字。 |
| password | string | 使用者密碼，限制為 4 ~ 16 碼字元。 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [userInfo](#userInfo) | object | 使用者資訊 |

#### <span id="userInfo">userInfo 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userID | int | 使用者編號 |
| nickname | string | 暱稱 |
| player | int | 當前角色 ID |
| race | int | 當前競賽 ID，0 = 非競賽中 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "userInfo": {
	        "userID": 1,
	        "nickname": "test001",
	        "player": 1010000000000001,
	        "race": 3
	    }
	}