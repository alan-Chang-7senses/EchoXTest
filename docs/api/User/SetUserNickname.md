# 使用者 - 設定使用者暱稱

## 介紹

- 使用在初次設定或重設使用者暱稱。
- 需要完成登入驗證才可正常使用此 API。


## URL

http(s)://`域名`/User/SetUserNickname/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| nickname | string | 欲設之暱稱。16字以內的英數組合 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
