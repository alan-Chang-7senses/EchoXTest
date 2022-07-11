# 角色 - 儲存技能

## 介紹

- 使用於儲存角色技能資料。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/SetSkill/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 角色編號，16 碼 |
| [skillsData](#skillsData) | json | 技能資料 |

#### <span id="skillsData">skillsData 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| skillID | int | 技能ID |
| slot | int | 技能欄位 |

### Example

	{
		"playerID":1010000000000001,
		"skillsData": [
			{
				"skillID": 1,
				"slot": 1
			},
			{           
			"skillID": 2,
				"slot": 2
			},
			{            
			"skillID": 3,
				"slot": 3
			}
		]
	}


## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |


### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}