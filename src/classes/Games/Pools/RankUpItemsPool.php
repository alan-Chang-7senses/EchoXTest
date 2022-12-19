<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\AccessorFactory;
use Games\Players\Holders\RankUpItemsHolder;
use stdClass;

class RankUpItemsPool extends PoolAccessor
{
    protected string $keyPrefix = 'rankUpItems_';
    private static RankUpItemsPool $instance;

    public static function Instance() : RankUpItemsPool
    {
        if(empty(self::$instance))self::$instance = new RankUpItemsPool();
        return self::$instance;
    }

    public function FromDB(int|string $id): stdClass|false
    {
        $holder = new RankUpItemsHolder();
        $idPairs = array_map('intval',explode('_', $id));
        $row = AccessorFactory::Static()->FromTable('RankUpItems')
                                 ->WhereEqual('RankUpLevel',$idPairs[0])
                                 ->WhereEqual('Attribute',  $idPairs[1])
                                 ->Fetch();
        if($row === false)return false;
        $holder->rankUpLevel = $row->RankUpLevel;
        $holder->attribute = $row->Attribute;
        $holder->items[] = [$row->DustItemID => $row->DustAmount];
        $holder->items[] = [$row->CrystalItemID => $row->CrystalAmount];
        $holder->coinCost = $row->CoinCost;
        return $holder;
    }
}