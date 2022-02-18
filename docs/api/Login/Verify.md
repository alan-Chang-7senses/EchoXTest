# 登入驗證

## 介紹

- 使用於一般登入。
- 透過提供帳號密碼完成登入驗證。
- 需要玩家身份才能存取資料的 API 功能，須先完成此登入驗證。

## URL

http(s)://`域名`/Login/Verify/

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
| level | int | 等級 |
| exp | int | 經驗值 |
| vitality | int | 體力 |
| money | int | 金錢 |
| player | int | 當前角色 ID |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "userInfo": {
	        "userID": 1,
	        "nickname": "Zhiwei",
	        "level": 1,
	        "exp": 0,
	        "vitality": 0,
	        "money": 0
	        "player": 1010000000000015
	    }
	}