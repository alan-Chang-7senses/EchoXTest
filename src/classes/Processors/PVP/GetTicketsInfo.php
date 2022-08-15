<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Holders\ResultData;
use Processors\Races\BaseRace;
use Games\PVP\QualifyingHandler;
use Games\Exceptions\RaceException;

class GetTicketsInfo extends BaseRace
{
    protected bool|null $mustInRace = false;

    public function Process(): ResultData
    {
        $qualifyingHandler = new QualifyingHandler();
        if ($qualifyingHandler->NowSeasonID == -1) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $ticketsinfo = [];
        foreach (QualifyingHandler::Lobbies as $lobby) {
            $ticketsinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, $lobby);
        }
        $result = new ResultData(ErrorCode::Success);
        $result->tickets = $ticketsinfo;
        return $result;
    }
}