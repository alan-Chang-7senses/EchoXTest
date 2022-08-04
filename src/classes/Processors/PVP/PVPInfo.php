<?php

namespace Processors\PVP;

use stdClass;
use Consts\ErrorCode;
use Holders\ResultData;
use Games\Scenes\SceneHandler;
use Games\Scenes\SceneUtility;
use Processors\Races\BaseRace;
use Games\PVP\QualifyingHandler;
use Games\Exceptions\RaceException;

class PVPInfo extends BaseRace
{
    protected bool|null $mustInRace = false;

    public function Process(): ResultData
    {
        $qualifyingHandler = new QualifyingHandler();
        if ($qualifyingHandler->NowSeasonID == -1) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $infos = [];
        foreach (QualifyingHandler::Lobbies as $lobby) {
            $lobbyinfo = new stdClass;
            $lobbyinfo->lobby = $lobby;
            $lobbyinfo->petaLimitLevel = $qualifyingHandler->GetPetaLimitLevel($lobby);

//            $ticketInfo = $qualifyingHandler->GetTicketInfo($this->userInfo->id, $lobby);

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