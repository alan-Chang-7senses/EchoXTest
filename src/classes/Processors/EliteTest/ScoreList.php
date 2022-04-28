<?php

namespace Processors\EliteTest;

use Consts\ErrorCode;
use Games\Accessors\EliteTestAccessor;
use Holders\ResultData;
/**
 * Description of ScoreList
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ScoreList extends Leaderboard{
    
    public function Process(): ResultData {
        
        $accessor = new EliteTestAccessor();
        $rows = $accessor->rowsScore($this->offset, $this->length);
        
        $result = new ResultData(ErrorCode::Success);
        $result->list = [];
        
        $ranking = $this->offset + 1;
        foreach($rows as $row){
            
            $result->list[] = [
                'ranking' => $ranking,
                'account' => $row->Username,
                'score' => $row->Score,
            ];
            
            ++$ranking;
        }
        
        return $result;
    }
}
