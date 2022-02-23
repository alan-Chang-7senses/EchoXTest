# 使用者 - 登入

## 介紹

- 使用於玩家關閉遊戲時，進行登出動作。
- 將刪除該玩家的登入身份。

## URL

http(s)://`域名`/User/Login/

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