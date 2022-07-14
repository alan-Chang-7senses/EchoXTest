# 競賽 - 斷線恢復(存資料)

## 介紹

- 當玩家於競賽中斷線，恢復連線進入競賽可透過此功能獲取競賽場中的場景與角色相關資料。

## URL

http(s)://`域名`/Races/OfflineRecoveryData/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| raceID | int | 賽局編號 |
| countDown | float | 倒數計時 |
| runTime | float | 比賽時間 |
| [playersData](#playersData) | json | 各玩家的角色競賽資料陣列 |

#### <span id="playersData">playersData 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 玩家編號 |
| moveDistance | float | 移動距離 |
| [skillData](#skillData) | json | 技能資料 |

#### <span id="skillData">skillData 內容</span>

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| SkillCoolTime | float | 技能冷卻時間 |
| normalSkillTime | float | 一般技能持續時間 |
| fullLVSkillTime | float | 滿星技能持續時間 |



### Example

	{
		"raceID": 1,
		"countDown": 0,
		"runTime": 10,
	    "playersData":	[{
			"playerID": 1010000000000001,
			"moveDistance": 250,
			"skillData":[{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":0,
				"skillCoolTime": 0,
				"normalSkillTime": 0,
				"fullLVSkillTime": 0}]
			},{"playerID": 1010000000000002,
			"moveDistance": 250,
			"skillData":[{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":101101,
				"skillCoolTime": 5,
				"normalSkillTime": 5,
				"fullLVSkillTime": 10},{
				"skillID":0,
				"skillCoolTime": 0,
				"normalSkillTime": 0,
				"fullLVSkillTime": 0},{
				"skillID":0,
				"skillCoolTime": 0,
				"normalSkillTime": 0,
				"fullLVSkillTime": 0}]}]
	    ,
		"createTime":1655953824
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