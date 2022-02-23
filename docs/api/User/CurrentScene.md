# 使用者 - 當前場景

## 介紹

- 使用場景編號設定使用者的當前場景，並回傳此場景相關資訊。

## URL

http(s)://`域名`/User/CurrentScene/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| scene | int | 場景編號 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [scene](#scene) | object | 場景資訊 |

#### <span id="scene">scene 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 場景編號 |
| name | string | 場景名稱 |
| readySec | int | 起跑準備時間（秒） |
| env | int | [環境](../codes/scene.md#env) |
| [climates](#climates) | array | 全部時間區段的氣候資料陣列 |

#### <span id="climates"> climates 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 氣候編號 |
| weather | int | [天氣](../codes/scene.md#weather) |
| windDirection | int | [風向](../codes/scene.md#windDirection) |
| windSpeed | int | 風速 |
| startTime | int | 當日氣候起始時間（秒） |
| lighting | int | [照明（明暗）](../codes/scene.md#lighting) |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "scene": {
	        "id": 1,
	        "name": "CloseBeta",
	        "readySec": 7,
	        "env": 1,
	        "climates": [
	            {
	                "id": 3,
	                "weather": 1,
	                "windDirection": 3,
	                "windSpeed": 100,
	                "startTime": 64800,
	                "lighting": 2
	            },
	            {
	                "id": 2,
	                "weather": 1,
	                "windDirection": 2,
	                "windSpeed": 100,
	                "startTime": 28800,
	                "lighting": 1
	            },
	            {
	                "id": 1,
	                "weather": 1,
	                "windDirection": 1,
	                "windSpeed": 100,
	                "startTime": 0,
	                "lighting": 2
	            }
	        ]
	    }
	}