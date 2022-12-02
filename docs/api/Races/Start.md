# 競賽 - 起跑

## 介紹

- 當玩家開局進場準備就緒後，透過此功能紀錄起跑時間。

## URL

http(s)://`域名`/Races/Start/

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
| [playerMusicURLs](#playerMusicURLs) | array | 所有玩家的角色音樂網址 |

<br>
##### <span id="playerMusicURLs">playerMusicURLs 玩家的角色音樂網址內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 角色編號 |
| musicURL | string | 音樂網址(無則回傳空字串) |

### Example

	{
	  "error": {
	    "code": 0,
	    "message": ""
	  },
	  "playerMusicURLs": [
	    {
	      "playerID": 201,
	      "musicURL": ""
	    },
	    {
	      "playerID": 1010000000000670,
	      "musicURL": "https://static.melos.studio/audio/sonus/0922/peta/2085.mp3"
	    }
	  ]
	}