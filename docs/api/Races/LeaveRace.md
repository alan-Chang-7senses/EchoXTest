# 競賽 - 離開競賽

## 介紹

- 當角色斷線返回沒有正常回到競賽中，可透過此功能來離開競賽。
- 離開競賽視同放棄比賽，不會扣除入場卷，於該場競賽完成後也不會獲得任何獎勵或影響領先率及排名等數值。
- 若參與競賽已完賽，此功能無效，並回覆完賽。

## URL

http(s)://`域名`/Races/LeaveRace/

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

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}