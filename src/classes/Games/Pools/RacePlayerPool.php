<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\RaceAccessor;
use Games\Races\Holders\RacePlayerHolder;
use Generators\DataGenerator;
use stdClass;
/**
 * Description of RacePlayerPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerPool extends PoolAccessor{
    
    private static RacePlayerPool $instance;
    
    public static function Instance() : RacePlayerPool{
        if(empty(self::$instance)) self::$instance = new RacePlayerPool ();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'racePlayer_';
    
    public function FromDB(int|string $id): stdClass|false {
        
        $raceAccessor = new RaceAccessor();
        $row = $raceAccessor->rowPlayerByID($id);
        
        $holder = new RacePlayerHolder();
        $holder->id = $id;
        $holder->race = $row->RaceID;
        $holder->user = $row->UserID;
        $holder->player = $row->PlayerID;
        $holder->number = $row->RaceNumber;
        $holder->direction = $row->Direction;
        $holder->energy = explode(',', $row->Energy);
        $holder->trackType = $row->TrackType;
        $holder->trackShape = $row->TrackShape;
        $holder->rhythm = $row->Rhythm;
        $holder->ranking = $row->Ranking;
        $holder->trackNumber = $row->TrackNumber;
        $holder->hp = $row->HP;
        
        return DataGenerator::ConventType($holder, 'stdClass');
    }
    
    protected function SaveData(stdClass $data, array $values) : stdClass{
        
        $bind = [];
        foreach($values as $key => $value){
            $bind[ucfirst($key)] = $value;
            $data->$key = $value;
        }
        
        (new RaceAccessor())->ModifyRacePlayerValuesByID($data->id, $bind);
        
        return $data;
    }
}
