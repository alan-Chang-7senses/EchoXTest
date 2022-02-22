<?php

namespace Processors\Races;

use Holders\ResultData;
use Helpers\InputHelper;
use Games\Races\RaceHandler;
use Games\Players\PlayerHandler;
use Consts\ErrorCode;
use Games\Scenes\SceneHandler;
/**
 * Description of PlayerValues
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerValues extends BaseRace{
    
    public function Process(): ResultData {
        
        $values = InputHelper::post('values');
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceHandler->SetPlayer(new PlayerHandler($this->userInfo->player));
        $raceHandler->SetSecne(new SceneHandler($this->userInfo->scene));
        
        $values = json_decode($values);
        
        $raceHandler->SaveRacePlayer((array)$values);
        
        $result = new ResultData(ErrorCode::Success);
        $result->h = $raceHandler->ValueH();
        $result->s = $raceHandler->ValueS();
        
        return $result;
    }
}
