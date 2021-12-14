<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Characters\Avatar;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of CharacterSelectData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CharacterSelectData extends BaseProcessor {
    
    public function Process(): ResultData {
        
        $userID = $_SESSION[Sessions::UserID];
        
        $offset = InputHelper::post('offset');
        $count = InputHelper::post('count');
        
        $result = new ResultData(ErrorCode::Success);
        $result->total = Avatar::TotalByUser($userID);
        $result->players = Avatar::CharactersPartByOffset($userID, $offset, $count);
        
        return $result;
    }
}
