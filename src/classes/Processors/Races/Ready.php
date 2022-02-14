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
use Games\Pools\UserPool;
use Games\Races\RaceUtility;
use Games\Scenes\SceneUtility;
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
        
        $userPool = UserPool::Instance();
        
        $userSelf = $userPool->{$_SESSION[Sessions::UserID]};
        if($userSelf->race != RaceValue::NotInRace) throw new RaceException (RaceException::UserInRace);
        
        $config = ConfigGenerator::Instance();
        
        $users = json_decode(InputHelper::post('users'));
        $trackType = InputHelper::post('trackType');
        $trackShape = InputHelper::post('trackShape');
        
        if(count($users) > $config->AmountRacePlayerMax) throw new RaceException(RaceException::OverPlayerMax);
        
        $userInfos = [];
        $n = 1;
        foreach($users as $user){
            
            $userInfo = $userPool->{$user->id};
            if($userInfo === false) throw new RaceException (RaceException::UserNotExist, ['[user]' => $user->id]);
            else if($userInfo->race != RaceValue::NotInRace) throw new RaceException (RaceException::OtherUserInRace, ['[user]' => $user->id]);
            
            $userInfo->raceNumber = $n;
            $userInfo->ranking = $user->ranking;
            $userInfo->trackNumber = $user->trackNumber;
            $userInfos[] = $userInfo;
            
            ++$n;
        }
        
        $scenseInfo = ScenePool::Instance()->{$userSelf->scene};
        $currentClimate = SceneUtility::CurrentClimate($scenseInfo->climates);
        
        $raceAccessor = new RaceAccessor();
        $currentTime = time();
        $raceID = $raceAccessor->AddRace($scenseInfo->id, $currentTime, $currentClimate->windDirection);

        $playerPool = PlayerPool::Instance();
        $racePlayerPool = RacePlayerPool::Instance();
        $racePlayers = [];
        foreach($userInfos as $userInfo){
            $playerInfo = $playerPool->{$userInfo->player};
            $energy = RaceUtility::RandomEnergy($playerInfo->slotNumber);
            $racePlayerID = $raceAccessor->AddRacePlayer([
                'RaceID' => $raceID,
                'UserID' => $userInfo->id,
                'PlayerID' => $userInfo->player,
                'RaceNumber' => $userInfo->raceNumber,
                'Energy1' => $energy[0],
                'Energy2' => $energy[1],
                'Energy3' => $energy[2],
                'Energy4' => $energy[3],
                'TrackType' => $trackType,
                'TrackShape' => $trackShape,
                'Ranking' => $userInfo->ranking,
                'TrackNumber' => $userInfo->trackNumber,
                'HP' => 0,
                'CreateTime' => $currentTime,
                'UpdateTime' => $currentTime,
            ]);
            $racePlayers[] = $racePlayerPool->$racePlayerID;
            $userPool->Save($userInfo->id, 'race', $raceID);
        }
        
        $raceInfo = RacePool::Instance()->$raceID;
        
        $result = new ResultData(ErrorCode::Success);
        $result->racePlayers = $racePlayers;
        $result->race = $raceInfo;
        return $result;
    }
}
