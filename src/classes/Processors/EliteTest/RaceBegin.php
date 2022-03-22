<?php

namespace Processors\EliteTest;

use Consts\ErrorCode;
use Games\Accessors\EliteTestAccessor;
use Games\Consts\RaceValue;
use Games\EliteTest\EliteTestUtility;
use Games\Exceptions\RaceException;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
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
        $existUserIDs = [];
        foreach($rows as $row) {
            if($row->Race != RaceValue::NotInRace) $unreadyUsers[] = $row->UserID;
            $existUserIDs[] = $row->UserID;
        }
        
        if(count($unreadyUsers) == count($users)) throw new RaceException(RaceException::UserInRace);
        
        $npcUserIDs = array_values(array_diff($users, $existUserIDs));
        $newUsers = [];
        $createTime = time();
        foreach($npcUserIDs as $userID){
            $newUsers[] = [
                'UserID' => $userID,
                'Username' => DataGenerator::GuidV4(),
                'CreateTime' => $createTime
            ];
        }
        if(count($newUsers) > 0) $accessor->AddUsers($newUsers);
        
        $readyUsers = array_values(array_diff($users, $unreadyUsers));
        $raceID = $accessor->AddRace();
        $accessor->ModifyUserByUserIDs($readyUsers, ['Race' => $raceID]);
        $accessor->IncreaseTotalRaceBeginHours();
        $accessor->IncreaseTotalUserRaceBeginByUserIDs($readyUsers);
        
        $result = new ResultData(ErrorCode::Success);
        $result->raceID = $raceID;
        $result->ready = $readyUsers;
        $result->unready = $unreadyUsers;
        
        return $result;
    }
    
    public function __destruct() {
        parent::__destruct();
        EliteTestUtility::EndExpiredRace();
    }
}
