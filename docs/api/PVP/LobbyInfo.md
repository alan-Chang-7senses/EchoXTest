# 競賽 - PVP配對

## 介紹

- 當玩家進入遊戲大廳時，取得大廳相關資訊

## URL

http(s)://`域名`/PVP/LobbyInfo/

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
| seasonRemainTime | int | 剩餘賽季時間(秒) |
| [infos](#infos) | object | 大廳資訊 |
<br>


#### <span id="infos">infos 內容</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳種類](../codes/race.md#lobby) |
| petaLimitLevel | int | Peta限制等級<br>(0代表不限制) |
| [ticket](#ticket) | object | 入場卷資訊 |
| [rank](#rank) | object | 排行榜資訊 |
| [scene](#scene) | object | [場景資訊](../User/CurrentScene.md#scene) |
<br>

#### <span id="ticket">ticket 入場卷資訊 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| ticketID | int | 入場卷物品編號 |
| amount | int | 入場卷物品數量 |
| maxReceive | int | 入場卷領取上限 |
| receiveRemainTime | int | 剩餘可領領時間(秒) |
<br>

#### <span id="rank">rank 排行榜資訊</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| raceAmount | int | 上場次數 |
| aveRank| string | 平均排名 |
| rank | int | 排行嗙名次 |
|




### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "seasonRemainTime": 770882,
        "infos": [
            {
                "lobby": 1,
                "petaLimitLevel": 70,
                "ticket": {
                    "ticketID": 1,
                    "amount": 100,
                    "maxReceive": 5,
                    "receiveRemainTime": 0
                },
                "rank": {
                    "raceAmount": 12,
                    "aveRank": "1.11",
                    "rank": "5"
                },
                "scene": {
                    "id": 1,
                    "name": "CloseBeta",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 2,
                    "windSpeed": 50,
                    "lighting": 1
                }
            },
            {
                "lobby": 2,
                "petaLimitLevel": 0,
                "ticket": {
                    "ticketID": 2,
                    "amount": 0,
                    "maxReceive": 3,
                    "receiveRemainTime": 0
                },
                "rank": {
                    "raceAmount": 34,
                    "aveRank": "3.21",
                    "rank": "15"
                },
                "scene": {
                    "id": 2,
                    "name": "CloseBeta",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 1,
                    "windSpeed": 1,
                    "lighting": 0
                }
            }
        ]
    }