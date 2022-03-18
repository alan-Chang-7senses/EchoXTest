# 菁英測試 - 使用者資料

## 介紹

- 在菁英測試帳號登入後，用於取得使用者當前的狀態資料。

## URL

http(s)://`域名`/EliteTest/UserInfo/

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

#### <span id="info"> Info 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者編號 |
| race | int | 當前競賽 ID，0 = 非競賽中 |
| score | int | 積分 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "info": {
	        "id": 1,
	        "race": 0,
	        "score": 0
	    }
	}