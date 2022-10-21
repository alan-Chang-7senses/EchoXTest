# 競賽 - 排名

## 介紹

- 當角色排名變更時，由房主發出此功能來更新排名。
- 已經到達終點的角色，排名不會變更。
- 目前名次已改成Server計算。

## URL

http(s)://`域名`/Races/Rankings/

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
| [rankings]($rankings) | array | 排名狀態 |


#### <span id="rankings">rankings 內容</span>
| 鍵值(int) | 內容(int) |
|:-:|:-:|
| 角色編號(PlayerID) | 名次 |

### Example
	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "rankings": {
	        "-3": 1,
	        "-2": 2,
	        "-1": 3,
	        "216": 4
	    }
	}