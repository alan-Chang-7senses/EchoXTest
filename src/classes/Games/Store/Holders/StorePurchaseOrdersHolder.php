<?php

namespace Games\Store\Holders;

/**
 * Description of StorePurchaseOrdersHolder
 * 儲值訂單資訊
 */
class StorePurchaseOrdersHolder {

    /** @var int 訂單編號 */
    public int $orderID;

    /** @var int 使用者編號 */
    public int $userID;

    /** @var int 交易序號 */
    public int $tradeID;

    /** @var int 商品ID */
    public int $productID;

    /** @var int 商品物品ID */
    public int $itemID;

    /** @var int 商品數量 */
    public int $amount;

    /** @var int 平台 */
    public int $plat;

    /** @var int 狀態 */
    public int $status;

    /** @var string 收據 */
    public string $receipt;

    /** @var int 建立時間 */
    public int $createTime;

    /** @var int 更新時間 */
    public int $updateTime;

}
