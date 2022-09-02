# 角色 - 提升階級

## 介紹

- 用於提升角色階級。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/RankUp/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 角色編號，16 碼 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |



### Example 回傳結果
    {
        "error": {
            "code": 0,
            "message": ""
        }
    }   