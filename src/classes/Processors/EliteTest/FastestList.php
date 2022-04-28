<?php

namespace Processors\EliteTest;

use Consts\ErrorCode;
use Games\Accessors\EliteTestAccessor;
use Holders\ResultData;
/**
 * Description of FastestList
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FastestList extends Leaderboard{
    
    public function Process(): ResultData {
        
        $accessor = new EliteTestAccessor();
        $rows = $accessor->rowsFastest($this->offset, $this->length);
        
        $result = new ResultData(ErrorCode::Success);
        $result->list = [];
        
        $ranking = $this->offset + 1;
        foreach($rows as $row){
            
            $result->list[] = [
                'ranking' => $ranking,
                'account' => $row->Username,
                'duration' => $row->Duration,
            ];
            
            ++$ranking;
        }
        
        return $result;
    }
}
