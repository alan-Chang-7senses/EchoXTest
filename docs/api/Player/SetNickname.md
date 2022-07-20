# 角色 - 設定暱稱

## 介紹

- 設定持有之Peta名稱。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/SetNickname/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號，16 碼 |
| nickname | string | 角色暱稱 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |


### Example

#### 成功

	{
		"error": {
			"code": 0,
			"message": ""
		}
	}