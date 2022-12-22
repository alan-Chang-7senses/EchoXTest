<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\AccessorFactory;
use Games\Players\Holders\SkillUpgradeItemsHolder;
use stdClass;

class SkillUpgradeItemsPool extends PoolAccessor
{
    protected string $keyPrefix = 'SkillUpgradeItems_';
    private static SkillUpgradeItemsPool $instance;

    public static function Instance() : SkillUpgradeItemsPool
    {
        if(empty(self::$instance))self::$instance = new SkillUpgradeItemsPool();
        return self::$instance;
    }

    public function FromDB(int|string $id): stdClass|false
    {
        $idPairs = array_map('intval',explode('_', $id));
        $holder = new SkillUpgradeItemsHolder();
        $row = AccessorFactory::Static()->FromTable('SkillUpgradeItems')
                                 ->WhereEqual('UpgradeLevel',$idPairs[0])
                                 ->WhereEqual('SpecieCode',  $idPairs[1])
                                 ->Fetch();
        if($row === false)return false;
        $holder->upgradeLevel = $row->UpgradeLevel;
        $holder->specieCode = $row->SpecieCode;
        $holder->items[] = [$row->BaseItemID => $row->BaseItemAmount];
        $holder->items[] = [$row->ChipItemID => $row->ChipAmount];
        $holder->coinCost = $row->CoinCost;
        return $holder;
    }
}