# 使用者 - 玩家創角改名送三隻免費Peta

## 介紹

- 玩家創角送三隻免費Peta與取好的暱稱存入資料庫。

## URL

http(s)://`域名`/User/FreePlayer/SaveFreePlayer/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| nickname | string | 取好的暱稱 |

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