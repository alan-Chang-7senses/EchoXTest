# 信箱 - 查詢信件

## 介紹

- 用於查詢玩家所有信件。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/Mails/GetMails/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lang | int | 多國語言 |
## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [Mails](#mails) | object | 信件資訊 |
|

#### <span id="mails">mails 內容</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userMailID | int | 使用者信件編號 |
| openStatus | int | 信件是否開啟 |
| receiveStatus | int |信件是否已領獎 |
| title | string | 信件標題 |
| content | string | 信件內容 |
| sender | string | 信件寄件人 |
| url | string | 信件內容-網址 |
| remainingTime | int | 剩餘時間 |
| [rewardItems](#rewardItems) | object| 獎勵物品|
|

#### <span id="rewardItems">rewardItems 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
|
### Example
	{
		"error": {
			"code": 0,
			"message": ""
		},
		"Mails": [
			{
				"userMailID": 13,
				"openStatus": 0,
				"receiveStatus": 0,
				"title": "歡迎來到《PetaRush》",
				"content": "歡迎來到《PetaRush》誠摯邀請你一同來參與《動物大奔走》這個Peta的大型盛事！",
				"sender": "寄件人：研發團隊",
				"url": "",
				"remainingTime": 1199597.5609428883,
				"rewardItems": [
					{
						"itemID": 1001,
						"amount": 1,
						"icon": "ItemIcon_1001"
					},
					{
						"itemID": 1002,
						"amount": 2,
						"icon": "ItemIcon_1002"
					},
					{
						"itemID": 1003,
						"amount": 3,
						"icon": "ItemIcon_1003"
					}
				]
			},
			{
				"userMailID": 15,
				"openStatus": 0,
				"receiveStatus": 0,
				"title": "歡迎來到《PetaRush》",
				"content": "歡迎來到《PetaRush》誠摯邀請你一同來參與《動物大奔走》這個Peta的大型盛事！",
				"sender": "寄件人：研發團隊",
				"url": "",
				"remainingTime": 596658.5609428883,
				"rewardItems": []
			}
		]
	}