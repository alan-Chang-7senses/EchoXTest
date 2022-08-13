# 信箱 - 刪除信件

## 介紹

- 用於刪除玩家單一信件。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/Mails/DeleteMails/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userMailID | int | 使用者信件編號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |

### Example

	{
	    "error":{
	        "code":0,
	        "message":""
	    }
	}