# 競賽 - 套用能量耗盡獎勵

## 介紹

- 競賽過程中，套用能量耗盡獎勵
- CB2 版本。

## URL

http(s)://`域名`/Races/ApplyEnergyRunOutBonus/

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
	    "h": 1.0878,
	    "s": 18.749565,
	    "hp": 112.61,
	    "duration": 20
	}