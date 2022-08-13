# 使用者 - 持有物品

## 介紹

- 取得使用者持有物品資訊。

## URL

http(s)://`域名`/User/Items/

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
| [items](#items) | array | 物品資訊物件陣列 |

#### <span id="items">items 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| userItemsID | int | 使用者物品編號 |
| amount | int | 物品數量 |
| useType | int | [使用種類](../codes/item.md#useType) |
| itemType | int | [物品種類](../codes/item.md#itemType) |
| icon | string | Icon 圖號 |

### Example

#### 成功
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"items": [
			{
				"itemID": 5100,
				"userItemsID": 4,
				"amount": 1,
				"useType": 0,
				"itemType": 5,
				"icon": "ItemIcon_0032"
			},
			{
				"itemID": 1003,
				"userItemsID": 8,
				"amount": 9,
				"useType": 1,
				"itemType": 1,
				"icon": "ItemIcon_1003"
			}
		]
	}