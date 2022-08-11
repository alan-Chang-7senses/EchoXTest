# 使用者 - 持有物品

## 介紹

- 根據使用者物品編號，取得使用找可以選擇的獎勵物品資訊。

## URL

http(s)://`域名`/User/GetItemSelectInfo/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數


| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userItemID | int | 使用者物品編號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [items](#items) | array | 物品資訊物件陣列 |

#### <span id="items">items 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
|


### Example

#### 成功
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"items": [
			{
				"itemID": 1,
				"amount": 1,
				"icon": "1"
			},
			{
				"itemID": 2,
				"amount": 2,
				"icon": "2"
			},
			{
				"itemID": 3,
				"amount": 3,
				"icon": "3"
			}
		]
	}