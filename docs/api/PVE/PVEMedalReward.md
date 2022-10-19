# PVE - 領取章節獎牌獎勵

## 介紹

- 在PVE關卡列表介面中。用以領取各階段章節獎牌獎勵。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVE/PVEMedalReward/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| chapterID | int | 章節ID |
| [phase](#phase) | int | 欲領取獎勵之階段代號 |
<br>

#### <span id="phase">reward 獎勵階段代號 </span>
| 說明 | 代號 |
|:-:|:-:|
| 第一階段獎勵代號 | 0 |
| 第二階段獎勵代號 | 1 |
| 第三階段獎勵代號 | 2 |


## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [reward](#reward) | object | 領取之章節階段獎勵 |


<br>

#### <span id="reward">reward 領取之章節階段獎勵內容 </span>
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
        "reward": {
            "itemID": 1111,
            "icon": "ItemIcon_0004",
            "amount": 1
        }
    }