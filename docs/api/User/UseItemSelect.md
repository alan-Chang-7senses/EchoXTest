# 使用者 - 使用選擇物品

## 介紹

- 使用使用者選擇物品，根據使用者喜好選擇自己想要的獎勵。

## URL

http(s)://`域名`/User/UseItemSelect/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`


### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userItemID | int | 使用者物品編號 |
| amount | int | 數量 |
| selectIndex | int | 選擇序列，由0開始 |
|

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| itemID | int | 物品編號 |
| amount | int | 數量 |
| icon | int | Icon 圖號 |

### Example

#### 成功
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"itemID": 3,
		"amount": 60,
		"icon": "3"
	}