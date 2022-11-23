# 使用者 - 更新新手引導進度

## 介紹

- 用以刷新使用者當前新手引導進度。
- 必須完成登入驗證。

## URL

http(s)://`域名`/User/UpdateTutorialStep/

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
| [tutorial](#tutorial) | int | [當前的新手引導階段](../codes/tutorial.md#tutorial) |
| [rewardItems](#rewardItems) | object| 完成上一個進度獲得的獎勵物品 |

#### <span id="rewardItems">rewardItems 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |

### Example

#### 成功
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"turotial": 1,
		"rewardItems": [
			{
				"itemID": 1001,
				"amount": 10,
				"icon": "ItemIcon_0001"
			},
			{
				"itemID": 1002,
				"amount": 5,
				"icon": "ItemIcon_0002"
			}
		]
	}