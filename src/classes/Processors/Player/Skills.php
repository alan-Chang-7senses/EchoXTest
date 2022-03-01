<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Games\Players\PlayerHandler;
use Games\Skills\SkillEffectHandler;
use Games\Skills\SkillHandler;
use Games\Skills\SkillMaxEffectHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of Skills
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Skills extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $id = InputHelper::post('id');
        
        $playerInfo = (new PlayerHandler($id))->GetInfo();
        
        
        $skills = [];
        foreach ($playerInfo->skills as $playerSkill){
            
            $skillInfo = (new SkillHandler($playerSkill->skillID))->GetInfo();
            
            $skill = new stdClass();
            $skill->id = $skillInfo->id;
            $skill->name = $skillInfo->name;
            $skill->description = $skillInfo->description;
            $skill->type = $skillInfo->type;
            $skill->energy = $skillInfo->energy;
            $skill->cooldown = $skillInfo->cooldown;
            $skill->maxDescription = $skillInfo->maxDescription;
            $skill->maxCondition = $skillInfo->maxCondition;
            $skill->maxConditionValue = $skillInfo->maxConditionValue;
            
            $skill->effect = [];
            foreach($skillInfo->effects as $skillEffectID){
                
                $skillEffectInfo = (new SkillEffectHandler($skillEffectID))->GetInfo();
                
                $skill->effect[] = [
                    'type' => $skillEffectInfo->type,
                    'target' => $skillEffectInfo->target,
                    'duration' => $skillEffectInfo->duration,
                    'formulaValue' => 0, //未計算
                ];
            }
            
            $skill->maxEffect = [];
            foreach($skillInfo->maxEffects as $maxEffectID){
                
                $maxEffectInfo = (new SkillMaxEffectHandler($maxEffectID))->GetInfo();
                
                $skill->maxEffect[] = [
                    'type' => $maxEffectInfo->type,
                    'typeValue' => $maxEffectInfo->typeValue,
                    'formulaValue' => 0, //未計算
                ];
            }
            
            $skills[] = $skill;
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->skills = $skills;
        return $result;
    }
}
