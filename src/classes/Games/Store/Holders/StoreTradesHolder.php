<?php

namespace Games\Store\Holders;

use stdClass;

/*
 * Description of StoreTradesHolder
 * 交易資訊 Main DB
 */

class StoreTradesHolder extends stdClass {

    /** @var int 交易序號 */
    public int $tradeID;

    /** @var int 使用者編號 */
    public int $userID;

    /** @var int 狀態 */
    public int $status;

    /** @var int 商店類型 */
    public int $storeType;

    /** @var int 商店索引 */
    public int $cPIndex;

    /** @var int 剩餘庫存量 */
    public int $remainInventory;

    /** @var int 更新時間 */
    public int $updateTime;

}
