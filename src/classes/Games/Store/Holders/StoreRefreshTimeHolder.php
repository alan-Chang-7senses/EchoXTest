<?php

namespace Games\Store\Holders;

/*
 * Description of StoreRefreshHolder
 * 商店更新時間 Main DB
 */

class StoreRefreshTimeHolder {

    /** 需要更新 for server  */
    public bool $needRefresh;

    /** 剩餘更新時間(秒) for client */
    public int $remainSeconds;

}
