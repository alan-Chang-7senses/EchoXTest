<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\SkillAccessor;
use Games\Consts\SkillValue;
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
        $skill->name = $skillInfo->SkillName;
        $skill->description = $skillInfo->Description;
        $skill->cooldown = $skillInfo->Cooldown / SkillValue::DivisorCooldown;
        $skill->energy = explode(',', $skillInfo->Energy);
        $skill->ranks = [$skillInfo->Level1, $skillInfo->Level2, $skillInfo->Level3, $skillInfo->Level4, $skillInfo->Level5];
        $skill->effects = explode(',', $skillInfo->Effect);
        $skill->maxDescription = $skillInfo->MaxDescription;
        $skill->maxCondition = $skillInfo->MaxCondition;
        $skill->maxConditionValue = $skillInfo->MaxConditionValue;
        $skill->maxEffects = explode(',', $skillInfo->MaxEffect);
        
        return DataGenerator::ConventType($skill, 'stdClass');
    }

}
