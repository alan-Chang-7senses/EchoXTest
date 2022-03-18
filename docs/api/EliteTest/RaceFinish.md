# 菁英測試 - 競賽完成

## 介紹

- 在菁英測試帳號登入後，於競賽完成時，紀錄玩家競賽相關資料。
- 處理完成後，將解除參賽玩家的競賽狀態。

## URL

http(s)://`域名`/EliteTest/RaceFinish/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| raceID | int | 競賽編號  |
| [users](#users) | string | 參賽玩家競賽資料所組成的 JSON 陣列字串|

#### <span id="users">users 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 使用者編號 |
| score | int | 得分 |
| trackOrder | int | 賽道順序 |
| ranking | int | 排名 |
| duration | float | 完賽時間 |
| finishS | float | 結束 S 值 |
| finishH | float | 結束 H 值 |
| [skills](#skills) | array | 使用技能的 JSON 陣列 |

#### <span id="skills"> skills 內容</span>

_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | string | 技能識別字 |
| position | string | 發動技能座標 |
| trackType | int | [賽道類別](../codes/race.md#trackType)  |
| trackShape | int | [賽道形狀](../codes/race.md#trackShape) |
| beforeS | float | 發動前 S 值 |
| beforeH | float | 發動前 H 值 |
| beforeEnergy | string | 發動前能量，以逗點隔開的字串，依序為 紅,黃,藍,綠 |
| afterS | float | 發動後 S 值 |
| afterH | float | 發動後 H 值 |
| afterEnergy | string | 發動後能量，以逗點隔開的字串，依序為 紅,黃,藍,綠 |

#### users 範例

	[
		{
			"id": 1,
			"score": 30,
			"trackOrder": 1,
			"ranking": 1,
			"duration": 30.12,
			"finishS": 2.55,
			"finishH": 0.98,
			"skills":[
				{
					"id": "Test001",
					"position": "(12.33, 0.75, 1.99)",
					"trackType": 1,
					"trackShape": 1,
					"beforeS": 2.55,
					"beforeH": 0.98,
					"beforeEnergy": "3,1,2,5",
					"afterS": 2.95,
					"afterH": 0.92,
					"afterEnergy": "0,1,2,5"
				},
				{
					"id": "Test002",
					"position": "(10.33, 0.05, 3.99)",
					"trackType": 1,
					"trackShape": 1,
					"beforeS": 2.55,
					"beforeH": 0.98,
					"beforeEnergy": "3,1,2,5",
					"afterS": 2.95,
					"afterH": 0.92,
					"afterEnergy": "0,1,2,5"
				}
			]
		},
		{
			"id": 2,
			"score": 20,
			"trackOrder": 2,
			"ranking": 2,
			"duration": 30.32,
			"finishS": 2.55,
			"finishH": 0.98,
			"skills":[]
		},
		{
			"id": 3,
			"score": 20,
			"trackOrder": 2,
			"ranking": 2,
			"duration": 30.32,
			"finishS": 2.55,
			"finishH": 0.98,
			"skills":[
				{
					"id": "Test002",
					"position": "(10.33, 0.05, 3.99)",
					"trackType": 1,
					"trackShape": 1,
					"beforeS": 2.55,
					"beforeH": 0.98,
					"beforeEnergy": "3,1,2,5",
					"afterS": 2.95,
					"afterH": 0.92,
					"afterEnergy": "0,1,2,5"
				}
			]
		}
	]

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}