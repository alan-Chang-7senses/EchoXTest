<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Holders\ResultData;

/**
 * Description of CreateRoom
 */
//class CreateRoom extends BaseRace {

class CreateRoom extends BasePVPMatch {

    public function Process(): ResultData {
        $raceRoomID = $this->Matching(true);
        $result = new ResultData(ErrorCode::Success);
        $result->raceRoomID = $raceRoomID;
        return $result;
    }

}
