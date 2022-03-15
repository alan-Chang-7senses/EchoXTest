<?php

namespace Processors\EliteTest;

use Consts\ErrorCode;
use Games\Accessors\EliteTestAccessor;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of RaceBegin
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceBegin extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $users = json_decode(InputHelper::post('users'));
        if(!is_array($users) || count($users) > ConfigGenerator::Instance()->AmountRacePlayerMax) throw new RaceException(RaceException::IncorrectPlayerNumber);
        
        $accessor = new EliteTestAccessor();
        $rows = $accessor->rowsUserByUserIDs($users);
        $unreadyUsers = [];
        foreach($rows as $row) {
            if($row->Race != RaceValue::NotInRace) $unreadyUsers[] = $row->UserID;
        }
        
        $readyUsers = [];
        $raceID = 0;
        if(count($unreadyUsers) != count($users)) {
            $readyUsers = array_values(array_diff($users, $unreadyUsers));
            $raceID = $accessor->AddRace();
            $accessor->ModifyUserByUserIDs($readyUsers, ['Race' => $raceID]);
            $accessor->IncreaseTotalRaceBeginHours();
            $accessor->IncreaseTotalUserRaceBeginByUserIDs($readyUsers);
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->raceID = $raceID;
        $result->ready = $readyUsers;
        $result->unready = $unreadyUsers;
        
        return $result;
    }
}
