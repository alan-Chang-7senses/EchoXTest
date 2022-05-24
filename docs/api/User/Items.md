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
| id | int | 使用者物品編號 |
| name | string | 名稱代號 |
| description | string | 物品敘述代號 |
| itemType | int | [物品種類](../codes/item.md#itemType) |
| icon | string | Icon 圖號 |
| stackLimit | int | 堆疊上限（0 = 無法堆疊） |
| useType | int | [使用種類](../codes/item.md#useType) |
| source | array | 來源／出處 代號陣列 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "items": [
	        {
	            "id": 1,
	            "amount": 99,
	            "name": "n0001",
	            "description": "d0001",
	            "itemType": 1,
	            "icon": "1",
	            "stackLimit": 9999,
	            "useType": 1,
	            "source": [
	                "s001",
	                "s002",
	                "s003"
	            ]
	        },
	        {
	            "id": 2,
	            "amount": 50,
	            "name": "n0002",
	            "description": "d0002",
	            "itemType": 2,
	            "icon": "2",
	            "stackLimit": 8888,
	            "useType": 0,
	            "source": [
	                "s002"
	            ]
	        }
	    ],
	    "processTime": 0.0099029541015625
	}