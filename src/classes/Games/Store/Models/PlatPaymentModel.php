<?php

namespace Games\Store\Models;

use stdClass;

/**
 * Description of PlatPaymentModel
 * 一般儲值資訊
 */
class PlatPaymentModel extends stdClass {
    /** @var int 流水號 */
    public int $Serial;

    /** @var int 使用者編號 */
    public int $UserID;

    /** @var int 付費平台 */
    public int $PlatType;

    /** @var string 平台交易收據 */
    public string $Receipt;

    /** @var string 商品下單序號 */
    public string $OrderID;

    /** @var int 支付金額 */
    public int $Amount;

    /** @var string 支付的幣種 */
    public string $Currency;

    /** @var int 時間 */
    public int $LogTime;

}
