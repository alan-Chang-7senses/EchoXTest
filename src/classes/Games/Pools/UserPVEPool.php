<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\PVE\Holders\UserPVEInfoHolder;
use Games\PVE\PVELevelHandler;
use stdClass;

class UserPVEPool extends PoolAccessor
{       
    private static UserPVEPool $instance;
    
    public static function Instance() : UserPVEPool {
        if(empty(self::$instance)) self::$instance = new UserPVEPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'userPVE_';
    public function FromDB(int|string $id): UserPVEInfoHolder|stdClass|false
    {
        $holder = new UserPVEInfoHolder();
        $holder->id = $id;
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->FromTable('UserPVELevel')
                ->WhereEqual('UserID',$id)
                ->FetchAll();
        if($rows === false)return false;                
        foreach($rows as $row)
        {
            $pveInfo = (new PVELevelHandler($row->LevelID))->GetInfo();
            $holder->clearLevelInfo[$pveInfo->chapterID][$row->LevelID] = $row->MedalAmount;
        }        
        return $holder;
    } 
}