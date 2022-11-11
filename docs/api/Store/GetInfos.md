# 商店 - 資訊

## 介紹

- 遊戲中各種商城的資訊，提供貨幣交易、資源兌換的需求。
- 需要完成登入驗證才可正常使用此API。

## URL

http(s)://`域名`/Store/GetInfos/

## Method

`GET`

## Request

Content Type: `application/x-www-form-urlencoded`

### 參數

| 參數名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| device | int | 裝置(1:iOS 2:Android) |
| plat | int | 目前平台(1:Apple 2:Google 3:MyCard) |
<br>

## Response

Content Type: `application/json`

### 回應格式

| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| error | object | 錯誤代碼與訊息<br>（[Response 的 error 內容](../response.md#error)） |
| currencies | array | 按[貨幣](#Currency)順序 |
| autoRefreshTime | int | 自動刷新剩餘時間(s) |
| stores | array | [商店資訊](#storeData) |


#### <span id="storeData">商店資訊</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| storeInfoID | int | 商店編號 |
| uiStyle | int | [介面類型](#UIStyle) |
| refreshRemain | int | 剩餘刷新次數 |
| refreshMax | int | 每日刷新次數 |
| refreshCost | int | 刷新費用 |
| refreshCurrency | int | 刷新費用之[貨幣](#Currency) |
| storetype| int | 商店類別<Br>(1.一般商品, 2.Apple商品 3.Google商品 4.MyCard商品 ) |
| fixItems | array | 固定[一般](#counters)/[儲值](#purchase)商店商品](可空) |
| randomItems | array | 隨機[一般](#counters)/[儲值](#purchase)商店商品(可空) |
<br>

#### <span id="counters">一般商店商品</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradID | int | 交易序號 |
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
| name | string | 物品名稱 |
| remainInventory | int | 剩餘庫存數量(-1:不限 0:售罄) |
| price | int | 售價 |
| currency | int | [貨幣](#Currency)種類 |
| promotion | int | 折扣 |
<br>

#### <span id="purchase">儲值商店商品</span>
_此欄位資料為物件陣列，以下為單一陣列元素的物件內容：_
| 名稱 | 類型 | 說明 |
|:-:|:-:|:-:|
| tradID | int | 交易序號 |
| itemID | int | 物品編號 |
| amount | int | 物品數量 |
| icon | string | Icon 圖號 |
| name | string | 物品名稱 |
| product | string | product key |
| multiNo | string | 商品名稱，多國語系編號 |
| currency | string | 幣別(TWD, USD...) |
| price | int | 價格 |
<br>
#### <span id="UIStyle">介面類型</span>
| 編碼 | 定義 |
|:-:|:-:|
| 1 | 固定商品 12 件、隨機商品 0 件 |
| 2 | 固定商品 9 件、隨機商品 3 件 |
| 3 | 固定商品 3 件、隨機商品 9 件 |
| 4 | 固定商品 0 件、隨機商品 12 件 |
<br>

#### <span id="Currency">貨幣</span>
| 編碼 | 定義 |
|:-:|:-:|
| 1 | 火星幣 |
| 2 | 寶石 |
| 3 | PT幣 |
| 4 | 火星幣賽入場券 |
| 5 | PT幣賽入場券 |
| 6 | 群體賽入場券 |
<br>

### Example

    {
        "error": {
            "code": 0,
            "message": ""
        },
        "currencies": [
            849,
            802,
            0,
            0,
            0,
            1
        ],
        "autoRefreshTime": 78001,
        "stores": [
            {
                "storeInfoID": 1,
                "uiStyle": 1,
                "refreshRemain": 0,
                "refreshMax": 0,
                "refreshCost": 0,
                "refreshCurrency": 0,
                "storetype": 1,
                "fixItems": [
                    {
                        "tradID": 1,
                        "itemID": 1001,
                        "amount": 1,
                        "icon": "ItemIcon_0001",
                        "name": "8101",
                        "remainInventory": 3,
                        "price": 100,
                        "currency": 1,
                        "promotion": 3
                    },
                    {
                        "tradID": 2,
                        "itemID": 1001,
                        "amount": 1,
                        "icon": "ItemIcon_0001",
                        "name": "8101",
                        "remainInventory": 3,
                        "price": 100,
                        "currency": 1,
                        "promotion": 3
                    },
                    {
                        "tradID": 3,
                        "itemID": 1001,
                        "amount": 1,
                        "icon": "ItemIcon_0001",
                        "name": "8101",
                        "remainInventory": 3,
                        "price": 100,
                        "currency": 1,
                        "promotion": 3
                    }
                ],
                "randomItems": []
            },
            {
                "storeInfoID": 2,
                "uiStyle": 3,
                "refreshRemain": 3,
                "refreshMax": 3,
                "refreshCost": 200,
                "refreshCurrency": 2,
                "storetype": 4,
                "fixItems": [
                    {
                        "tradID": 4,
                        "itemID": -3,
                        "amount": 1,
                        "icon": "ItemIcon_0028",
                        "name": "8134",
                        "product": "003",
                        "multiNo": "測試003",
                        "currency": "USD",
                        "price": 40
                    },
                    {
                        "tradID": 5,
                        "itemID": -3,
                        "amount": 1,
                        "icon": "ItemIcon_0028",
                        "name": "8134",
                        "product": "005",
                        "multiNo": "測試007",
                        "currency": "USD",
                        "price": 40
                    },
                    {
                        "tradID": 6,
                        "itemID": -3,
                        "amount": 1,
                        "icon": "ItemIcon_0028",
                        "name": "8134",
                        "product": "008",
                        "multiNo": "測試008",
                        "currency": "USD",
                        "price": 50
                    }
                ],
                "randomItems": []
            }
        ]
    }