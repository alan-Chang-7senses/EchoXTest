<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Players\PlayerHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of CurrentPlayer
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CurrentPlayer extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $playerID = InputHelper::post('player');
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userHandler->SaveData(['player' => $playerID]);
        
        $playerInfo = (new PlayerHandler($playerID))->GetInfo();
        $player = clone $playerInfo;
        unset($player->dna);
        
        $result = new ResultData(ErrorCode::Success);
        $result->player = $player;
        
        return $result;
    }
}
