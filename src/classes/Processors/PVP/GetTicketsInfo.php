<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Games\Exceptions\RaceException;
use Games\PVP\QualifyingHandler;
use Holders\ResultData;
use Processors\Races\BaseRace;

class GetTicketsInfo extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $qualifyingHandler = new QualifyingHandler();
        if ($qualifyingHandler->NowSeasonID == -1) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $ticketsinfo = [];
        foreach (QualifyingHandler::MatchLobbies as $lobby) {
            $ticketsinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, $lobby);
        }
        $result = new ResultData(ErrorCode::Success);
        $result->tickets = $ticketsinfo;
        return $result;
    }

}
