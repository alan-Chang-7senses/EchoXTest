# 信箱 - 信件已讀/領獎

## 介紹

- 用於更改玩家單一信件的已讀狀態及領獎狀態。
- 可以領取獎品並將物品放進背包。(未實作)
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/Mails/ReceiveMailsRewards/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| mailsID | int | 信件編號 |
| openStatus | int | 開啟狀態 |
| receiveStatus | int | 領獎狀態 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |

### Example

	{
	    "error":{
	        "code":0,
	        "message":""
	    }
	}