# 商店 - QuickSDK儲值回調通知

## 介紹

- 遊戲中儲值商店購買晶鑽。
- 會由前端設定此API給 QuickSDK 的callbackURL；
- 購買後QuickSDK會回調此API，回應SUCCESS為成功為完成購買。

## URL

http(s)://`域名`/Interfaces/QuickSDK/PurchaseNotify/
## Method

`POST`

## Request
Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| uid| 購買道具的用戶uid| 
| username| 購買道具的用戶username| 
| cpOrderNo| 遊戲下單時傳遞的遊戲訂單號，原樣返回| 
| orderNo| SDK唯一訂單號| 
| payTime| 用戶支付時間，如2017-02-06 14:22:32| 
| payType| 支付方式，具體值對應支付管道詳見後臺平臺管理>支付對照表| 
| payAmount| 用戶支付金額| 
| payCurrency| 用戶支援的幣種，如RMB，USD等| 
| usdAmount| 用戶支付的遊戲道具以美元計價的金額| 
| payStatus| 支付狀態，為0表示成功，為1時遊戲不做處理| 
| actRate| 充值折扣，取值範圍0~1(不包含0），默認為1表示不折扣；如值為0.2表示多發20%的元寶| 
| extrasParams| 遊戲下單時傳遞的擴展參數，將原樣返回。
| sign| 簽名值，遊戲應根據簽名約定，本地計算後與此值進行比對| 
<br>

## Response

Content Type: `string`

### 回應格式

	希望SDK繼續通知則返回任何非SUCCESS的字元。
	處理完畢，訂單結束則返回SUCCESS，SDK不會再通知。


### Example

