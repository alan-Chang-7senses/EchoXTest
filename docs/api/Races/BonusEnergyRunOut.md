# 競賽 - 能量耗盡獎勵

## 介紹

- 競賽過程中，角色能量剛好耗盡時，可獲得隨機獎勵效果編號。
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
| [effect](#effect) | int | 獲得影響效果 |
| effectValue | float | 獲得影響效果值 |
| [number](#number) | int | 獲得效果編號 |

#### <span id="effect">effect 內容</span>

| 代號 | 類型 | 說明 |
|:-:|:-:|:-:|
| 101 | int | 減少H值消耗 |
| 102 | int | 增加S值 |
| 201 | int | 增加HP |

#### <span id="number">number 內容</span>

| 代號 | 說明 |
|:-:|:-:|
| 1 | 增減S值 => 5 |
| 2 | 增減HP => 35 |
| 3 | 增減H值 => -0.5 |
| 4 | 增減S值 => 1.5 |


### Example
	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "effect": 101,
	    "effectValue": -0.5,
	    "number": 3
	}