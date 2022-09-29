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
        if ($qualifyingHandler->NowSeasonID == RaceValue::NOSeasonID) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $ticketsinfo = [];
        $ticketsinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, RaceValue::LobbyCoin);                      
        //todo vip 達標            
        //$ticketsinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, RaceValue::LobbyPT);
        $result = new ResultData(ErrorCode::Success);
        $result->tickets = $ticketsinfo;
        return $result;
    }

}
