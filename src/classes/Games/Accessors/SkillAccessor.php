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
    
    public function rowsInfoByAliasCodes(array $codes) : array{
        return $this->StaticAcceessor()->FromTable('SkillInfo')->WhereIn('AliasCode', $codes)->FetchAll();
    }
    
    public function rowsEffectByEffectID(array $ids) : array{
        return $this->StaticAcceessor()->FromTable('SkillEffect')->WhereIn('SkillEffectID', $ids)->FetchAll();
    }
}
