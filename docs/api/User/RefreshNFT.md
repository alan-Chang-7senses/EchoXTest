# 使用者 - 更新NFT資料

## 介紹
 - 用以刷新使用者當前所持有之NFT角色。
 - 必須完成登入驗證。

## URL

http(s)://`域名`/User/RefreshNFT/

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