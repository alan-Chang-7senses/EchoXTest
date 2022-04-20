<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\RaceHandler;
use Games\Scenes\SceneHandler;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use stdClass;
/**
 * Description of PlayerValues
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerValues extends BaseRace{
    
    private array $validValues = [
        'direction',
        'trackType',
        'trackShape',
        'rhythm',
        'trackNumber',
    ];
    
    public function Process(): ResultData {
        
        $hp = InputHelper::post('hp');
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceHandler->SetPlayer(new PlayerHandler($this->userInfo->player));
        
        $raceInfo = $raceHandler->GetInfo();
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $racePlayerInfo = $raceHandler->GetRacePlayerInfo();
        if($racePlayerInfo->status == RaceValue::StatusReach) throw new RaceException(RaceException::PlayerReached);
        
        $values = json_decode(InputHelper::post('values'));
        if($values === null) $values = new stdClass();
        DataGenerator::ValidProperties($values, $this->validValues);
        
        $values->hp = $hp * RaceValue::DivisorHP;
        $values->status = RaceValue::StatusUpdate;
        $values->updateTime = microtime(true);
        $raceHandler->SaveRacePlayer((array)$values);
        
        $raceHandler->SetSecne(new SceneHandler($this->userInfo->scene));
        
        $result = new ResultData(ErrorCode::Success);
        $result->h = $raceHandler->ValueH();
        $result->s = $raceHandler->ValueS();
        
        return $result;
    }
}
