<?php

namespace Games\Store\Holders;

/**
 * Description of RevenueHolder
 * 儲值資訊
 */
class RevenueHolder {

    /** @var int 流水號 */
    public int $serial;

    /** @var int 使用者編號 */
    public int $userID;

    /** @var int 平台 */
    public int $plat;

    /** @var int 支付金額 */
    public int $payAmount;

    /** @var string 支付的幣種 */
    public string $payCurrency;

    /** @var int 建立時間 */
    public int $createTime;

}
