# API Response

## 介紹

- API 回應的基本規格。

## 回應格式

Content Type: `application/json`

### success 內容

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| [error](#error) | object | 狀態碼及訊息 |
| _`- other -`_| - | _`其它回傳資料，詳見個別的 API 說明`_ |

### <span id="error">error</span> 內容
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| code | int | 狀態碼，0 表示成功，其餘詳見 [Error Code](codes/errorCode.md) |
| message | string | 狀態訊息，若成功則為空字串 |

#### Example

	{
	    "error": {
		    "code": 1004,
		    "message": "Password is wrong"
	    }
    }
