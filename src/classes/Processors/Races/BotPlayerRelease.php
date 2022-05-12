<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\PlayerValue;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Races\BaseRace;
/**
 * Description of BotPlayerRelease
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class BotPlayerRelease extends BaseRace {
    
    protected bool|null $mustInRace = false;
    
    public function Process(): ResultData {
        
        $id = InputHelper::post('id');
        
        if($id >= PlayerValue::BotIDLimit) throw new RaceException(RaceException::NotBotPlayer);
        
        $handler = new UserHandler($id);
        $info = $handler->GetInfo();
        
        if($info->race != RaceValue::BotMatch) throw new RaceException(RaceException::NotBotInMatch);
        
        $handler->SaveData(['Race' => RaceValue::NotInRace]);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
