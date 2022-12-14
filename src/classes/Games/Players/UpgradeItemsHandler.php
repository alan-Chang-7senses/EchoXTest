<?php

namespace Games\Players;

use Accessors\PoolAccessor;
use Games\Exceptions\PlayerException;
use stdClass;

class UpgradeItemsHandler
{
    private stdClass $info;

    public function __construct(int $level, int $type, PoolAccessor $pool)
    {
        $this->rankUpInfo = null;
        $id = $level . '_' . $type;
        $this->info = $pool->$id;
        if($this->info == false) throw new PlayerException(PlayerException::NoUpgradeData);
    }

    /**
     * @param return [itemID(需求物品編號) => amount(物品需求數量)]
     */
    public function GetUpgradeItems() : array
    {
        $rt = [];
        foreach($this->info->items as $item)
        {
            foreach($item as $itemID => $amount)
            if(!empty($amount)) $rt[$itemID] = $amount;
        }
        return $rt;
    }

    public function GetCoinCost() : int
    {
        return $this->info->coinCost;
    }
}