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
        $ticketsinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, RaceValue::LobbyCoinA);                      
        //todo vip 達標            
        //$ticketsinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, RaceValue::LobbyPetaTokenA);
        $result = new ResultData(ErrorCode::Success);
        $result->tickets = $ticketsinfo;
        return $result;
    }

}
