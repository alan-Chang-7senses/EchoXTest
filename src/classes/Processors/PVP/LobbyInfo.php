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

        $remainTimeArr = [];
        foreach (QualifyingHandler::Lobbies as $lobby) {
            $remainTimeArr[$lobby] = $qualifyingHandler->GetSeasonRemaintime($lobby);
        }
        $result->pvpRemainTime =  empty($remainTimeArr) ? null : $remainTimeArr;
        $result->petaToken = $this->userInfo->petaToken;
        $result->coin = $this->userInfo->coin;
        $result->diamond = $this->userInfo->diamond;
        return $result;
    }

}
