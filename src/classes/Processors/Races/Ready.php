<?php
namespace Processors\Races;

use Consts\ErrorCode;
use Games\Accessors\RaceAccessor;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\Holders\Processors\ReadyRaceInfoHolder;
use Games\Races\RaceHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of Ready
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
//class Ready extends BaseProcessor{
class Ready extends BaseRace{
    
    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        
        $config = ConfigGenerator::Instance();
        
        $users = json_decode(InputHelper::post('users'));
        if(count($users) > $config->AmountRacePlayerMax) throw new RaceException(RaceException::OverPlayerMax);
        
        $trackType = InputHelper::post('trackType');
        $trackShape = InputHelper::post('trackShape');
        $direction = InputHelper::post('direction');
        
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
            $readyRaceInfo->rhythm = $user->rhythm;
            
            $userHandlers[] = $handler;
            $readyRaceInfos[$userInfo->id] = $readyRaceInfo;
            
            ++$n;
        }
        
        $sceneHandler = new SceneHandler($this->userInfo->scene);
        $sceneInfo = $sceneHandler->GetInfo();
        $climate = $sceneHandler->GetClimate();
        
        $raceAccessor = new RaceAccessor();
        $currentTime = time();
        $raceID = $raceAccessor->AddRace($sceneInfo->id, $currentTime, $climate->windDirection);
        
        $racePlayerIDs = [];
        $playerSkills = [];
        foreach($userHandlers as $userHandler){
            
            $userInfo = $userHandler->GetInfo();
            $readyRaceInfo = $readyRaceInfos[$userInfo->id];
            
            $playerHandler = new PlayerHandler($userInfo->player);
            $playerInfo = $playerHandler->GetInfo();
            
            $skills = [];
            foreach($playerInfo->skills as $playerSkill){
                
                $handler = new SkillHandler($playerSkill->id);
                $skillInfo = $handler->GetInfo();
                $skills[] = [
                    'id' => $skillInfo->id,
                    'name' => $skillInfo->name,
                    'type' => $skillInfo->type,
                    'level' => $playerSkill->level,
                    'slot' => $playerSkill->slot,
                    'energy' => $skillInfo->energy,
                    'cooldown' => $skillInfo->cooldown,
                    'maxCondition' => $skillInfo->maxCondition,
                    'maxConditionValue' => $skillInfo->maxConditionValue,
                    'effects' => $handler->GetEffects(),
                    'maxEffects' => $handler->GetMaxEffects(),
                ];
            }
            
            $racePlayerID = $raceAccessor->AddRacePlayer([
                'RaceID' => $raceID,
                'UserID' => $userInfo->id,
                'PlayerID' => $userInfo->player,
                'RaceNumber' => $readyRaceInfo->raceNumber,
                'Direction' => $direction,
                'Energy' => implode(',', RaceUtility::RandomEnergy($playerInfo->slotNumber)),
                'TrackType' => $trackType,
                'TrackShape' => $trackShape,
                'Rhythm' => $readyRaceInfo->rhythm,
                'Ranking' => $readyRaceInfo->ranking,
                'TrackNumber' => $readyRaceInfo->trackNumber,
                'HP' => $playerInfo->stamina * pow(10, RaceValue::HPDecimals),
                'CreateTime' => $currentTime,
                'UpdateTime' => $currentTime,
            ]);
            
            $racePlayerIDs[$userInfo->player] = $racePlayerID;
            $playerSkills[$userInfo->player] = $skills;
        }
        
        $raceHandler = new RaceHandler($raceID);
        $raceHandler->SaveRacePlayerIDs($racePlayerIDs);
        
        $raceHandler->SetSecne($sceneHandler);
        $readyUserInfos = [];
        foreach ($userHandlers as $userHandler) {
            
            $raceHandler->SetPlayer(new PlayerHandler($userHandler->GetInfo()->player));
            $racePlayerInfo = $raceHandler->GetRacePlayerInfo();
            
            $readyUserInfos[] = [
                'id' => $racePlayerInfo->user,
                'player' => $racePlayerInfo->player,
                'energy' => $racePlayerInfo->energy,
                'hp' => $racePlayerInfo->hp / pow(10, RaceValue::HPDecimals),
                's' => $raceHandler->ValueS(),
                'h' => $raceHandler->ValueH(),
                'startSec' => $raceHandler->StartSecond(),
                'skills' => $playerSkills[$racePlayerInfo->player],
            ];
            
            $userHandler->SaveData(['race' => $raceID]);
        }
        
        $scene = [
            'readySec' => $sceneInfo->readySec,
            'env' => $sceneInfo->env,
            'weather' => $climate->weather,
            'windDirection' => $climate->windDirection,
            'windSpeed' => $climate->windSpeed,
            'ligthing' => $climate->lighting,
        ];
        
        $result = new ResultData(ErrorCode::Success);
        $result->scene = $scene;
        $result->users = $readyUserInfos;
        return $result;
    }
}
