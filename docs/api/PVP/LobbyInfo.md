# 競賽 - 大廳資訊

## 介紹

- 當玩家進入遊戲大廳時，取得大廳相關資訊(還有些資訊需要補上)。
- 需要完成登入驗證才可正常使用此 API。

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
<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "petaToken": 0,
        "coin": 0,
        "diamond": 0
    }