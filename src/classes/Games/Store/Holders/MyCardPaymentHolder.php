<?php

namespace Games\Store\Holders;

/**
 * Description of MyCardPaymentHolder
 * MyCard儲值資訊
 */

class MyCardPaymentHolder {

   /** @var int 流水號 */
   public int $orderID;

   /** @var int 使用者編號 */
   public int $userID;

   /** @var string 付費方式 */
   public string $paymentType;

   /** @var int 支付金額 */
   public int $amount;

   /** @var string 支付的幣種 */
   public string $currency;

   /** @var string 交易序號 */
   public string $myCardTradeNo;

   /** @var string 通路代碼 */
   public string $myCardType;

   /** @var string 活動代碼 */
   public string $promoCode;

   /** @var string 訂閱代碼 */
   public string $serialId;

   /** @var int 建立時間 */
   public int $createTime;


}
