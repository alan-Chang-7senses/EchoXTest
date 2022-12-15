<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Leadboards\LeadboardUtility;
use Games\Players\PlayerHandler;
use Games\Pools\ItemInfoPool;
use Games\PVP\CompetitionsInfoHandler;
use Games\PVP\QualifyingHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Games\Scenes\SceneUtility;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use Holders\ResultData;
use Processors\Races\BaseRace;
use stdClass;

class PVPInfo extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckAnySeasonIsExist();

        $infos = [];
        $userBagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        foreach (QualifyingHandler::MatchLobbies as $lobby) {
            $lobbyinfo = new stdClass;
            $lobbyinfo->lobby = $lobby;
            $ticketID = RaceUtility::GetTicketID($lobby);
            $ticketInfo = ItemInfoPool::Instance()->{$ticketID};
            $lobbyinfo->ticketIcon = $ticketInfo->Icon;
            $playerLevel = ConfigGenerator::Instance()->{RaceValue::LobbyPlayerLevelConfig[$lobby]};
            $skillLevel = ConfigGenerator::Instance()->{RaceValue::LobbySkillLevelConfig[$lobby]};
            $lobbyinfo->ticketAmount = $userBagHandler->GetItemAmount($ticketID);
            $lobbyinfo->petaLimitLevel = intval($playerLevel);

            $scendID = $qualifyingHandler->GetSceneID($lobby, $this->userInfo->scene);
            $sceneHandler = new SceneHandler($scendID);
            $sceneInfo = $sceneHandler->GetInfo();
            $climates = SceneUtility::CurrentClimate($sceneInfo->climates);

            $competitionsInfoHandler = CompetitionsInfoHandler::Instance($lobby);
            $rankInfo = LeadboardUtility::GetPlayerOwnRateRanking(false,$this->userInfo->player,$qualifyingHandler->GetSeasonIDByLobby($lobby),$competitionsInfoHandler->GetInfo()->treshold);
            $lobbyinfo->rank = new stdClass();
            $lobbyinfo->rank->playCount = $rankInfo->playCount;
            $lobbyinfo->rank->rate = $rankInfo->rate;
            $lobbyinfo->rank->ranking = $rankInfo->rank;

            $lobbyinfo->simplePlayer = new stdClass();

            $specifyInfo = (new PlayerHandler($this->userInfo->player,$playerLevel,$skillLevel))->GetInfo();
            $lobbyinfo->simplePlayer->velocity = $specifyInfo->velocity;
            $lobbyinfo->simplePlayer->stamina = $specifyInfo->stamina;
            $lobbyinfo->simplePlayer->intelligent = $specifyInfo->intelligent;
            $lobbyinfo->simplePlayer->breakOut = $specifyInfo->breakOut;
            $lobbyinfo->simplePlayer->will = $specifyInfo->will;
            $lobbyinfo->simplePlayer->skillLevel = intval($skillLevel);

            $lobbyinfo->scene = [
                'id' => $sceneInfo->id,
                'name' => $sceneInfo->name,
                'env' => $sceneInfo->env,
                'weather' => $climates->weather,
                'windDirection' => $climates->windDirection,
                'windSpeed' => $climates->windSpeed,
                'lighting' => $climates->lighting,
            ];

            $lobbyinfo->seasonRemainTime = $qualifyingHandler->GetSeasonRemaintime($lobby);
            $infos[] = $lobbyinfo;
        }

        $result = new ResultData(ErrorCode::Success);
        $result->infos = $infos;
        return $result;
    }

}
