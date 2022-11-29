<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Games\PVP\QualifyingHandler;
use Holders\ResultData;
use Processors\Races\BaseRace;

class LobbyInfo extends BaseRace {

    protected bool|null $mustInRace = false;

    public function Process(): ResultData {
        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckSeasonIsExist();

        $result = new ResultData(ErrorCode::Success);
        $result->pvpRemainTime = $qualifyingHandler->GetSeasonRemaintime();
        $result->petaToken = $this->userInfo->petaToken;
        $result->coin = $this->userInfo->coin;
        $result->diamond = $this->userInfo->diamond;
        return $result;
    }

}
