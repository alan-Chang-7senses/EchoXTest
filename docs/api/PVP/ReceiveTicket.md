# 競賽 - 領取入場卷

## 介紹

- 領取晉級賽入場卷，讓玩家可以進行競賽。
- 領取有時間限制。
- 需要完成登入驗證才可正常使用此 API。

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
| lobby | int | [大廳種類](../codes/race.md#lobby) |
| amount | int | 已有物品數量 |
| receiveRemainTime | int | 剩餘可領時間(秒) |
<br>
### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "lobby": "1",
        "amount": 75,
        "receiveRemainTime": 42652
    }