<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Consts\Sessions;
use Games\Accessors\PVEAccessor;
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

    protected function SaveClearLevel(stdClass $data, array $values) : stdClass{
        
        $bind = [];
        foreach($values as $key => $value){
            $bind[ucfirst($key)] = $value;
        }
        $levelInfo = (new PVELevelHandler($bind['LevelID']))->GetInfo();
        $medalAmount = isset($bind['MedalAmount']) ? $bind['MedalAmount'] : 1;
        $userID = isset($bind['UserID']) ? $bind['UserID'] : $_SESSION[Sessions::UserID];
        // 將從快取取出的stdClass型別轉換成array
        if($data->clearLevelInfo instanceof stdClass)
        {
            $infoTemp = [];
            foreach($data->clearLevelInfo as $chapterID => $chapterInfo)
            {
                foreach($chapterInfo as $levelID => $medal)
                {
                    $infoTemp[$chapterID][$levelID] = $medal;
                }
            }
            $data->clearLevelInfo = $infoTemp;
        }
        $data->clearLevelInfo[$levelInfo->chapterID][$levelInfo->levelID] = $medalAmount;
        
        (new PVEAccessor())->AddClearInfo($userID,$levelInfo->levelID,$medalAmount);        
        return $data;
    }
}