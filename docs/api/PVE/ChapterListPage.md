# PVE - 章節列表頁面

## 介紹

- 進入PVE模式時，取得顯示所有章節頁面所需資訊。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/PVE/ChapterListPage/

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
| power | int | 使用者當前電力 |
| [chapters](#chapters) | array | 章節資訊集合 |


<br>

#### <span id="chapters">chapters 章節資訊集合內容 </span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 章節編號 |
| icon | string | 章節圖示代號 |
| name | string | 章節名稱代號 |
| available | bool | 是否解鎖 |
<br>

### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "power": 300,
        "chapters": [
            {
                "id": "1",
                "icon": "222",
                "name": "111",
                "available": true
            },
            {
                "id": "2",
                "icon": "222",
                "name": "222",
                "available": true
            },
            {
                "id": "3",
                "icon": "333",
                "name": "333",
                "available": false
            }
        ]
    }