<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\AccessorFactory;
use stdClass;

class MetadataActivityPool extends PoolAccessor
{
    private static MetadataActivityPool $instance;
    
    public static function Instance() : MetadataActivityPool {
        if(empty(self::$instance)) self::$instance = new MetadataActivityPool ();
        return self::$instance;
    }
    protected string $keyPrefix = 'metadataActivity_';

    public function FromDB(int|string $id): stdClass|false
    {
        $holder = new stdClass();
        $row = AccessorFactory::Static()->FromTable('MetadataActivity')
                    ->WhereEqual('ActivityName',$id)
                    ->Fetch();
        if($row === false)return false;                    
        $holder->ActivityName = $row->ActivityName;
        $holder->Source = $row->Source;
        $holder->Native = $row->Native;
        $holder->SkeletonType = $row->SkeletonType;
        $holder->CreateRewardID = $row->CreateRewardID;
        $holder->CreateRewardAmount = $row->CreateRewardAmount;
        return $row;
    }
}