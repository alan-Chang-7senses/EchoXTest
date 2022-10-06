# 商店 - 訂單資訊

## 介紹

- 遊戲中儲值的驗證，先記錄玩家購買的商品，儲值完成後再給予購買物品。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/OrderInfo/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradeID | int |  交易序號 |
| buyStatus | int | 購買狀態(1:購買, 2:取消、儲值取消) |
| XXX | int | Quick驗證資訊 |
<br>

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
<br>

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}