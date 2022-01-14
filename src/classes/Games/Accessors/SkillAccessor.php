<?php

namespace Games\Accessors;
/**
 * Description of SkillAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillAccessor extends BaseAccessor{
    
    public function aliasCodesByPart(string $code, int $type) : array{
        $row = $this->StaticAcceessor()->FromTable('SkillPart')->SelectExpr('CONCAT_WS(\',\', AliasCode1, AliasCode2, AliasCode3) AS aliasCode')
                ->WhereEqual('PartCode', $code)->WhereEqual('PartType', $type)->Fetch();
        return empty($row) ? [] : explode(',', $row->aliasCode);
    }
    
    public function rowInfoBySkillID(int|string $id) : mixed{
        return $this->StaticAcceessor()->FromTable('SkillInfo')->WhereEqual('SkillID', $id)->Fetch();
    }
    
    public function rowEffectByEffectID(int|string $id) : mixed{
        return $this->StaticAcceessor()->FromTable('SkillEffect')->WhereEqual('SkillEffectID', $id)->Fetch();
    }
    
    public function rowMaxEffectByMaxEffectID(int|string $id) : mixed{
        return $this->StaticAcceessor()->FromTable('SkillMaxEffect')->WhereEqual('MaxEffectID', $id)->Fetch();
    }

    public function rowsInfoByAliasCodes(array $codes) : array{
        return $this->StaticAcceessor()->FromTable('SkillInfo')->WhereIn('AliasCode', $codes)->FetchAll();
    }
    
    public function rowsEffectByEffectIDs(array $ids) : array{
        return $this->StaticAcceessor()->FromTable('SkillEffect')->WhereIn('SkillEffectID', $ids)->FetchAll();
    }
    
    public function rowsMaxEffectByEffectIDs(array $ids) : array{
        return $this->StaticAcceessor()->FromTable('SkillMaxEffect')->WhereIn('MaxEffectID', $ids)->FetchAll();
    }
}
