<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Generators\ConfigGenerator;
use Holders\ResultData;

class PVPMatch extends BasePVPMatch {

    public function Process(): ResultData {
        $raceRoomID = $this->Matching(false);
        $result = new ResultData(ErrorCode::Success);
        $result->raceRoomID = $raceRoomID;
        $result->extraMatchSeconds = ConfigGenerator::Instance()->PvP_ExtraMatchSeconds;
        $result->maxMatchSeconds = ConfigGenerator::Instance()->PvP_MaxMatchSeconds;

        return $result;
    }

}
