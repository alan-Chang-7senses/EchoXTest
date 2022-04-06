<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Exceptions\UserException;
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
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        if($userInfo->race != RaceValue::NotInRace) throw new RaceException(RaceException::UserInRace);

        $playerID = InputHelper::post('player');
        if(!in_array($playerID, $userInfo->players)) throw new UserException(UserException::NotHoldPlayer, ['[player]' => $playerID]);
        
        $userHandler->SaveData(['player' => $playerID]);
        
        $playerInfo = (new PlayerHandler($playerID))->GetInfo();
        $player = clone $playerInfo;
        unset($player->dna);
        
        $result = new ResultData(ErrorCode::Success);
        $result->player = $player;
        
        return $result;
    }
}
