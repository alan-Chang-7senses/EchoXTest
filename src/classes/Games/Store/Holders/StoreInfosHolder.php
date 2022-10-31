<?php

namespace Games\Store\Holders;

use stdClass;

/*
 * Description of StoreInfosHolder
 * 交易商店資訊 MainDB
 */

class StoreInfosHolder extends stdClass {

    /** @var int 商店資訊編號 */
    public int $storeInfoID;

    /** @var int 使用者編號 */
    public int $userID;

    /** @var int 商店編號 */
    public int $storeID;

    /** @var string (json) 固定商品 */
    public string $fixTradIDs;

    /** @var string (json) 隨機商品 */
    public string $randomTradIDs;

    /** @var int 剩餘刷新次數 */
    public int $refreshRemainAmounts;

    /** @var int 建立時間 */
    public int $createTime;

    /** @var int 更新時間 */
    public int $updateTime;

}
