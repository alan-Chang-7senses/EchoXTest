# 公告 - 提示文字

## 介紹

- 依據所指定的提示編號和語言，取得提示文字資訊。

## URL

http(s)://`域名`/Notices/HintText/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| hintID | int | 提示編號 |
| lang | int | [語言](../codes/other.md#lang) |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| title | string | 標題 |
| content | string | 內容  |

### Example

	{
		"error": {
			"code": 0,
			"message": ""
		},
		"title": "小提示",
		"content": "peta金幣賽入場卷，凌晨0點、中午12點會給一張，當身上數量超過100張便不再發放。"
	}