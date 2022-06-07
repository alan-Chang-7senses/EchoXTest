# 競賽 - 託管角色數值

## 介紹

- 競賽過程中，指定角色的狀態數值更新。
- 更新數值後，取回最新的相關競賽公式計算數值。
- 若不進行狀態數值變更，也可藉此取回角色當前的競賽數值。
- 建議房主專用。

## URL

http(s)://`域名`/Races/HostedPlayerValues/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| player | int | 角色編號 |
| hp | float | 剩餘耐力 |
| [values](#values) | string | 預計更新數值的物件 JSON 字串<br>若僅用於查詢，只需提供空字串 |

#### <span id="values">values 內容</span>

_此欄位資料為物件，以下欄位可擇一提供：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| direction | int | [方向](../codes/race.md#direction) |
| trackType | int | [賽道類別](../codes/race.md#trackType) |
| trackShape | int | [賽道形狀](../codes/race.md#trackShape) |
| rhythm | int | [比賽節奏](../codes/race.md#rhythm) |
| trackNumber | int | 賽道號碼 |
| ranking | int | 排名 |

#### users 範例

	{
		"direction": 1,
		"trackType": 2
	}

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| h | float | H值 |
| s | float | S值 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "h": 1.0774688179425564,
	    "s": 1.4908666666666668
	}