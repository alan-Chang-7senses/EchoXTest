<?php
namespace Processors\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\Accessors\RaceAccessor;
use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\PVP\RaceRoomsHandler;
use Games\Races\Holders\Processors\ReadyRaceInfoHolder;
use Games\Races\OfflineRecoveryDataHandler;
use Games\Races\RaceHandler;
use Games\Races\RaceUtility;
use Games\Races\RaceVerifyHandler;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Games\Users\UserHandler;
use Games\Users\UserUtility;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
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

        if($this->userInfo->room == RaceValue::NotInRoom) throw new RaceException(RaceException::UserNotInRoom);
        
        $config = ConfigGenerator::Instance();

        $users = json_decode(InputHelper::post('users'));
        if($users === null) throw new RaceException(RaceException::IncorrectPlayerNumber);
        
        $userCount = count($users);
        if(!is_array($users) || $userCount > $config->AmountRacePlayerMax || $userCount == 0) throw new RaceException(RaceException::IncorrectPlayerNumber);
        DataGenerator::ExistProperties($users[0], ['id', 'ranking', 'trackNumber', 'rhythm']);

        $trackType = InputHelper::post('trackType');
        $trackShape = InputHelper::post('trackShape');
        $direction = InputHelper::post('direction');

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('Users')->WhereEqual('Room', $this->userInfo->room)->FetchStyleAssoc()->FetchAll();
        $roomUserIDs = array_column($row, 'UserID');
        
        $n = 1;
        $userHandlers = [];
        $readyRaceInfos = [];
        foreach($users as $user){
            
            if(isset($readyRaceInfos[$user->id])) continue;
            if(!UserUtility::IsNonUser($user->id) && !in_array($user->id, $roomUserIDs)) continue;
            
            $handler = new UserHandler($user->id);
            $userInfo = $handler->GetInfo();
            if($userInfo->race != RaceValue::NotInRace && $userInfo->race != RaceValue::BotMatch) throw new RaceException (RaceException::OtherUserInRace, ['[user]' => $user->id]);

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
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $raceID = $raceAccessor->AddRace([
            'SceneID' => $sceneInfo->id,
            'Room' => $this->userInfo->room,
            'CreateTime' => $currentTime,
            'UpdateTime' => $currentTime,
            'Weather' => $climate->weather,
            'WindDirection' => $climate->windDirection,
        ]);

        RaceRoomsHandler::StartRace($this->userInfo->room, $raceID);

        $racePlayerIDs = [];
        $playerSkills = [];
        $ratioEnergy = [];
        $randomEnergy = [];
        foreach($userHandlers as $userHandler){

            $userInfo = $userHandler->GetInfo();
            $readyRaceInfo = $readyRaceInfos[$userInfo->id];

            $playerHandler = new PlayerHandler($userInfo->player);
            $playerInfo = $playerHandler->GetInfo();

            $skills = [];
            $energyCounts = array_fill(0, RaceValue::EnergyTypeCount, 0);
            $energyTotal = 0;
            foreach($playerInfo->skills as $playerSkill){

                if($playerSkill->slot == SkillValue::NotInSlot) continue;

                $handler = new SkillHandler($playerSkill->id);
                $skillInfo = $handler->GetInfo();
                $skills[] = [
                    'id' => $skillInfo->id,
                    'name' => $skillInfo->name,
                    'icon' => $skillInfo->icon,
                    'level' => $playerSkill->level,
                    'slot' => $playerSkill->slot,
                    'energy' => $skillInfo->energy,
                    'cooldown' => $skillInfo->cooldown,
                    'duration' => $skillInfo->duration,
                    'maxCondition' => $skillInfo->maxCondition,
                    'maxConditionValue' => $skillInfo->maxConditionValue,
                    'effects' => $handler->GetEffects(),
                    'maxEffects' => $handler->GetMaxEffects(),
                ];

                for($i = 0; $i < RaceValue::EnergyTypeCount; ++$i){
                    $energyCounts[$i] += $skillInfo->energy[$i];
                    $energyTotal += $skillInfo->energy[$i];
                }
            }

            $ratioEnergy[$userInfo->player] = RaceUtility::RatioEnergy($energyCounts, $energyTotal);
            $randomEnergy[$userInfo->player] = RaceUtility::RandomEnergy($playerInfo->slotNumber);
            $energy = [];
            for($i = 0; $i < RaceValue::EnergyTypeCount; ++$i){
                $energy[] = $ratioEnergy[$userInfo->player][$i] + $randomEnergy[$userInfo->player][$i];
            }

            $racePlayerID = $raceAccessor->AddRacePlayer([
                'RaceID' => $raceID,
                'UserID' => $userInfo->id,
                'PlayerID' => $userInfo->player,
                'RaceNumber' => $readyRaceInfo->raceNumber,
                'Direction' => $direction,
                'Energy' => implode(',', $energy),
                'TrackType' => $trackType,
                'TrackShape' => $trackShape,
                'Rhythm' => $readyRaceInfo->rhythm,
                'Ranking' => $readyRaceInfo->ranking,
                'TrackNumber' => $readyRaceInfo->trackNumber,
                'HP' => $playerInfo->stamina * RaceValue::DivisorHP,
                'CreateTime' => $currentTime,
                'UpdateTime' => $currentTime,
            ]);

            $racePlayerIDs[$userInfo->player] = $racePlayerID;
            $playerSkills[$userInfo->player] = $skills;
            
            $offlineRecoveryDataHandler = new OfflineRecoveryDataHandler();
            $offlineRecoveryDataHandler->SetRecoveryData($raceID,0,0,$userInfo->player,0,
            0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$GLOBALS[Globals::TIME_BEGIN]);  
        }

        $raceHandler = new RaceHandler($raceID);
        $raceHandler->SaveData(['racePlayers' => $racePlayerIDs]);

        $raceHandler->SetSecne($sceneHandler);
        $readyUserInfos = [];
        foreach ($userHandlers as $userHandler) {

            $userInfo = $userHandler->GetInfo();
            $raceHandler->SetPlayer(new PlayerHandler($userInfo->player));
            $racePlayerInfo = $raceHandler->GetRacePlayerInfo();

            $readyUserInfos[] = [
                'id' => $racePlayerInfo->user,
                'player' => $racePlayerInfo->player,
                'energyRatio' => $ratioEnergy[$racePlayerInfo->player],
                'energyRandom' => $randomEnergy[$racePlayerInfo->player],
                'energyTotal' => $racePlayerInfo->energy,
                'hp' => $racePlayerInfo->hp / RaceValue::DivisorHP,
                's' => $raceHandler->ValueS(),
                'h' => $raceHandler->ValueH(),
                'startSec' => $raceHandler->StartSecond(),
                'skills' => $playerSkills[$racePlayerInfo->player],
            ];

            if(!UserUtility::IsNonUser($userInfo->id)) $userHandler->SaveData(['race' => $raceID]);
        }

        $scene = [
            'env' => $sceneInfo->env,
            'weather' => $climate->weather,
            'windDirection' => $climate->windDirection,
            'windSpeed' => $climate->windSpeed,
            'lighting' => $climate->lighting,
        ];
        
        $accessor->executeBind('UPDATE `RaceBeginHours` SET `Amount` = `Amount` + 1, `UpdateTime` = :updateTime WHERE `Hours` = :hour AND `Lobby` = :lobby', [
            'updateTime' => $currentTime,
            'hour' => DataGenerator::TodayHourByTimezone(getenv(EnvVar::TimezoneDefault)),
            'lobby' => $this->userInfo->lobby,
        ]);
        
        $accessor->PrepareName('IncreaseTotalUserRaceBegin');
        foreach($readyUserInfos as $readyUserInfo){
            
            $accessor->executeBind('INSERT INTO `UserRaceAmount` (`UserID`, `Begin`, `UpdateTime`) VALUES (:userID, 1, :updateTime1) ON DUPLICATE KEY UPDATE `Begin` = `Begin` + 1, `UpdateTime` = :updateTime2', [
                'userID' => $readyUserInfo['id'],
                'updateTime1' => $currentTime,
                'updateTime2' => $currentTime,
            ]);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->scene = $scene;
        $result->users = $readyUserInfos;
                
        RaceVerifyHandler::Instance()->Ready($readyUserInfos, $racePlayerIDs);

        return $result;
    }
}
