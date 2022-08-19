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
    
    public function rowsPlayerEffectByRacePlayerID(int $id) : array{
        return $this->MainAccessor()->FromTable('RacePlayerEffect')->WhereEqual('RacePlayerID', $id)->FetchAll();
    }

    public function AddRace(array $bind) : string{
        $this->MainAccessor()->FromTable('Races')->Add($bind);
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
    
    public function AddRacePlayerEffects(array $binds) : bool{
        return $this->MainAccessor()->FromTable('RacePlayerEffect')->AddAll($binds);
    }
    
    public function ModifyRaceByID(int $id, array $bind) : bool{
        return $this->MainAccessor()->FromTable('Races')->WhereEqual('RaceID', $id)->Modify($bind);
    }
    
    public function ModifyRacePlayerValuesByID(int $id, array $bind) : bool {
        return $this->MainAccessor()->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $id)->Modify($bind);
    }
}
