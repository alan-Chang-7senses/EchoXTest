<?php

namespace Processors\Races;

use Consts\ErrorCode;
use Games\Consts\RaceValue;
use Helpers\InputHelper;
use Holders\ResultData;
/**
 * Description of StudyRoom
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class StudyRoom extends BaseRace{
    
    public function Process(): ResultData {
        
        $room = InputHelper::post('room');
        
        $this->userHandler->SaveData([
            'lobby' => RaceValue::LobbyStudy,
            'room' => $room,
        ]);
        
        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}
