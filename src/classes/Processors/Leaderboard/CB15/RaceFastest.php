<?php

namespace Processors\Leaderboard\CB15;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Processors\Leaderboard\BaseWebLeaderboard;
/**
 * Description of RaceFastest
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceFastest extends BaseWebLeaderboard{
    
    public function Process(): ResultData {
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->SelectExpr('*, SUBSTRING( FROM_UNIXTIME(`Fastest`, "%T.%f"), 1, 12) AS Duration')->FromTableJoinUsing('UserRaceTiming', 'Users', 'LEFT', 'UserID')
                ->Limit($this->length, $this->offset)->OrderBy('Fastest')->FetchAll();
                //FromTable('UserRaceTiming')->Limit($this->length, $this->offset)->OrderBy('Fastest')->FetchAll();
        
        $list = [];
        $ranking = $this->offset + 1;
        foreach($rows as $row){
            
            $list[] = [
                'ranking' => $ranking,
                'nickname' => $row->Nickname,
                'duration' => $row->Duration,
            ];
            
            ++$ranking;
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->list = $list;
        return $result;
    }
}
