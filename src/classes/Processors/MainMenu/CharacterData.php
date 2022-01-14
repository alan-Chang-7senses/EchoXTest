<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Games\DataPools\PlayerPool;
use Games\DataPools\SkillPool;
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
        
        $player = PlayerPool::Instance()->$playerID;
        
        $skillPool = SkillPool::Instance();
        $skills = [];
        foreach($player->skills as $skill){
            $skillInfo = $skillPool->{$skill->skillID};
            $skillInfo->level = $skill->level;
            $skills[] = $skillInfo;
        }
        
        $player->skills = $skills;
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = $player;
        
        return $result;
    }
}
