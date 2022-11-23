# 通知 - 遊戲公告

## 介紹
 - 獲取當前時間內所有需要顯示之公告資訊。
 - 須完成登入驗證。

## URL

http(s)://`域名`/Notices/Announcement/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| lang | int | [多國語言編號](../codes/other.md#lang) |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [announcement](#announcement) | array | 遊戲公告內容物件集合。無公告則回傳null |

#### <span id="announcement">announcement 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

<br>

按照新舊順序排序。 新 => 舊。

無公告則回傳null
<br>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| graphURL | string | 公告圖片在GCP的網址 |
| type | int | [公告類別](../codes/other.md#announcementtype-遊戲公告類型) |
| title | string | 標題 |
| announceTime | int | 公告時間。格式為Unix時間戳 |
| content | string | 公告內文 |


### Example
    {
        "error": {
            "code": 0,
            "message": ""
        },
        "announcement": [
            {
                "graphURL": "...",
                "type": 2,
                "title": "我是標題",
                "announceTime": 1666597667,
                "content": "我是公告我是公告我是公告"
            }
        ]
    }

