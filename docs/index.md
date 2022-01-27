# King of Animals Games API

## 介紹

- 提供遊戲前端（Unity）存取資料以及處理遊戲邏輯的後端功能。

## APIs

- [登入驗證](Login/Verify.md)。
- 主介面
	- [主要資料](MainMenu/MainData.md)。
	- [角色選擇介面資料](MainMenu/CharacterSelectData.md)。
	- [角色資料](MainMenu/CharacterData.md)。

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [error](#error) | object | 狀態碼及訊息 |
| _`- other -`_| - | _`其它回傳資料，詳見個別的 API 說明`_ |

#### <span id="error">error</span> 內容
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| code | int | 狀態碼，0 表示成功，其餘詳見 [Error Code](#errorCode) |
| message | string | 狀態訊息，若成功則為空字串 |

### Example

	{
	    "error": {
		    "code": 1004,
		    "message": "Password is wrong"
	    }
    }

## Http Code

| 狀態碼 | 說明 |
|:-:|:-:|
| 200 | OK |
| 400 | 資料處理失敗或發生錯誤 |
| 404 | 無此功能，未知的請求 |
| 500 | 發生未知或無法處理的錯誤 |
| 501 | 不支援的請求方法 |

## <span id="errorCode">Error Code</span>

| 錯誤碼 | 說明 |
|:-:|:-:|
| 0 | 處理成功 |
| 1 | API 設置錯誤 |
| 3 | 系統錯誤 |
| 4 | 資料庫請求失敗 |
| 26 | 參數格式錯誤 或 缺少參數 |
| 27 | 資料驗證失敗 |
| 28 | 處理失敗 |
| 999 | 未知錯誤 |
|:-:|:-:|
| 1000 | 使用者權限已登出 |
| 1001 | 帳號或密碼格式錯誤 |
| 1002 | 無此帳號 |
| 1003 | 密碼錯誤 |
| 1004 | 帳號被禁用 |
|:-:|:-:|
| 23000 | 寫入資料重複 |