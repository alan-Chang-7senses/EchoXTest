# 公告 - 相容版本

## 介紹

- 依據當前串接的後端 API 版本，回傳相容前端版本號。
- 後端版本為後端 API 容器內部環境變數紀錄的版本號。
- 後端版本可相容多個前端版本。
- 每個前端版本搭配一個 Avatar 版本。
- 不需登入即可使用。

## URL

http(s)://`域名`/Notices/Versions/

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
| error | object | 錯誤碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| versions | array | 版本資訊物件陣列 |

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| frontend | string | 前端版本號 |
| avatar | string | Avatar 資源版本號 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "versions": [
	        {
	            "frontend": "0.1.19",
	            "avatar": "0.9.3"
	        },
	        {
	            "frontend": "0.1.18",
	            "avatar": "0.9.3"
	        }
	    ]
	}