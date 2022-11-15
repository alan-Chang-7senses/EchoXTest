<?php

namespace Games\Store\Holders;

use stdClass;

/*
 * Description of StoreCountersHolder
* static DB
 */

class StoreCountersHolder extends stdClass {

    /** @var int 索引值 */
    public int $cIndex;

    /** @var int 群組 */
    public int $groupID;

    /** @var int 專櫃Id */
    public int $counterID;

    /** @var int 商品Id */
    public int $itemID;

    /** @var int 商品數量 */
    public int $amount;

    /** @var int 庫存 */
    public int $inventory;

    /** @var int 售價 */
    public int $price;

    /** @var int 售價貨幣 */
    public int $currency;

    /** @var int 促銷類型 */
    public int $promotion;

}
