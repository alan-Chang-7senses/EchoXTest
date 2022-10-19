# PVE - 開始

## 介紹

- 在PVE開賽(Ready)前，獲取賽道與機器人相關資訊。以及讓該使用者進入PVE狀態。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVE/PVEStart/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| levelID | int | 關卡編號 |
<br>


## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [botInfos](#botInfos) | array | 機器人資訊 |
<br>

#### <span id="botInfos">botInfos 機器人資訊內容 </span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 機器人的使用者編號 |
| name | string | 機器人的角色名稱代號 |
| [dna](#dna) | object | 機器人各部位DNA代碼前六碼 |
| trackNumber | int | 機器人所在跑到代碼 |
<br>

#### <span id="dna">dna dna內容 </span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| head | string | 頭部dna前六碼 |
| body | string | 身體dna前六碼 |
| hand | string | 手dna前六碼 |
| leg | string | 腳dna前六碼 |
| back | string | 背dna前六碼 |
| hat | string | 帽dna前六碼 |

<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "botInfos": [
            {
                "id": -1,
                "name": "aichar0001",
                "dna": {
                    "head": "140105",
                    "body": "140102",
                    "hand": "110113",
                    "leg": "140204",
                    "back": "110113",
                    "hat": "130301"
                },
                "trackNumber": 1
            },
            {
                "id": -2,
                "name": "aichar0002",
                "dna": {
                    "head": "130302",
                    "body": "140102",
                    "hand": "130303",
                    "leg": "110211",
                    "back": "110211",
                    "hat": "140105"
                },
                "trackNumber": 2
            },
            {
                "id": -3,
                "name": "aichar0003",
                "dna": {
                    "head": "130302",
                    "body": "140308",
                    "hand": "130303",
                    "leg": "110208",
                    "back": "110207",
                    "hat": "170302"
                },
                "trackNumber": 3
            }
        ]
    }