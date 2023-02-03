# 情境題 - 轉移資產


## URL

http://localhost:37001/BlanceTransfer

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 請求參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| receiveUserId | int | 資產轉入之使用者 reference id |
| transferAmount | float | 轉出數量 |

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息 |

### Example

#### 成功

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}