<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Holders\ResultData;

class PVPMatch extends BasePVPMatch {

    public function Process(): ResultData {
        $raceRoomID = $this->Matching(false);
        $result = new ResultData(ErrorCode::Success);
        $result->raceRoomID = $raceRoomID;
        $result->extraMatchSeconds = $this->competitionsInfo->extraMatchSecond;
        $result->maxMatchSeconds = $this->competitionsInfo->maxMatchSecond;
        $result->minMatchPlayers = $this->competitionsInfo->minMatchPlayers;
        $result->maxBot = $this->competitionsInfo->bot;

        return $result;
    }

}
