# 競賽 - 領取招待卷

## 介紹

- 每個賽季都可以領免費招待卷，讓玩家可以進行競賽，此API可以取得競技賽免費召待卷。

## URL

http(s)://`域名`/PVP/ReceiveTicket/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳](../codes/race.md#lobby) |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| ticket | object | [入場卷資訊](LobbyInfo.md#ticket) |
<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "ticket": {
            "ticketID": 1,
            "amount": 2,
            "maxReceive": 5,
            "receiveRemainTime": 41692            
        }
    }