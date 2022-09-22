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
	    "h": 0.6964231220820043,
	    "s": 13.205215,
	    "hp": 50,
	    "duration": 20,
	    "effect": 101,
	    "effectValue": -0.5,
	    "number": 4
	}