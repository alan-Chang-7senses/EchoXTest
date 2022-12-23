# 競賽 - 取得晉級賽各賽場資訊

## 介紹

- 取得晉級賽相關資訊。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVP/PVPInfo/

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
| [infos](#infos) | object | 大廳資訊 |
<br>


#### <span id="infos">infos 內容</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳種類](../codes/race.md#lobby) |
| ticketIcon | string | 入場卷物品圖號 |
| ticketAmount | int | 入場卷已有物品數量 |
| petaLimitLevel | int | Peta指定等級<br>(0代表不指定) |
| [rank](#rank) | object | 排行榜資訊 |
| [simplePlayer](#simplePlayer) | object | 角色受等級影響過後的簡易數值 |
| [scene](#scene) | object | 場景資訊 |
| seasonRemainTime | int | 剩餘賽季時間(秒) |
<br>

#### <span id="rank">rank 排行榜資訊</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playCount | int | 參賽次數 |
| rate | int | 積分 |
| ranking | int | 排行榜名次 |
<br>

#### <span id="simplePlayer">simplePlayer 角色資訊</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| velocity | float | 速度 |
| stamina | float | 耐力 |
| intelligent | float | 聰慧 |
| breakOut | float | 爆發 |
| will | float | 鬥志 |
| skillLevel | int | 指定技能等級(0代表不指定) |


#### <span id="scene">scene 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 場景編號 |
| name | string | 場景名稱 |
| env | int | [環境](../codes/scene.md#env) |
| weather | int | [天氣](../codes/scene.md#weather) |
| windDirection | int | [風向](../codes/scene.md#windDirection) |
| windSpeed | int | 風速 |
| lighting | int | [照明（明暗）](../codes/scene.md#lighting) |
|


### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "infos": [
            {
                "lobby": 1,
                "ticketIcon": "ItemIcon_0032",
                "ticketAmount": 2241,
                "petaLimitLevel": 1,
                "rank": {
                    "playCount": 3,
                    "rate": 1509,
                    "ranking": 1
                },
                "simplePlayer": {
                    "velocity": 112.49,
                    "stamina": 112.49,
                    "intelligent": 112.49,
                    "breakOut": 112.49,
                    "will": 112.49,
                    "skillLevel": 1
                },
                "scene": {
                    "id": 1002,
                    "name": "9003",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 2,
                    "windSpeed": 25,
                    "lighting": 1
                },
                "seasonRemainTime": 476410010
            },
            {
                "lobby": 4,
                "ticketIcon": "ItemIcon_0032",
                "ticketAmount": 2241,
                "petaLimitLevel": 1,
                "rank": {
                    "playCount": 0,
                    "rate": 0,
                    "ranking": 0
                },
                "simplePlayer": {
                    "velocity": 112.49,
                    "stamina": 112.49,
                    "intelligent": 112.49,
                    "breakOut": 112.49,
                    "will": 112.49,
                    "skillLevel": 1
                },
                "scene": {
                    "id": 1001,
                    "name": "9001",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 3,
                    "windSpeed": 25,
                    "lighting": 1
                },
                "seasonRemainTime": 476410010
            },
            {
                "lobby": 2,
                "ticketIcon": "ItemIcon_0041",
                "ticketAmount": 2239,
                "petaLimitLevel": 100,
                "rank": {
                    "playCount": 5,
                    "rate": 1515,
                    "ranking": 1
                },
                "simplePlayer": {
                    "velocity": 143.01,
                    "stamina": 144.61,
                    "intelligent": 144.61,
                    "breakOut": 144.61,
                    "will": 144.61,
                    "skillLevel": 5
                },
                "scene": {
                    "id": 1002,
                    "name": "9003",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 2,
                    "windSpeed": 25,
                    "lighting": 1
                },
                "seasonRemainTime": 476410010
            },
            {
                "lobby": 5,
                "ticketIcon": "ItemIcon_0041",
                "ticketAmount": 2239,
                "petaLimitLevel": 100,
                "rank": {
                    "playCount": 0,
                    "rate": 0,
                    "ranking": 0
                },
                "simplePlayer": {
                    "velocity": 143.01,
                    "stamina": 144.61,
                    "intelligent": 144.61,
                    "breakOut": 144.61,
                    "will": 144.61,
                    "skillLevel": 5
                },
                "scene": {
                    "id": 1001,
                    "name": "9001",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 3,
                    "windSpeed": 25,
                    "lighting": 1
                },
                "seasonRemainTime": 476410010
            }
        ]
    }