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
| totalMailsCount | int | 玩家擁有全部信件總數 |

#### <span id="mails">mails 內容</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| userMailID | int | 使用者信件編號 |
| openStatus | int | 信件是否開啟 |
| receiveStatus | int |信件是否已領獎 |
| title | string | 信件標題 |
| content | string | 信件內容 |
| [argus](#argus) | object | 信件參數 |
| sender | string | 信件寄件人 |
| url | string | 信件內容-網址 |
| sendTime | int | 發送時間 (Timestamp) |
| remainingTime | int | 剩餘時間 |
| [rewardItems](#rewardItems) | object| 獎勵物品|
|

#### <span id="argus">argus 內容</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| kind | int | 取代種類<br>1.時間戳 2.多國語序號 |
| value | string | 數值 |
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
				"userMailID": 2,
				"openStatus": 0,
				"receiveStatus": 0,
				"title": "火星幣賽場-「勝者獎金更多」賽季獎勵",
				"content": "大會根據貴賽隊上個賽季的排名與成績，已將屬於您們的賽季獎勵送達，期待能再見到貴賽隊新一季的優秀表現。",
				"argus": [
					{
						"kind": 1,
						"value": "1669286277"
					},
					{
						"kind": 2,
						"value": "多國碼序號123456"
					},
					{
						"kind": 1,
						"value": "123456"
					}
				],
				"sender": "寄件人：研發團隊",
				"url": "",
				"sendTime": 1672070400,
				"remainingTime": 475270576.0674529,
				"rewardItems": [
					{
						"itemID": -4,
						"amount": 5,
						"icon": "ItemIcon_0040"
					},
					{
						"itemID": -2,
						"amount": 3,
						"icon": "ItemIcon_0026"
					},
					{
						"itemID": 4008,
						"amount": 1,
						"icon": "ItemIcon_023_1"
					},
					{
						"itemID": 4009,
						"amount": 1,
						"icon": "ItemIcon_023_2"
					},
					{
						"itemID": 4010,
						"amount": 1,
						"icon": "ItemIcon_023_3"
					}
				]
			},
			{
				"userMailID": 1,
				"openStatus": 0,
				"receiveStatus": 0,
				"title": "<color=#F6D3A4>PetaRush 精彩活動</color>",
				"content": "歡迎來到《PetaRush》，CB2菁英測試期間，官方平台準備許多精彩活動，誠摯邀請您共襄盛舉，凡參加CB2菁英封測，就有機會將各種好禮大獎搬到OB正式版本上使用喔！\n\nCB2慶祝活動\n※\nhttps://lihi1.com/kd459",
				"argus": [],
				"sender": "寄件人：研發團隊",
				"url": "",
				"sendTime": 1671984000,
				"remainingTime": 289329.06745290756,
				"rewardItems": [
					{
						"itemID": 5100,
						"amount": 5,
						"icon": "ItemIcon_0032"
					},
					{
						"itemID": 5201,
						"amount": 10,
						"icon": "ItemIcon_0041"
					}
				]
			}
		],
		"totalMailsCount": 2
	}