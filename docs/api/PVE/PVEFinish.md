# PVE - 結束

## 介紹

- 在PVE結束(FinishRace)後，獲取獎勵相關資訊。
- 讓使用者離開PVE狀態。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVE/PVEFinish/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數
無
<br>


## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| isCleared | bool | 是否通關 |
| [items](#items) | array | 通關獲得道具(未通關為null) |
| medalAmount | int | 獲得獎牌數量 |
<br>

#### <span id="items">items 通關獲得獎勵內容 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| icon | string | 物品圖示代號 |
| amount | int | 物品數量 |
<br>


<br>

### Example
    {
      "error": {
        "code": 0,
        "message": ""
      },
      "isCleared": true,
      "items": [
        {
          "itemID": 1121,
          "icon": "ItemIcon_0006",
          "amount": 1
        },
        {
          "itemID": 1122,
          "icon": "ItemIcon_0007",
          "amount": 1
        }
      ],
      "medalAmount": 2
    }