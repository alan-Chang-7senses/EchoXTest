# 公告 - 跑馬燈

## 介紹

- 依據所指定的語言代號，取得跑馬燈文字資訊。
- 可取得多筆跑馬燈文字資訊。

## URL

http(s)://`域名`/Notices/Marquee/

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
| error | object | 錯誤碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| lang | int | [語言](../codes/other.md#lang) |
| contents | array | 由多筆訊息字串所組成的陣列 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "lang": "1",
	    "contents": [
	        "Marquee English 1",
	        "Marquee English 2"
	    ]
	}