# 使用者 - 更新電力

## 介紹

 - 用於不定時或定時更新電力。
 - 回傳更新後電力與距離滿電力剩餘秒數。

## URL

http(s)://`域名`/User/UpdatePower/

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
| power | int | 更新後電力 |
| timeTillFull | int | 距離滿電力剩餘秒數 |
| rate | int | 用戶每一點體力回復需求秒數 |


### Example

#### 成功
    {
        "error": {
            "code": 0,
            "message": ""
        }
        "power": 20,
        "timeTillFull": 27570
    }