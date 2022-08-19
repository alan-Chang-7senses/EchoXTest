<?php

namespace Processors\Leaderboard\CB15;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Processors\Leaderboard\BaseLeaderboard;
/**
 * Description of UserRichest
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserRichest extends BaseLeaderboard{
    
    public function Process(): ResultData {
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->FromTable('Users')->WhereGreater('UserID', 0)
                ->OrderBy('Coin', 'DESC')->Limit($this->length, $this->offset)->FetchAll();
        
        $list = [];
        $ranking = $this->offset + 1;
        foreach($rows as $row){
            
            $list[] = [
                'ranking' => $ranking,
                'nickname' => $row->Nickname,
                'coin' => $row->Coin,
            ];
            
            ++$ranking;
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->list = $list;
        return $result;
    }
}
