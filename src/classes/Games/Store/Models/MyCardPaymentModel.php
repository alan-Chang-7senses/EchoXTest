<?php

namespace Games\Store\Models;

use stdClass;

/**
 * Description of MyCardPaymentModel
 * 儲值資訊
 */

class MyCardPaymentModel extends stdClass{

   /** @var string 付費方式 */
   public string $PaymentType;

   /** @var string MyCard 交易序 */
   public string $TradeSeq;

   /** @var string 交易序號 */
   public string $MyCardTradeNo;

   /** @var int 廠商交易序號 */
   public string $FacTradeSeq;

   /** @var int 使用者編號 */
   public int $CustomerId;

   /** @var int 支付金額 */
   public int $Amount;

   /** @var string 支付的幣種 */
   public string $Currency;

   /** @var int 建立時間 */
   public int $TradeDateTime;

   /** @var int 創立帳號時間 */
   public int $CreateAccountDateTime;

   /** @var string 創立帳號 IP */
   public string $CreateAccountIP;

}
