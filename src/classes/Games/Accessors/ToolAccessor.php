<?php

namespace Games\Accessors;

/**
 * Description of ToolAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ToolAccessor extends BaseAccessor{
    
    public function rowsPlayerNFT() : array{
        return $this->MainAccessor()->FromTable('PlayerNFT')->FetchAll();
    }
    
    public function rowsSkillPart() : array{
        return $this->StaticAccessor()->FromTable('SkillPart')->FetchAll();
    }
    
    public function ClearSkillPart() : bool{
        return $this->StaticAccessor()->FromTable('SkillPart')->Truncate();
    }
    
    public function AddSkillParts(array $rows) : bool {
        return $this->StaticAccessor()->FromTable('SkillPart')->Ignore(true)->AddAll($rows);
    }

    public function ModifySkillPartByPart(string $code, int $type, array $bind) : bool{
        return $this->StaticAccessor()->FromTable('SkillPart')->WhereEqual('PartCode', $code)->WhereEqual('PartType', $type)->Modify($bind);
    }

    public function rowsPlayerSkill() : array{
        return $this->MainAccessor()->FromTable('PlayerSkill')->FetchAll();
    }
    
    public function ClearPlayerSkill() : bool{
        return $this->MainAccessor()->FromTable('PlayerSkill')->Truncate();
    }
    
    public function AddPlayerSkills(array $rows) : bool{
        return $this->MainAccessor()->FromTable('PlayerSkill')->Ignore(true)->AddAll($rows);
    } 
    
    public function ModifyPlayerSkillByPlayerAndSkill(int $playerID, int $skillID, array $bind) : bool{
        return $this->MainAccessor()->FromTable('PlayerSkill')->WhereEqual('PlayerID', $playerID)->WhereEqual('SkillID', $skillID)->Modify($bind);
    }
    
}
