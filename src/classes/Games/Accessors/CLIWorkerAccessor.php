<?php

namespace Games\Accessors;

/**
 * Description of CLIWorkerAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CLIWorkerAccessor extends BaseAccessor{
    
    public function rowsPlayerSkill() : array{
        return $this->MainAccessor()->FromTable('PlayerSkill')->FetchAll();
    }
    
    public function rowsPlayerNFT() : array{
        return $this->MainAccessor()->FromTable('PlayerNFT')->FetchAll();
    }
    
    public function rowsSkillPart() : array{
        return $this->StaticAccessor()->FromTable('SkillPart')->FetchAll();
    }
    
    public function rowsSkillAffix() : array{
        return $this->StaticAccessor()->FromTable('SkillAffixAlias')->FetchAll();
    }

    public function rowsSkillInfoAccoc() : array{
        return $this->StaticAccessor()->FromTable('SkillInfo')->FetchStyleAssoc()->FetchAll();
    }
    
    public function InsertPlayerSkillsWithIgnore(array $rows) : bool{
        return $this->MainAccessor()->FromTable('PlayerSkill')->Ignore(true)->AddAll($rows);
    }
    
    public function UpdatePlayerSkillByPlayerSkillID(int $playerID, int $skillID, array $bind) : bool {
        return $this->MainAccessor()->FromTable('PlayerSkill')->WhereEqual('PlayerID', $playerID)->WhereEqual('SkillID', $skillID)
                ->PrepareName('UpdatePlayerSkillByPlayerSkillID')->Modify($bind);
    }
    
    public function ClearPlayerSkill() : bool{
        return $this->MainAccessor()->FromTable('PlayerSkill')->Truncate();
    }
}
