# 商店 - 資訊

## 介紹

- 遊戲中各種商城的資訊，堤供貨幣交易、資源兌換的需求。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/GetInfos/

## Method

`GET`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

無

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| currencies | array | 按[貨幣](#Currency)順序 |
| stores | array | [商店資訊](#storeData) |


#### <span id="storeData">商店資訊</span>
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| storeID | int | 商店索引 |
| UIStyle | int | [介面類型](#UIStyle) |
| refreshRemain | int | 剩餘刷新次數 |
| refreshTime | int | 剩餘刷新時間(s) |
| refreshCost | int | 刷新費用 |
| refreshCurrency | int | 刷新費用之[貨幣](#Currency) |
| fixPurchase | array | 儲值商店固定[物品](#purchase)(可空) |
| randomPurchase | array | 儲值商店隨機[物品](#purchase)(可空) |
| fixCounters | array | 一般商店固定[物品](#counters)(可空) |
| randomCounters | array | 一般商店隨機[物品](#counters)(可空) |


#### <span id="purchase">儲值商店商品內容</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradID | int | 交易序號 |
| name | string | 物品名稱 |
| amount | int | 物品數量 |
| IAP | string | ios product |
| IAB | string | android product |
|

#### <span id="counters">一般商店商品內容</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradID | int | 交易序號 |
| name | string | 物品名稱 |
| amount | int | 物品數量 |
| remainInventory | int | 剩餘庫存數量(-1:不限 0:售罄) |
| price | int | 售價 |
| currency | int | [貨幣](#Currency)種類 |
| discount | int | 折扣 |
|

## <span id="UIStyle">介面類型</span>
| 編碼 | 定義 |
|:-:|:-:|
| 1 | 固定商品 12 件、隨機商品 0 件 |
| 2 | 固定商品 8 件、隨機商品 4 件 |
| 3 | 固定商品 4 件、隨機商品 8 件 |
| 4 | 固定商品 0 件、隨機商品 12 件 |
|

## <span id="Currency">貨幣</span>
| 編碼 | 定義 |
|:-:|:-:|
| 1 | 火星幣 |
| 2 | 寶石 |
| 3 | PT幣 |
| 4 | 火星幣賽入場券 |
| 5 | PT幣賽入場券 |
| 6 | 群體賽入場券 |

### Example

	{
	    "error": {
	        "code": 0,
	        "message": ""
	    }
	}