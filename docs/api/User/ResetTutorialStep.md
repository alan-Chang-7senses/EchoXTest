# 使用者 - 重置新手引導進度

## 介紹

- 用以重置使用者當前新手引導進度。
- 必須完成登入驗證。
- 只有在後端是 local 或 beta 才有作用。

## URL

http(s)://`域名`/User/UpdateTutorialStep/

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
| [tutorial](#tutorial) | int | [當前的新手引導階段](../codes/tutorial.md#tutorial) |

### Example

#### 成功
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"turotial": 1
	}