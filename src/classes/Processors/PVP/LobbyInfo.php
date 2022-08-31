<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\PVP\QualifyingHandler;
use Holders\ResultData;
use Processors\Races\BaseRace;

class LobbyInfo extends BaseRace
{
    protected bool|null $mustInRace = false;

    public function Process(): ResultData
    {
        $qualifyingHandler = new QualifyingHandler();
        if ($qualifyingHandler->NowSeasonID == RaceValue::NOSeasonID) {
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