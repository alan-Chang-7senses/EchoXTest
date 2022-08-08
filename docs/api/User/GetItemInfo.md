# 使用者 - 持有物品

## 介紹

- 根據物品ID，取得基本物品資訊。

## URL

http(s)://`域名`/User/GetItemInfo/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數


| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |

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
| name | string | 名稱代號 |
| description | string | 物品敘述代號 |
| amount | int | 使用者擁有數量 |
| stackLimit | int | 堆疊上限（0 = 無法堆疊） |
| itemType | int | [物品種類](../codes/item.md#itemType) |
| useType | int | [使用種類](../codes/item.md#useType) |
| source | array | 來源／出處 代號陣列 |
|



### Example

#### 成功
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"item": {
			"name": "n0001",
			"description": "d0001",
			"amount": 9999,
			"stackLimit": 9999,
			"itemType": 1,
			"useType": 1,
			"source": [
				"s001",
				"s002",
				"s003"
			]
		}
	}