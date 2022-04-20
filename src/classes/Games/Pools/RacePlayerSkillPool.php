<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\RaceAccessor;
use stdClass;
/**
 * Description of RacePlayerSkillPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerSkillPool extends PoolAccessor{
    
    private static RacePlayerSkillPool $instance;

    public static function Instance() : RacePlayerSkillPool{
        if(empty(self::$instance)) self::$instance = new RacePlayerSkillPool ();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'racePlayerSkill_';

    public function FromDB(int|string $id): stdClass|false {
        
        $raceAccessor = new RaceAccessor();
        $holder = new stdClass();
        
        $rows = $raceAccessor->rowsPlayerSkillByRacePlayerID($id);
        foreach($rows as $row) $holder->{$row->Serial} = $row;
        
        return $holder;
    }
    
    protected function SaveData(stdClass $data, array $values) : stdClass{
        
        if(!isset($values['Serial'])) return $data;
        
        $serial = $values['Serial'];
        unset($values['Serial']);
        $bind = [];
        foreach($values as $key => $value){
            $key = ucfirst($key);
            $bind[$key] = $value;
            $data->$serial->$key = $value;
        }
        
        (new RaceAccessor())->ModifyRacePlayerSkillBySerial($serial, $bind);
        
        return $data;
    }
    
    protected function SaveNewData(stdClass $data, array $bind) : stdClass{
        
        $serial = (new RaceAccessor())->AddRacePlayerSkill($bind);
        $values = [
            'Serial' => $serial,
            'RacePlayerID' => $bind['RacePlayerID'],
            'Status' => $bind['Status'] ?? 0,
            'CreateTime' => $bind['CreateTime'],
            'SkillID' => $bind['SkillID'],
            'LaunchMax' => $bind['LanuchMax'] ?? 0,
            'Result' => $bind['Result'] ?? 0,
        ];
        
        $data->$serial = (object)$values;
        
        return $data;
    }
}
