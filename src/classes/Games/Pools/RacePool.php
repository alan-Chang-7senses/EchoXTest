<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Consts\Globals;
use Games\Accessors\RaceAccessor;
use Games\Races\Holders\RaceInfoHolder;
use stdClass;
/**
 * Description of RacePool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePool extends PoolAccessor{
    
    private static RacePool $instance;
    
    public static function Instance() : RacePool {
        if(empty(self::$instance)) self::$instance = new RacePool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'race_';

    public function FromDB(int|string $id): stdClass|false {
        
        $raceAccessor = new RaceAccessor();
        $row = $raceAccessor->rowInfoByID($id);
        
        $holder = new RaceInfoHolder();
        $holder->id = $id;
        $holder->scene = $row->SceneID;
        $holder->status = $row->Status;
        $holder->weather = $row->Weather;
        $holder->windDirection = $row->WindDirection;
        $holder->racePlayers = json_decode($row->RacePlayerIDs ?? '{}');
        $holder->createTime = $row->CreateTime;
        $holder->updateTime = $row->UpdateTime;
        $holder->finishTime = $row->FinishTime;
        
        return $holder;
    }
    
    protected function SaveData(stdClass $data, array $values) : stdClass{
        
        $values['updateTime'] = $GLOBALS[Globals::TIME_BEGIN];
        
        $bind = [];
        foreach($values as $key => $value){
            
            if($key == 'racePlayers'){
                
                $value = json_encode($value);
                $bind['RacePlayerIDs'] = $value;
                $data->$key = json_decode($value);
                
            }else{
                
                $bind[ucfirst($key)] = $value;
                $data->$key = $value;
            }
        }
        
        (new RaceAccessor())->ModifyRaceByID($data->id, $bind);
        
        return $data;
    }
}
