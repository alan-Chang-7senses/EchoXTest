<?php

namespace Processors\Leaderboard;

use Games\Players\PlayerUtility;
use Games\Players\PlayerHandler;
use Games\PVP\QualifyingHandler;
use Games\PVP\CompetitionsInfoHandler;
use Games\Leadboards\LeadboardUtility;

use Consts\ErrorCode;
use Helpers\InputHelper;
use Holders\ResultData;

/**
 * Description of RivalPlayer
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class RivalPlayer extends BaseRivalPlayer {

    public function Process(): ResultData {
        
        $idName = InputHelper::post('player');

        $lobby = InputHelper::post('lobby');

        $playerID = PlayerUtility::GetPlayerIdByIDName($idName);

        $result = new ResultData(ErrorCode::Success);

        $result->parts = $this->PartInfo($playerID);
        $result->player = $this->SkillInfo($playerID);
        $result->ranking = $this->RnakingInfo($playerID, $lobby);
        $result->itemName = (new PlayerHandler($playerID))->GetInfo()->itemName;

        return $result;
    }    

    protected function RnakingInfo(int $playerID, int $lobby): array {

        $playerInfo = (new PlayerHandler($playerID))->GetInfo();

        $qualifyingHandler = new QualifyingHandler();
        $seasonID = $qualifyingHandler->GetSeasonIDByLobby($lobby);
        $recordType = $qualifyingHandler->GetRecordTypeBySeasonID($seasonID);

        $treshold = CompetitionsInfoHandler::Instance($lobby)->GetInfo()->treshold;

        $ranking = LeadboardUtility::GetPlayerRanking($playerID, $playerInfo->userID, $seasonID, $recordType, $treshold);
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