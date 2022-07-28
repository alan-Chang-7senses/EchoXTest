<?php

namespace Games\CommandWorkers;

use Exception;
use Consts\Globals;
use Consts\ErrorCode;
use Holders\ResultData;
use Games\PVP\QualifyingHandler;

class ChangeQualifyingSeason extends BaseWorker
{

    public function Process(): array
    {
        try {
            $GLOBALS[Globals::TIME_BEGIN] = microtime(true);
            $qualifyingHandler = new QualifyingHandler();
            $lastQualifyingSeasonID = $qualifyingHandler->ChangeSeason($this->SeasonID, $this->StartNow == null ? true : $this->StartNow);
            $qualifyingHandler->SendPrizes($lastQualifyingSeasonID);
            return ["success"];            
        }catch(Exception $ex)
        {
            return [new ResultData(ErrorCode::Unknown, $ex->getMessage())];
        }
    }
}