# 主介面 - 主要資料

## 介紹

- 使用於取得主介面的主要資料。
- 需要完成登入驗證才可正常使用此 API。

## URL

http(s)://`域名`/MainMenu/MainData/

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
| name | string | 使用者暱稱 |
| petaToken | int | Peta 虛擬幣 |
| coin | int | 遊戲金幣 |
| power | int | 電力 |
| diamond | int | 遊戲鑽石 |
| [player](#player) | object | 角色資訊 |

#### <span id="player">player 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號，16 碼 |
| head | string | 頭部代碼 |
| body | string | 身體代碼 |
| hand | string | 手部代碼 |
| leg | string | 腿部代碼 |
| back | string | 背部代碼 |
| hat | string | 頭冠代碼 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "name": "test004",
	    "petaToken": 0,
	    "coin": 0,
	    "power": 0,
	    "diamond": 0,
	    "player": {
	        "id": 1010000000000007,
	        "head": "120001",
	        "body": "120001",
	        "hand": "110002",
	        "leg": "110002",
	        "back": "110001",
	        "hat": "110002"
	    }
	}