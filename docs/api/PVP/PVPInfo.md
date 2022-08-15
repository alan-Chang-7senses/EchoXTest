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
| seasonRemainTime | int | 剩餘賽季時間(秒) |
| [infos](#infos) | object | 大廳資訊 |
<br>


#### <span id="infos">infos 內容</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lobby | int | [大廳種類](../codes/race.md#lobby) |
| petaLimitLevel | int | Peta限制等級<br>(0代表不限制) |
| [rank](#rank) | object | 排行榜資訊 |
| [scene](#scene) | object | 場景資訊 |
<br>

#### <span id="rank">rank 排行榜資訊</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| raceAmount | int | 上場次數 |
| aveRank| string | 平均排名 |
| rank | int | 排行嗙名次 |
|

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
        "seasonRemainTime": 770882,
        "infos": [
            {
                "lobby": 1,
                "petaLimitLevel": 70,
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