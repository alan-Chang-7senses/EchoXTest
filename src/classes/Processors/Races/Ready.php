<?php
namespace Processors\Races;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\RaceAccessor;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\PlayerPool;
use Games\Pools\RacePlayerPool;
use Games\Pools\RacePool;
use Games\Pools\ScenePool;
use Games\Races\Holders\Processors\ReadyRaceInfoHolder;
use Games\Races\RaceUtility;
use Games\Scenes\SceneUtility;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Ready
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Ready extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $userSelf = (new UserHandler($_SESSION[Sessions::UserID]))->GetInfo();
        if($userSelf->race != RaceValue::NotInRace) throw new RaceException (RaceException::UserInRace);
        
        $config = ConfigGenerator::Instance();
        
        $users = json_decode(InputHelper::post('users'));
        $trackType = InputHelper::post('trackType');
        $trackShape = InputHelper::post('trackShape');
        $direction = InputHelper::post('direction');
        
        if(count($users) > $config->AmountRacePlayerMax) throw new RaceException(RaceException::OverPlayerMax);
        
        $n = 1;
        $userHandlers = [];
        $readyRaceInfos = [];
        foreach($users as $user){
            
            $handler = new UserHandler($user->id);
            $userInfo = $handler->GetInfo();
            if($userInfo->race != RaceValue::NotInRace) throw new RaceException (RaceException::OtherUserInRace, ['[user]' => $user->id]);
            
            $readyRaceInfo = new ReadyRaceInfoHolder();
            $readyRaceInfo->raceNumber = $n;
            $readyRaceInfo->ranking = $user->ranking;
            $readyRaceInfo->trackNumber = $user->trackNumber;
            
            $userHandlers[] = $handler;
            $readyRaceInfos[$userInfo->id] = $readyRaceInfo;
            
            ++$n;
        }
        
        $scenseInfo = ScenePool::Instance()->{$userSelf->scene};
        $currentClimate = SceneUtility::CurrentClimate($scenseInfo->climates);
        
        $raceAccessor = new RaceAccessor();
        $currentTime = time();
        $raceID = $raceAccessor->AddRace($scenseInfo->id, $currentTime, $currentClimate->windDirection);

        $slope = RaceUtility::SlopeValue($trackType);
        $climateAcceleration = RaceUtility::ClimateAccelerationValue($currentClimate->weather);
        $climateLose = RaceUtility::ClimateLoseValue($currentClimate->weather);
        $windEffect = RaceUtility::WindEffectValue($currentClimate->weather, $direction, $currentClimate->windSpeed);
        
        $playerPool = PlayerPool::Instance();
        $racePlayerPool = RacePlayerPool::Instance();
        $racePlayers = [];
        $suns = [];
        foreach($userHandlers as $userHandler){
            
            $userInfo = $userHandler->GetInfo();
            $readyRaceInfo = $readyRaceInfos[$userInfo->id];
            
            $playerInfo = $playerPool->{$userInfo->player};
            $energy = RaceUtility::RandomEnergy($playerInfo->slotNumber);
            
            $sun = RaceUtility::SunValueByPlayer($currentClimate->lighting, $playerInfo->sun);
            $suns[] = $sun;
            
            $racePlayerID = $raceAccessor->AddRacePlayer([
                'RaceID' => $raceID,
                'UserID' => $userInfo->id,
                'PlayerID' => $userInfo->player,
                'RaceNumber' => $readyRaceInfo->raceNumber,
                'Direction' => $direction,
                'Energy1' => $energy[0],
                'Energy2' => $energy[1],
                'Energy3' => $energy[2],
                'Energy4' => $energy[3],
                'TrackType' => $trackType,
                'TrackShape' => $trackShape,
                'Ranking' => $readyRaceInfo->ranking,
                'TrackNumber' => $readyRaceInfo->trackNumber,
                'HP' => 0,
                'CreateTime' => $currentTime,
                'UpdateTime' => $currentTime,
            ]);
            $racePlayers[] = $racePlayerPool->$racePlayerID;
            $userHandler->SaveRace($raceID);
        }
        
        $raceInfo = RacePool::Instance()->$raceID;
        
        $result = new ResultData(ErrorCode::Success);
        $result->racePlayers = $racePlayers;
        $result->race = $raceInfo;
        $result->chk = [
            'slope' => $slope,
            'climateAcceleration' => $climateAcceleration,
            'climateLose' => $climateLose,
            'windEffect' => $windEffect,
            'sun' => $suns
        ];
        return $result;
    }
}
