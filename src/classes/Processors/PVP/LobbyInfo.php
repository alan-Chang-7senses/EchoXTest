<?php

namespace Processors\PVP;

use stdClass;
use Consts\ErrorCode;
use Holders\ResultData;
use Processors\Races\BaseRace;
use Games\PVP\QualifyingHandler;
use Games\Exceptions\RaceException;

class LobbyInfo extends BaseRace
{
    protected bool|null $mustInRace = false;

    public function Process(): ResultData
    {
        $qualifyingHandler = new QualifyingHandler();
        if ($qualifyingHandler->NowSeasonID == -1) {
            throw new RaceException(RaceException::NoSeasonData);
        }



        $ticketinfo = [];
        foreach (QualifyingHandler::Lobbies as $lobby) {
            $ticketinfo[] = $qualifyingHandler->GetTicketInfo($this->userInfo->id, $lobby);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->petaToken = $this->userInfo->petaToken;
        $result->coin = $this->userInfo->coin;        
        $result->diamond = $this->userInfo->diamond;
        $result->ticket = $ticketinfo;
        return $result;
    }


}