<?php

namespace Games\Store\Models;

use stdClass;

/**
 * Description of StoreProductInfoModel
 * 儲值商店品項資訊
 */
class StoreProductInfoModel extends stdClass {

    /** @var string 商品Key */
    public string $ProductID;

    /** @var string 產品名稱(多語系編號) */
    public string $MultiNo;

    /** @var int 售價 */
    public int $Price;

    /** @var string 貨幣 */
    public string $ISOCurrency;

}
