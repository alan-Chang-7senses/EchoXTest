# 競賽 - 練習賽房間

## 介紹

- 在練習賽配對後，競賽開局前，使用此 API 設定玩家的配對房間。
- 所記錄之房間可用於斷線恢復資料。

## URL

http(s)://`域名`/Races/StudyRoom/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| room | int | 房間編號（10 碼以內之負值） |

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