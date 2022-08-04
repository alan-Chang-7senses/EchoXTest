# 競賽 - PVP配對

## 介紹

- 當玩家進入遊戲大廳時，取得大廳相關資訊

## URL

http(s)://`域名`/PVP/LobbyInfo/

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
| petaToken | int | Peta虛擬幣 |
| coin | int | 遊戲金幣 |
| diamond | int | 鑽石 |
| [ticket](#ticket) | object | 入場卷資訊 |
<br>


#### <span id="ticket">ticket 入場卷資訊 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳種類](../codes/race.md#lobby) |
| ticketID | int | 入場卷物品編號 |
| amount | int | 入場卷物品數量 |
| maxReceive | int | 入場卷領取上限 |
| receiveRemainTime | int | 剩餘可領領時間(秒) |
<br>


### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "petaToken": 0,
        "coin": 0,
        "diamond": 0,
        "ticket": [
            {
                "lobby": 1,
                "ticketID": 1,
                "amount": 889,
                "maxReceive": 5,
                "receiveRemainTime": 0
            },
            {
                "lobby": 2,
                "ticketID": 2,
                "amount": 1623,
                "maxReceive": 3,
                "receiveRemainTime": 0
            }
        ]
    }