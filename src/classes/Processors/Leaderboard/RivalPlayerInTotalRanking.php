<?php

namespace Processors\Leaderboard;

use Games\Players\PlayerUtility;
use Games\Players\PlayerHandler;
use Games\PVP\QualifyingHandler;
use Games\Leadboards\LeadboardUtility;
use Games\Consts\RaceValue;

use Consts\ErrorCode;
use Helpers\InputHelper;
use Holders\ResultData;

/**
 * Description of RivalPlayer
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class RivalPlayerInTotalRanking extends BaseRivalPlayer {

    public function Process(): ResultData {
        
        $idName = InputHelper::post('player');
        $playerID = PlayerUtility::GetPlayerIdByIDName($idName);

        $result = new ResultData(ErrorCode::Success);

        $result->parts = $this->PartInfo($playerID);
        $result->player = $this->SkillInfo($playerID);
        $result->ranking = $this->RnakingInfo($playerID);

        return $result;
    }    

    protected function RnakingInfo(int $playerID): array {

        $playerInfo = (new PlayerHandler($playerID))->GetInfo();

        $qualifyingHandler = new QualifyingHandler();
        $seasonIDs = [
            $qualifyingHandler->GetSeasonIDByLobby(RaceValue::LobbyPetaTokenA),
            $qualifyingHandler->GetSeasonIDByLobby(RaceValue::LobbyPetaTokenB),
        ];

        $ranking = LeadboardUtility::GetUserOwnRateRanking([], $playerInfo->userID, $seasonIDs);
        if ($ranking == false)
        {
            return [
                'ranking' => 0,
                'playCount' => 0,
                'leadRate' => 0,
            ];
        }

        return [
            'ranking' => $ranking->rank,
            'playCount' => $ranking->playCount,
            'leadRate' => $ranking->rate,
        ];
    }
}