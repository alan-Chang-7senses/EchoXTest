<?php

namespace Processors\EliteTest;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\EliteTestAccessor;
use Games\EliteTest\EliteTestValues;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of UserInfo
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserInfo extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $userID = $_SESSION[Sessions::UserID] - EliteTestValues::BaseUserID;
        $row = (new EliteTestAccessor())->rowUserByUserID($userID);
        
        $result = new ResultData(ErrorCode::Success);
        $result->info = [
            'id' => $row->UserID,
            'race' => $row->Race,
            'score' => $row->Score,
        ];
        
        return $result;
    }
}
