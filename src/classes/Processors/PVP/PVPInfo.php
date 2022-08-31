<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Pools\ItemInfoPool;
use Games\PVP\QualifyingHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Games\Scenes\SceneUtility;
use Games\Users\UserBagHandler;
use Holders\ResultData;
use Processors\Races\BaseRace;
use stdClass;

class PVPInfo extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $qualifyingHandler = new QualifyingHandler();
        if ($qualifyingHandler->NowSeasonID == RaceValue::NOSeasonID) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $infos = [];
        $userBagHandler = new UserBagHandler($_SESSION[Sessions::UserID]);
        foreach (QualifyingHandler::MatchLobbies as $lobby) {
            $lobbyinfo = new stdClass;
            $lobbyinfo->lobby = $lobby;
            $ticketID = RaceUtility::GetTicketID($lobby);
            $ticketInfo = ItemInfoPool::Instance()->{$ticketID};
            $lobbyinfo->ticketIcon = $ticketInfo->Icon;
            $lobbyinfo->ticketAmount = $userBagHandler->GetItemAmount($ticketID);
            $lobbyinfo->petaLimitLevel = $qualifyingHandler->GetPetaLimitLevel($lobby);

            $scendID = $qualifyingHandler->GetSceneID($lobby);
            $sceneHandler = new SceneHandler($scendID);
            $sceneInfo = $sceneHandler->GetInfo();
            $climates = SceneUtility::CurrentClimate($sceneInfo->climates);

            $lobbyinfo->rank = $qualifyingHandler->GetRank($lobby);
            $lobbyinfo->scene = [
                'id' => $sceneInfo->id,
                'name' => $sceneInfo->name,
                'env' => $sceneInfo->env,
                'weather' => $climates->weather,
                'windDirection' => $climates->windDirection,
                'windSpeed' => $climates->windSpeed,
                'lighting' => $climates->lighting,
            ];

            $infos[] = $lobbyinfo;
        }

        $result = new ResultData(ErrorCode::Success);
        $result->seasonRemainTime = $qualifyingHandler->GetSeasonRemaintime();
        $result->infos = $infos;
        return $result;
    }

}
