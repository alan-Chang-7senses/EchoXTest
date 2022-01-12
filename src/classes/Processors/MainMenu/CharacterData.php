<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Games\Players\PlayerInfo;
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
        
        $result = new ResultData(ErrorCode::Success);
        $result->creature = PlayerInfo::Instance()->$playerID;
        
        return $result;
    }
}
