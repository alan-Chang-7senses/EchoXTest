# 角色 - 使用經驗道具

## 介紹

- 使用經驗道具升級角色。
- 用於競賽以外畫面。

## URL

http(s)://`域名`/Player/UpgradeItem/

## Method

`POST`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| playerID | int | 角色編號，16 碼 |
| [mode](#mode) | int | 強化模式 |
| [item](#item) | json | 欲使用經驗道具合集 |

#### <span id="mode">mode 強化模式內容</span>

| 編號 | 說明 |
|:-:|:-:|
| 0 | 省錢培養 | 
| 1 | 普通培養 | 
| 2 | 豪爽培養 | 
#### <span id="item">item 欲使用經驗道具合集</span>

| 鍵值欄位 | 值欄位 |
|:-:|:-:|
| 道具ID(int) | 使用數量(int) | 
### Example
    {
        "1001": 2,
        "1002":1,
        "1003":1
    }

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| [bonus](#bonus) | array | 強化大成功模式 |
| level | int | 強化後等級 |
| hasUpgrade | bool | 是否等級提升 |

#### <span id="bonus">bonus 強化成功結果</span>

| 編號 | 說明 |
|:-:|:-:|
| 1 | 大成功 | 
| 2 | 超級成功 | 
### Example

    "bonus": [
        1
    ],


### Example
    {
    "error": {
        "code": 0,
        "message": ""
    },
    "bonus": [
        1
    ],
    "level": 40,
    "hasUpgrade": false
    }