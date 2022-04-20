<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Games\Players\PlayerHandler;
use Games\Skills\SkillHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Skills
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Skills extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $id = InputHelper::post('id');
        
        $playerHandler = new PlayerHandler($id);
        $playerInfo = $playerHandler->GetInfo();
        
        $skills = [];
        foreach ($playerInfo->skills as $playerSkill){
            
            $handler = new SkillHandler($playerSkill->id);
            $handler->playerHandler = $playerHandler;
            $skillInfo = $handler->GetInfo();
            
            $skills[] = [
                'id' => $skillInfo->id,
                'name' => $skillInfo->name,
                'description' => $skillInfo->description,
                'level' => $playerSkill->level,
                'slot' => $playerSkill->slot,
                'energy' => $skillInfo->energy,
                'cooldown' => $skillInfo->cooldown,
                'duration' => $skillInfo->duration,
                'ranks' => $skillInfo->ranks,
                'maxDescription' => $skillInfo->maxDescription,
                'maxCondition' => $skillInfo->maxCondition,
                'maxConditionValue' => $skillInfo->maxConditionValue,
                'effects' => $handler->GetEffects(),
                'maxEffects' => $handler->GetMaxEffects(),
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->skills = $skills;
        return $result;
    }
}
