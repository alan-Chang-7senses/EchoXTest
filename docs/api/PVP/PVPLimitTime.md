# 競賽 - PT賽每日限時開放資訊

## 介紹

- 依據所指定的語言代號，查詢
- (1) PT 賽每日限時開放資訊 (距離開始/結束時間點的剩餘秒數)。
- (2) 取得跑馬燈字串列表 (開始/結束時)。

## URL

http(s)://`域名`/PVP/PVPLimitTime/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lang | int | [語言](../codes/other.md#lang) |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| startRemainSeconds | int | 距離最近比賽開始時間點的剩餘秒數(秒) |
| endRemainSeconds | int | 距離最近比賽結束時間點的剩餘秒數(秒) |
| startMarquee | object | (開始時間) 顯示的跑馬燈字串列表 |
| endMarquee | object | (結束時間) 顯示的跑馬燈字串列表 |
<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "startRemainSeconds": 12233,
        "endRemainSeconds": 26633,
        "startMarquee": [
            "(12) 立即前往 PT 賽場參賽！以實力爭取優渥獎勵！"
        ],
        "endMarquee": [
            "(12) 當前 PT 賽場參賽時段已結束，敬請期待下次開放！"
        ]
    }

### 重點

(1) startRemainSeconds > endRemainSeconds : 競賽進行中
(2) startRemainSeconds < endRemainSeconds : 競賽尚未開放

### 備註

假設每天有 兩個時段開放 PT 賽，
(1) 07:00~11:00
(2) 19:00~23:00

<hr>
<b><範例一></b> 現在時間 早上 06:30:00 開遊戲取得資訊如下 :
<br><br>
"startRemainSeconds": 1800,<br>
// 1800 : 現在時間(06:30:00) 距離最近競賽的開始時間(07:00:00) 間隔 30分鐘 = 1800秒<br><br>
"endRemainSeconds": 16200,<br>
// 16200 (秒) : 現在時間(06:30:00)距離最近競賽的結束時間(11:00:00) 間隔 4小時30分鐘 = 16200秒<br><br>
<b>結論 : start(1800) < end(16200) : 競賽尚未開放</b>
<hr>
<b><範例二></b> 現在時間 早上 07:00:00 開遊戲取得資訊如下 :
<br><br>
"startRemainSeconds": 43200,<br>
// 43200 (秒) : 現在時間(07:00:00) 距離最近競賽的開始時間(19:00:00) 間隔 12小時 = 43200秒<br><br>
"endRemainSeconds": 14400,<br>
// 14400 (秒) : 現在時間(07:00:00)距離最近競賽的結束時間(11:00:00) 間隔 4小時 = 14400秒<br><br>
<b>結論 : start(43200) > end(14400) : 競賽進行中</b>
<hr>
<b><範例三></b> 現在時間 早上 10:59:59 開遊戲取得資訊如下 :
<br><br>
"startRemainSeconds": 28801,<br>
// 28801 (秒) : 現在時間(10:59:59) 距離最近競賽的開始時間(19:00:00) 間隔 8小時 = 28801秒<br><br>
"endRemainSeconds": 1,<br>
// 1 (秒) : 現在時間(10:59:59)距離最近競賽的結束時間(11:00:00) 間隔 1秒<br><br>
<b>結論 : start(28801) > end(1) : 競賽進行中</b>
<hr>
<b><範例四></b> 現在時間 早上 11:00:00 開遊戲取得資訊如下 :
<br><br>
"startRemainSeconds": 28800,<br>
// 28800 (秒) : 現在時間(11:00:00) 距離最近競賽的開始時間(19:00:00) 間隔 8小時 = 28800秒<br><br>
"endRemainSeconds": 43200,<br>
// 43200 (秒) : 現在時間(11:00:00)距離最近競賽的結束時間(23:00:00) 間隔 12小時 = 43200秒<br><br>
<b>結論 : start(28800) < end(43200) : 競賽尚未開放</b>
<hr>
<b><範例五></b> 現在時間 凌晨 00:00:00 開遊戲取得資訊如下 :
<br><br>
"startRemainSeconds": 25200,<br>
// 25200 (秒) : 現在時間(00:00:00) 距離最近競賽的開始時間(隔天 07:00:00) 間隔 7小時 = 25200秒<br><br>
"endRemainSeconds": 39600,<br>
// 39600 (秒) : 現在時間(00:00:00)距離最近競賽的結束時間(隔天 11:00:00) 間隔 11小時 = 39600秒<br><br>
<b>結論 : start(25200) < end(39600) : 競賽尚未開放</b>
<hr>