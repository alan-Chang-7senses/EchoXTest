# 使用者 - 使用物品

## 介紹

- 使用物品，需用使用者索引ID才可使用。

## URL

http(s)://`域名`/User/UseItems/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`


### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userItemID | int | 使用者物品編號 |
| amount | int | 數量 |


## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [addItems](#addItems) | array | 獲得物品陣列 |

#### <span id="addItems">addItems 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者物品編號 |
| amount | int | 物品數量 |

### Example

#### 成功

	{
		"error": {
			"code": 0,
			"message": ""
		},
		"addItems": [
			{
				"itemID": 1,
				"amount": 2,
				"icon": "1"
			},
			{
				"itemID": 2,
				"amount": 4,
				"icon": "2"
			},
			{
				"itemID": 3,
				"amount": 6,
				"icon": "3"
			}
		]
	}