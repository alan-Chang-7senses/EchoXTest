# 競賽 - 能量再獲得

## 介紹

- 競賽過程中，直接清空能量，給予隨機顏色的能量。
- 限制獲取次數及能量數量上下限。

## URL

http(s)://`域名`/Races/EnergyAgain/

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
| energy | array | 重新獲得的能量值，陣列元素依序為 紅、黃、藍、綠 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "energy": [
	        1,
	        2,
	        3,
	        4
	    ]
	}