<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\PVP\QualifyingHandler;
use Holders\ResultData;
use Processors\Races\BaseRace;

class GetTicketsInfo extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckAnySeasonIsExist();

        $ticketsinfo = [];
        foreach (QualifyingHandler::MatchLobbies as $lobby) {
            if ($qualifyingHandler->GetSeasonIDByLobby($lobby) != RaceValue::NOSeasonID) {
                $ticketsinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, $lobby);   
            }
        }

        $result = new ResultData(ErrorCode::Success);
        $result->tickets = $ticketsinfo;
        return $result;
    }

}
