# PVE - 一鍵通關

## 介紹

- 用於使用一鍵通關。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVE/PVERush/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| levelID | int | 關卡編號 |
| count | int | 一鍵通關次數 |
<br>


## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [rewards](#rewards) | object | 領取之章節階段獎勵 |


<br>

#### <span id="rewards">rewards 領取之章節階段獎勵內容 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| itemID | int | 物品編號 |
| icon | string | 物品圖示代號 |
| amount | int | 物品數量 |
<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "rewards": [
            {
                "itemID": 1132,
                "icon": "ItemIcon_0009",
                "amount": 2
            },
            {
                "itemID": 1112,
                "icon": "ItemIcon_0005",
                "amount": 6
            },
            {
                "itemID": 1122,
                "icon": "ItemIcon_0007",
                "amount": 2
            }
        ]
    }