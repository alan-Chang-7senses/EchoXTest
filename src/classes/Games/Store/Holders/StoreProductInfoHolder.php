<?php

namespace Games\Store\Holders;

use stdClass;

/**
 * Description of StoreProductInfoHolder
 * 儲值商店品項資訊
 */

class StoreProductInfoHolder extends stdClass  {

   /** @var string 商品Key */
   public string $productID;

   /** @var string 產品名稱 */
   public string $productName;

   /** @var int 數量 */
   public int $amount;

   /** @var string 幣制 */
   public string $currency;


}
