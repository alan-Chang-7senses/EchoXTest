<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Exceptions\RaceException;
use Games\Leadboards\LeadboardUtility;
use Games\Pools\ItemInfoPool;
use Games\PVP\QualifyingHandler;
use Games\Races\RaceUtility;
use Games\Scenes\SceneHandler;
use Games\Scenes\SceneUtility;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
use Holders\ResultData;
use Processors\Races\BaseRace;
use stdClass;
use Throwable;

/**
 * Description of TutorialInfo
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class TutorialInfo extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckAnySeasonIsExist();

        $scendID = ConfigGenerator::Instance()->TutorialSceneID;

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

            try {
                $sceneHandler = new SceneHandler($scendID);
            }            
            catch (Throwable $ex){    
                throw new RaceException(RaceException::NoRaceSceneInfo, ['[scene]' => $scendID]);
            }

            $sceneInfo = $sceneHandler->GetInfo();
            $climates = SceneUtility::CurrentClimate($sceneInfo->climates);

            $rankInfo = LeadboardUtility::PlayerLeadRanking($lobby, $this->userInfo->player, $qualifyingHandler->GetSeasonIDByLobby($lobby));
            $lobbyinfo->rank = new stdClass();
            $lobbyinfo->rank->playCount = $rankInfo->playCount;
            $lobbyinfo->rank->leadRate = $rankInfo->leadRate;
            $lobbyinfo->rank->ranking = $rankInfo->ranking;

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
