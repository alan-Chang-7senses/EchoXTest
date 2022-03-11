# 角色 - Avatar 部位

## 介紹

- 使用角色編號取得各部位的 Avatar 編號。

## URL

http(s)://`域名`/Player/AvatarParts/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| id | int | 角色編號 |

## Response

Content Type: `application/json`

### 回應格式

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
	    "parts": {
	        "id": 1010000000000001,
	        "head": "110101",
	        "body": "110101",
	        "hand": "110101",
	        "leg": "110101",
	        "back": "110101",
	        "hat": "110101"
	    }
	}