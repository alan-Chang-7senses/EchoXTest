<?php

namespace Games\Accessors;

use PDO;
/**
 * Description of RaceAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceAccessor extends BaseAccessor{
    
    public function rowInfoByID(int $id) : mixed{
        return $this->MainAccessor()->FromTable('Races')->WhereEqual('RaceID', $id)->Fetch();
    }
    
    public function rowPlayerByID(int $id) : mixed{
        return $this->MainAccessor()->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $id)->Fetch();
    }

    public function rowsPlayerByRaceIDFetchAssoc(int $id) : array{
        return $this->MainAccessor()->FromTable('RacePlayer')
                ->WhereEqual('RaceID', $id)->FetchStyle(PDO::FETCH_ASSOC)->FetchAll();
    }
    
    public function rowsPlayerSkillByRacePlayerID(int $id) : array{
        return $this->MainAccessor()->FromTable('RacePlayerSkill')
                ->WhereEqual('RacePlayerID', $id)->FetchAll();
    }

    public function AddRace(int $sceneID, float $createTime, int $windDirection) : string{
        
        $this->MainAccessor()->FromTable('Races')->Add([
            'SceneID' => $sceneID,
            'CreateTime' => $createTime,
            'UpdateTime' => $createTime,
            'WindDirection' => $windDirection,
        ]);
        
        return $this->MainAccessor()->LastInsertID();
    }
    
    public function AddRacePlayer(array $player) : string{
        
        $this->MainAccessor()->FromTable('RacePlayer')->Add($player);
        return $this->MainAccessor()->LastInsertID();
    }
    
    public function AddRacePlayerSkill(array $bind) : string{
        $this->MainAccessor()->FromTable('RacePlayerSkill')->Add($bind);
        return $this->MainAccessor()->LastInsertID();
    }
    
    public function ModifyRacePlayerIDsByID(int $id, string $idData, float $updateTime) : bool{
        return $this->MainAccessor()->FromTable('Races')->WhereEqual('RaceID', $id)->Modify(['RacePlayerIDs' => $idData, 'UpdateTime' => $updateTime]);
    }
    
    public function ModifyRacePlayerValuesByID(int $id, array $bind) : bool {
        return $this->MainAccessor()->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $id)->Modify($bind);
    }
    
    public function ModifyRacePlayerSkillBySerial(int $serial, array $bind) : bool{
        return $this->MainAccessor()->FromTable('RacePlayerSkill')->WhereEqual('Serial', $serial)->Modify($bind);
    }
    
    public function FinishRaceByRaceID(int $id, int $status) : array{
        return $this->MainAccessor()->CallProcedure('RaceFinish', ['raceID' => $id, 'status' => $status, 'time' => microtime(true)]);
    }
}
