<?php

namespace xx\Holders;

/*
 * Description of StoreDataHolder
 */

class StoreDataHolder {

   /** @var int 商店編號 */
   public int $storeID;

   /** @var int 是否開放 */
   public int $isOpen;

   /** @var int 商店類型 */
   public int $storeType;

   /** @var int 介面類型 */
   public int $uIStyle;

   /** @var int 固定商品專櫃群組 */
   public int $fixedGroup;

   /** @var int 隨機商品專櫃群組 */
   public int $stochasticGroup;

   /** @var int 每日刷新次數 */
   public int $refreshCount;

   /** @var int 刷新費用 */
   public int $refreshCost;

   /** @var int 刷新費用之貨幣 */
   public int $refreshCostCurrency;


}
