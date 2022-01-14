<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Games\DataPools\PlayerInfo;
use Games\DataPools\SkillInfo;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of CharacterData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CharacterData extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $playerID = InputHelper::post('characterID');
        
        $playerInfo = PlayerInfo::Instance()->$playerID;
        
        $skillInfoPool = SkillInfo::Instance();
        $skills = [];
        foreach($playerInfo->skills as $skill){
            $skillInfo = $skillInfoPool->{$skill->skillID};
            $skillInfo->level = $skill->level;
            $skills[] = $skillInfo;
        }
        
        $playerInfo->skills = $skills;
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $playerInfo;
        
        return $result;
    }
}
