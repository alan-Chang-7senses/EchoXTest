<?php
namespace Processors\Races;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Accessors\RaceAccessor;
use Games\Consts\RaceValue;
use Games\Consts\SkillValue;
use Games\Exceptions\RaceException;
use Games\Players\PlayerHandler;
use Games\Races\Holders\Processors\ReadyRaceInfoHolder;
use Games\Races\RaceHandler;
use Games\PVP\RaceRoomsHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Games\Skills\SkillHandler;
use Games\Users\UserHandler;
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

        $config = ConfigGenerator::Instance();

        $users = json_decode(InputHelper::post('users'));
        $userCount = count($users);
        if(!is_array($users) || $userCount > $config->AmountRacePlayerMax || $userCount == 0) throw new RaceException(RaceException::IncorrectPlayerNumber);
        DataGenerator::ExistProperties($users[0], ['id', 'ranking', 'trackNumber', 'rhythm']);

        $trackType = InputHelper::post('trackType');
        $trackShape = InputHelper::post('trackShape');
        $direction = InputHelper::post('direction');

        $n = 1;
        $userHandlers = [];
        $readyRaceInfos = [];
        foreach($users as $user){

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
        }

        $raceHandler = new RaceHandler($raceID);
        $raceHandler->SaveData(['racePlayers' => $racePlayerIDs]);

        $raceHandler->SetSecne($sceneHandler);
        $readyUserInfos = [];
        foreach ($userHandlers as $userHandler) {

            $raceHandler->SetPlayer(new PlayerHandler($userHandler->GetInfo()->player));
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

            $userHandler->SaveData(['race' => $raceID]);

        }

        $scene = [
            'env' => $sceneInfo->env,
            'weather' => $climate->weather,
            'windDirection' => $climate->windDirection,
            'windSpeed' => $climate->windSpeed,
            'lighting' => $climate->lighting,
        ];

        $result = new ResultData(ErrorCode::Success);
        $result->scene = $scene;
        $result->users = $readyUserInfos;
        return $result;
    }
}
