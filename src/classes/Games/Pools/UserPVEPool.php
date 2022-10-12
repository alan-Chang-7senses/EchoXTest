<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Accessors\PVEAccessor;
use Games\Consts\PVEValue;
use Games\Consts\RaceValue;
use Games\PVE\Holders\UserPVEInfoHolder;
use Games\PVE\PVELevelHandler;
use Games\PVE\UserPVEHandler;
use stdClass;

/**
 * 關卡相關資料。
 */
class UserPVEPool extends PoolAccessor
{       
    private static UserPVEPool $instance;
    protected string $keyPrefix = 'userPVE_';
    
    public static function Instance() : UserPVEPool {
        if(empty(self::$instance)) self::$instance = new UserPVEPool();
        return self::$instance;
    }
    
    public function FromDB(int|string $id): UserPVEInfoHolder|stdClass|false
    {
        $holder = new UserPVEInfoHolder();
        $holder->id = $id;
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->FromTable('UserPVELevel')
                ->WhereEqual('UserID',$id)
                ->FetchAll();
        if($rows === false)return false;
        $holder->currentProcessingLevel = null;
        foreach($rows as $row)
        {
            $pveInfo = (new PVELevelHandler($row->LevelID))->GetInfo();
            $holder->levelProcess[$pveInfo->chapterID][$row->LevelID] = $row->MedalAmount;
            $holder->raceRoomID = $row->RaceRoomID;
            if($row->Status == PVEValue::LevelStatusProcessing)
            {
                $holder->currentProcessingLevel =  $row->LevelID;
            }
        }        
        return $holder;
    } 

    protected function SaveLevel(stdClass $data, array $values) : stdClass{
        
        $bind = [];
        foreach($values as $key => $value){
            $bind[ucfirst($key)] = $value;
        }
        // 將從快取取出的stdClass型別轉換成array
        UserPVEHandler::LevelInfoToArray($data);
        $levelInfo = (new PVELevelHandler($bind['LevelID']))->GetInfo();
        $medalAmount = isset($data->levelProcess[$levelInfo->chapterID][$levelInfo->levelID]) ? 
                            $data->levelProcess[$levelInfo->chapterID][$levelInfo->levelID] : 
                            0;
        //新的獎牌數量必須比較多才會以新的數量存檔。                            
        if(isset($bind['MedalAmount']) && $medalAmount < $bind['MedalAmount'])
        {
            $medalAmount = $bind['MedalAmount'];
        }
        $bind['MedalAmount'] = $medalAmount;
        $data->levelProcess[$levelInfo->chapterID][$levelInfo->levelID] = $bind['MedalAmount'];

        $data->currentProcessingLevel = $bind['Status'] == PVEValue::LevelStatusProcessing ? 
                                        $bind['LevelID'] :
                                        null;
        if(!isset($bind['RaceRoomID']))$bind['RaceRoomID'] = RaceValue::NotInRoom;
        $data->raceRoomID = $bind['RaceRoomID'];
        //取代的話會造成有不該刷新的值被刷新。所以所有欄位的資料都需要有                                        
        (new PVEAccessor())->AddLevelInfo($bind);
        return $data;
    }
}