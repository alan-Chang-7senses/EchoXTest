<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\SkillAccessor;
use Games\Skills\Holders\SkillInfoHolder;
use Generators\DataGenerator;
use stdClass;
/**
 * Description of SkillInfo
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillPool extends PoolAccessor{
    
    private static SkillPool $instance;
    
    public static function Instance() : SkillPool {
        if(empty(self::$instance)) self::$instance = new SkillPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'skill_';

    public function FromDB(int|string $id): stdClass|false {
        
        $skillAccessor = new SkillAccessor();
        $skillInfo = $skillAccessor->rowInfoBySkillID($id);
        if($skillInfo === false) return false;
        
        $skill = new SkillInfoHolder();
        $skill->id = $skillInfo->SkillID;
        $skill->type = $skillInfo->TriggerType;
        $skill->level = 1;
        $skill->name = $skillInfo->SkillName;
        $skill->ranks = [$skillInfo->Level1, $skillInfo->Level2, $skillInfo->Level3, $skillInfo->Level4, $skillInfo->Level5];
        
        $rows = $skillAccessor->rowsEffectByEffectIDs(explode(',', $skillInfo->Effect));
        $skill->effects = [];
        foreach ($rows as $row) $skill->effects[] = $row->SkillEffectID;
        
        $rows = $skillAccessor->rowsMaxEffectByEffectIDs(explode(',', $skillInfo->MaxEffect));
        $skill->maxEffects = [];
        foreach ($rows as $row) $skill->maxEffects[] = $row->MaxEffectID;
        
        return DataGenerator::ConventType($skill, 'stdClass');
    }

}
