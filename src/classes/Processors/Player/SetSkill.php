<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Games\Exceptions\PlayerException;
use Holders\ResultData;
use Processors\BaseProcessor;
use Helpers\InputHelper;
use Generators\DataGenerator;
use Games\Skills\SkillHandler;


class SetSkill extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $id = InputHelper::post('playerID');
        $skillsData = json_decode(InputHelper::post('skillsData'));
        DataGenerator::ExistProperties($skillsData[0], ['skillID', 'slot']);

        for($i=0; $i<count($skillsData); $i++)
        {
            if($skillsData[$i]->slot<=6)
            {
                $skillHandler = new SkillHandler($skillsData[$i]->skillID);
                $skillHandler->SetSkillSlot($id,$skillsData[$i]->skillID,$skillsData[$i]->slot);
            }
            else{
                throw new PlayerException (PlayerException::OverSlot);
            }
        }
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}