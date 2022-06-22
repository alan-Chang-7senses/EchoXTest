# 競賽 - 能量耗盡獎勵

## 介紹

- 競賽過程中，角色能量剛好耗盡時，可獲得獎勵效果。
- CB2 版本。

## URL

http(s)://`域名`/Races/BonusEnergyRunOut/

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
| h | float | H值 |
| s | float | S值 |
| hp | float | 剩餘體力 |
| duration | float | 有效時間（秒）|

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "h": 1.2516472770190523,
	    "s": 4.6397,
	    "hp": 0,
	    "duration": 5
	}