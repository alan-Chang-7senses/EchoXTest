<?php

namespace Games\CommandWorkers;

use Exception;
use Consts\ErrorCode;
use Holders\ResultData;
use Games\PVP\QualifyingHandler;

class ChangeQualifyingSeason extends BaseWorker
{

    public function Process(): array
    {
        try {
            $qualifyingHandler = new QualifyingHandler();
            $lastQualifyingSeasonID = $qualifyingHandler->ChangeSeason($this->SeasonID, $this->StartNow);
            $qualifyingHandler->SendPrizes($lastQualifyingSeasonID);
            return ["success"];            
        }catch(Exception $ex)
        {
            return [new ResultData(ErrorCode::Unknown, $ex->getMessage())];
        }
    }
}