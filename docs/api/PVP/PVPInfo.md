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
| ticketIcon | string | 入場卷物品圖號 |
| ticketAmount | int | 入場卷已有物品數量 |
| petaLimitLevel | int | Peta限制等級<br>(0代表不限制) |
| [rank](#rank) | object | 排行榜資訊 |
| [scene](#scene) | object | 場景資訊 |
<br>

#### <span id="rank">rank 排行榜資訊</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playCount | int | 參賽次數 |
| leadRate | float | 領先率百分比之數值<br>例如 12.34 代表領先率為「12.34%」 |
| ranking | int | 排行榜名次 |
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
        "seasonRemainTime": 130165,
        "infos": [
            {
                "lobby": 1,
                "ticketIcon": "ItemIcon_0032",
                "ticketAmount": 998,
                "petaLimitLevel": 70,
                "rank": {
                    "playCount": 1,
                    "leadRate": 0,
                    "ranking": 0
                },
                "scene": {
                    "id": 1001,
                    "name": "9001",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 2,
                    "windSpeed": 50,
                    "lighting": 1
                }
            },
            {
                "lobby": 2,
                "ticketIcon": "ItemIcon_0031",
                "ticketAmount": 0,
                "petaLimitLevel": 0,
                "rank": {
                    "playCount": 0,
                    "leadRate": 0,
                    "ranking": 0
                },
                "scene": {
                    "id": 1001,
                    "name": "9001",
                    "env": 1,
                    "weather": 1,
                    "windDirection": 2,
                    "windSpeed": 50,
                    "lighting": 1
                }
            }
        ]
    }