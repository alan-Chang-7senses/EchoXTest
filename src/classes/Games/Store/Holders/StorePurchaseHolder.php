<?php

namespace Games\Store\Holders;

use stdClass;

/*
 * Description of StorePurchaseHolder
 * static DB
 */

class StorePurchaseHolder extends stdClass {

    /** @var int 索引值 */
    public int $pIndex;

    /** @var int 群組 */
    public int $groupID;

    /** @var int 課金Id */
    public int $purchaseID;

    /** @var int 商品Id */
    public int $itemID;

    /** @var int 商品數量 */
    public int $amount;

    /** @var string 商品ProductID */
    public string $productID;


}
