# 免費Peta三選一 查詢使用者目前進度

## 介紹

- 取得使用者初次登入選擇免費Peta的進度。在使用者未完成免費Peta三選一流程時，可以知道使用者目前進度。
  進而在連線時知道要進入哪一個環節。

## URL

http(s)://`域名`/User/FreePeta/FreePetaProccess

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
| proccess | int | 使用者目前進度。0：還未設定使用者暱稱、1：已設定好暱稱、2：已選好免費Peta，流程結束。 |
