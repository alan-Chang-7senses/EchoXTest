# 競賽 - 獲取入場卷資訊

## 介紹

- 取得開放晉級賽的入場卷資訊。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVP/GetTicketsInfo/

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
| tickets | object | [入場卷資訊](#ticket) |
<br>

#### <span id="ticket">ticket 入場卷資訊 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳種類](../codes/race.md#lobby) |
| ticketID | int | 入場卷物品編號 |
| ticketIcon | string | 入場卷物品圖號 |
| amount | int | 已有物品數量 |
| maxReceive | int | 領取上限 |
| receiveRemainTime | int | 剩餘可領領時間(秒) |
<br>


### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "tickets": [
            {
                "lobby": 1,
                "ticketID": 5100,
                "ticketIcon": "ItemIcon_0032",
                "amount": 111,
                "maxReceive": 5,
                "receiveRemainTime": 0
            },
            {
                "lobby": 2,
                "ticketID": 5201,
                "ticketIcon": "ItemIcon_0031",
                "amount": 0,
                "maxReceive": 3,
                "receiveRemainTime": 0
            }
        ]
    }