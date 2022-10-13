<?php

namespace Games\Store\Holders;

/*
 * Description of StorePurchaseHolder
 */

class StorePurchaseHolder {

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

   /** @var string 蘋果商品 */
   public string $iAP;

   /** @var string 安卓商品 */
   public string $iAB;


}
