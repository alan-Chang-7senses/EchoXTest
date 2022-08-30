<?php

namespace Processors\PVP;

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

        $result = new ResultData(ErrorCode::Success);
        $result->pvpRemainTime = $qualifyingHandler->GetSeasonRemaintime();        
        $result->petaToken = $this->userInfo->petaToken;
        $result->coin = $this->userInfo->coin;
        $result->diamond = $this->userInfo->diamond;
        return $result;
    }


}