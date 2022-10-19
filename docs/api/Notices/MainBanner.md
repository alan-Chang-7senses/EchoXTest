# 公告 - 主畫面 Banner

## 介紹

- 依據所指定的語言代號，取得 Banner 圖片網址、頁面網址或代號。

## URL

http(s)://`域名`/Notices/MainBanner/

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
| banner | array | 由多筆 Banner 資訊所組成的物件陣列 |

#### <span id="banner"> banner 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| type | int | [banner 目標類型](../codes/other.md#bannerTarget) |
| image | int | 圖片網址 |
| page | int | 頁面網址或 [UI 畫面代號](../codes/other.md#uiID) |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "banner": [
	        {
	            "type": 1,
	            "image": "https://petarush-cdn.metasens.com/petarush/Images/MainBanner/banner_01_en.png",
	            "page": "https://www.metasens.com/activity/peta"
	        },
	        {
	            "type": 1,
	            "image": "https://petarush-cdn.metasens.com/petarush/Images/MainBanner/banner_02.png",
	            "page": "https://www.metasens.com/en_us/activity/demipeta"
	        },
	        {
	            "type": 1,
	            "image": "https://petarush-cdn.metasens.com/petarush/Images/MainBanner/banner_03.png",
	            "page": "https://www.metasens.com/en_us/activity/phantabear3d"
	        },
	        {
	            "type": 2,
	            "image": "https://petarush-cdn.metasens.com/petarush/Images/MainBanner/banner_04_en.png",
	            "page": "ReceiveTicket"
	        },
	        {
	            "type": 1,
	            "image": "https://petarush-cdn.metasens.com/petarush/Images/MainBanner/banner_05_en.png",
	            "page": "https://chr2.io/petarush/ranking/?lang=en"
	        },
	        {
	            "type": 1,
	            "image": "https://petarush-cdn.metasens.com/petarush/Images/MainBanner/banner_06_en.png",
	            "page": "https://chr2.io/petarush/announcement/?lang=en&id=5"
	        },
	        {
	            "type": 1,
	            "image": "https://petarush-cdn.metasens.com/petarush/Images/MainBanner/banner_07_en.png",
	            "page": "https://discord.com/invite/petarush"
	        }
	    ]
	}