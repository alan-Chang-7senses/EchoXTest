<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerPool;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Scenes\SceneHandler;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use stdClass;
/**
 * Description of BasePlayerValues
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BasePlayerValues extends BaseRace{
    
    private array $validValues = [
        'direction',
        'trackType',
        'trackShape',
        'rhythm',
        'trackNumber',
        'ranking',
    ];
    
    abstract function GetPlayerID() : int;

    public function Process(): ResultData {
        
        $hp = InputHelper::post('hp');
        $playerID = $this->GetPlayerID();
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        
        if(!isset($raceInfo->racePlayers->{$playerID})){
            
            $accessor = new PDOAccessor(EnvVar::DBMain);
            $row = $accessor->FromTable('RacePlayer')->WhereEqual('RaceID', $raceInfo->id)->WhereEqual('PlayerID', $playerID)->Fetch();
            if(!empty($row) && $row->Status == RaceValue::StatusGiveUp) {
                $result = new ResultData(ErrorCode::Success);
                $result->h = SkillValue::SkillH;
                $result->s = SkillValue::SkillS;
                return $result;
            }
            
            throw new RaceException(RaceException::PlayerNotInThisRace, ['[player]' => $playerID]);
        }
        
        $playerHandler = new PlayerHandler($playerID);
        $racePlayerHandler = $raceHandler->SetPlayer($playerHandler);
        
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $racePlayerInfo = $raceHandler->GetRacePlayerInfo();
        if($racePlayerInfo->status == RaceValue::StatusReach) throw new RaceException(RaceException::PlayerReached);
        
        $values = json_decode(InputHelper::post('values'));
        if($values === null) $values = new stdClass();
        DataGenerator::ValidProperties($values, $this->validValues);
        
        if(isset($values->ranking))unset($values->ranking);            
        
        $values->hp = $hp * RaceValue::DivisorHP;

        $raceHandler->SaveRacePlayer((array)$values);
        $racePlayerPool = RacePlayerPool::Instance();
        $racePlayerPool->Delete($racePlayerInfo->id);

        $raceHandler->SetSecne(new SceneHandler($this->userInfo->scene));
        
        $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
        $raceHandler->SetPlayer($playerHandler);
        
        $result = new ResultData(ErrorCode::Success);
        $result->h = $raceHandler->ValueH();
        $result->s = $raceHandler->ValueS();
        
        return $result;
    }
}
