# 主介面 - 角色選擇介面資料

## 介紹

- 使用於取得主介面的角色選擇介面資料。
- 需要完成登入驗證才可正常使用此 API。
- 依據所提供參數取得指定範圍內的多個角色資料。
- 提供使用者的角色持有總數。

## URL

http(s)://`域名`/MainMenu/CharacterSelectData/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| offset | int | 偏移量，即從 0 開始的取得資料起始數 |
| count | int | 數量，即此次取得資料的最大數量 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| total | int | 持有角色總數 |
| [players](#players) | array | 角色資料的物件陣列 |

#### <span id="players">players 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號，16 碼 |
| head | string | 頭部代碼（或空字串略過此部位） |
| body | string | 身體代碼（或空字串略過此部位） |
| hand | string | 手部代碼（或空字串略過此部位） |
| leg | string | 腿部代碼（或空字串略過此部位） |
| back | string | 背部代碼（或空字串略過此部位） |
| hat | string | 頭冠代碼（或空字串略過此部位） |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    },
	    "total": 2,
	    "players": [
	        {
	            "id": 1010000000000005,
	            "head": "110205",
	            "body": "110101",
	            "hand": "110101",
	            "leg": "110101",
	            "back": "110103",
	            "hat": "110101"
	        },
	        {
	            "id": 1010000000000015,
	            "head": "110104",
	            "body": "110103",
	            "hand": "310201",
	            "leg": "110101",
	            "back": "110101",
	            "hat": "110101"
	        }
	    ]
	}