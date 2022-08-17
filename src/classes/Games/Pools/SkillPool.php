<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\SkillAccessor;
use Games\Consts\SkillValue;
use Games\Skills\Holders\SkillInfoHolder;
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
        $skill->name = $skillInfo->SkillName;
        $skill->icon = $skillInfo->Icon;
        $skill->description = $skillInfo->Description;
        $skill->cooldown = $skillInfo->Cooldown / SkillValue::DivisorCooldown;
        
        if($skillInfo->Duration > 0) $skillInfo->Duration /= SkillValue::DivisorDuration;
        $skill->duration = $skillInfo->Duration;
        
        $skill->energy = array_map('intval',explode(',', $skillInfo->Energy));
        $skill->ranks = array_map(function($value){ return $value / SkillValue::DivisorLevel; }, [$skillInfo->Level1, $skillInfo->Level2, $skillInfo->Level3, $skillInfo->Level4, $skillInfo->Level5]);
        $skill->effects = explode(',', $skillInfo->Effect);
        $skill->maxDescription = $skillInfo->MaxDescription;
        $skill->maxCondition = $skillInfo->MaxCondition;
        $skill->maxConditionValue = $skillInfo->MaxConditionValue;
        $skill->attackedDesc = $skillInfo->AttackedDesc;
        $skill->maxEffects = explode(',', $skillInfo->MaxEffect);
        return $skill;
    }

    public function SetSkillSlot(int $plyerID, int $skillID, int $slot): mixed{
        $skillAccessor = new SkillAccessor();
        return $skillAccessor->setSkillSlot( $plyerID,  $skillID,  $slot);
    }
}
