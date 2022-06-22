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

#### <span id="mails">mails 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| mailsID | int | 信件編號 |
| openStatus | int | 信件是否開啟 |
| receiveStatus | int |信件是否已領獎 |
| title | string | 信件標題 |
| content | string | 信件內容 |
| sender | string | 信件寄件人 |
| url | string | 信件內容-網址 |
| remainingTime | int | 剩餘時間 |
| mailsRewards1 | int | 第一個獎項物品編號 |
| mailsRewards1Number1 | int | 第一個獎項數量 |
| mailsRewards1 | int | 第二個獎項物品編號 |
| mailsRewards1Number1 | int | 第二個獎項數量 |
| mailsRewards1 | int | 第三個獎項物品編號 |
| mailsRewards1Number1 | int | 第三個獎項數量 |

### Example

	{
	    "error":{
	        "code":0,
	        "message":""
	    },
	    "Mails":[
	        {
	            "mailsID":2,
	            "openStatus":0,
	            "receiveStatus":0,
	            "title":"歡迎",
	            "content":"歡迎來到PETARUSH",
	            "sender":"寄件人:研發團隊",
	            "url":"",
	            "remainingTime":918911.8287000656,
	            "mailsRewards1":null,
	            "mailsRewards1Number1":null,
	            "mailsRewards2":null,
	            "mailsRewards1Number2":null,
	            "mailsRewards3":null,
	            "mailsRewards1Number3":null
	        }
	    ]
	}