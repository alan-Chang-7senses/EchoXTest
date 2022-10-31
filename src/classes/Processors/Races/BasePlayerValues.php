<?php

namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Consts\RaceVerifyValue;
use Games\Consts\SkillValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerPool;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerEffectHandler;
use Games\Races\RaceVerifyHandler;
use Games\Races\Rhythm\RhythmGetter;
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
        $distance = InputHelper::post('distance');
        
        $values = json_decode(InputHelper::post('values'));
        if($values === null) $values = new stdClass();
        // DataGenerator::ValidProperties($values, $this->validValues);
        
        $raceHandler = new RaceHandler($this->userInfo->race);
        $raceInfo = $raceHandler->GetInfo();
        if($raceInfo->status == RaceValue::StatusFinish) throw new RaceException(RaceException::Finished);
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $playerID = $this->GetPlayerID();
        if(!isset($raceInfo->racePlayers->{$playerID})){
            
            $row = $accessor->FromTable('RacePlayer')->WhereEqual('RaceID', $raceInfo->id)->WhereEqual('PlayerID', $playerID)->Fetch();
            if(!empty($row) && $row->Status == RaceValue::StatusGiveUp) {
                $result = new ResultData(ErrorCode::Success);
                $result->h = SkillValue::SkillH;
                $result->s = SkillValue::SkillS;
                return $result;
            }
            
            throw new RaceException(RaceException::PlayerNotInThisRace, ['[player]' => $playerID]);
        }
        
        if(isset($values->ranking))unset($values->ranking);
        $values->hp = $hp * RaceValue::DivisorHP;
        $racePlayerID = $raceInfo->racePlayers->{$playerID};
        $values->rhythm = (new RhythmGetter((new PlayerHandler($playerID))->GetInfo()->habit,$racePlayerID))->GetRhythm();

        $accessor->Transaction(function() use ($accessor, $racePlayerID, $values){
            
            $row = $accessor->ClearCondition()
                    ->FromTable('RacePlayer')->WhereEqual('RacePlayerID', $racePlayerID)->ForUpdate()->Fetch();
            if($row->Status == RaceValue::StatusReach) throw new RaceException (RaceException::PlayerReached);
            
            $values->Status = RaceValue::StatusUpdate;
            $values->UpdateTime = $GLOBALS[Globals::TIME_BEGIN];
            $accessor->Modify((array)$values);

        });
        

        RacePlayerPool::Instance()->Delete($racePlayerID);
        
        $playerHandler = new PlayerHandler($playerID);
        $racePlayerHandler = $raceHandler->SetPlayer($playerHandler);        
        
        $raceHandler->SetSecne(new SceneHandler($this->userInfo->scene));
        
        $playerHandler = RacePlayerEffectHandler::EffectPlayer($playerHandler, $racePlayerHandler);
        $raceHandler->SetPlayer($playerHandler);
        
        $result = new ResultData(ErrorCode::Success);
        $result->h = $raceHandler->ValueH();
        $result->s = $raceHandler->ValueS();
        $result->energy = $racePlayerHandler->GetInfo()->energy;
        
        
        $result->distance = RaceVerifyHandler::Instance()->PlayerValues($raceInfo->racePlayers->$playerID, $result->s, $distance);        
        //        if (RaceVerifyHandler::Instance()->PlayerValues($raceInfo->racePlayers->$playerID, $result->s, $distance) == RaceVerifyValue::VerifyCheat) {
            //            if ($this->userInfo->player === $playerID)//API:PlayerValues;  HostPlayerValue:不處理
            //            {
                //                throw new RaceException(RaceException::UserCheat);
                //            }
                //        }
                
        $result->rhythm = $racePlayerHandler->GetInfo()->rhythm;
        return $result;
    }
}
