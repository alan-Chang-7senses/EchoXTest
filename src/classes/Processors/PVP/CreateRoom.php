<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Holders\ResultData;

/**
 * Description of CreateRoom
 */
//class CreateRoom extends BaseRace {

class CreateRoom extends PVPMatch {

    public function Process(): ResultData {
        $pvpResult = parent::Process();
        if ($pvpResult->error->code === ErrorCode::Success) {
            $result = new ResultData(ErrorCode::Success);
            $result->raceRoomID = $pvpResult->raceRoomID;
            return $result;
        } else {
            return $pvpResult;
        }
    }

}
