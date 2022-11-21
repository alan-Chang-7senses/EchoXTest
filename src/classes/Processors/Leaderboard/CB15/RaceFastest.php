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
        
//        $accessor = new PDOAccessor(EnvVar::DBMain);
//        $rows = $accessor->SelectExpr('*, SUBSTRING( FROM_UNIXTIME(`Fastest`, "%T.%f"), 1, 12) AS Duration')->FromTableJoinUsing('UserRaceTiming', 'Users', 'LEFT', 'UserID')
//                ->Limit($this->length, $this->offset)->OrderBy('Fastest')->FetchAll();
//                //FromTable('UserRaceTiming')->Limit($this->length, $this->offset)->OrderBy('Fastest')->FetchAll();
//        
//        $list = [];
//        $ranking = $this->offset + 1;
//        foreach($rows as $row){
//            
//            $list[] = [
//                'ranking' => $ranking,
//                'nickname' => $row->Nickname,
//                'duration' => $row->Duration,
//            ];
//            
//            ++$ranking;
//        }
        
        $result = new ResultData(ErrorCode::Success);
//        $result->list = $list;
        $result->list = json_decode('[
        {
            "ranking": 1,
            "nickname": "423Nora",
            "duration": "00:00:56.481"
        },
        {
            "ranking": 2,
            "nickname": "Nora4635",
            "duration": "00:01:27.289"
        },
        {
            "ranking": 3,
            "nickname": "3gu",
            "duration": "00:01:32.605"
        },
        {
            "ranking": 4,
            "nickname": "A3",
            "duration": "00:01:32.833"
        },
        {
            "ranking": 5,
            "nickname": "4318Tracy",
            "duration": "00:01:32.836"
        },
        {
            "ranking": 6,
            "nickname": "pakyouidee",
            "duration": "00:01:34.105"
        },
        {
            "ranking": 7,
            "nickname": "Vinic77",
            "duration": "00:01:34.162"
        },
        {
            "ranking": 8,
            "nickname": "M1",
            "duration": "00:01:34.229"
        },
        {
            "ranking": 9,
            "nickname": "Kally07",
            "duration": "00:01:34.644"
        },
        {
            "ranking": 10,
            "nickname": "Maik",
            "duration": "00:01:36.020"
        },
        {
            "ranking": 11,
            "nickname": "M2",
            "duration": "00:01:36.105"
        },
        {
            "ranking": 12,
            "nickname": "NB",
            "duration": "00:01:37.814"
        },
        {
            "ranking": 13,
            "nickname": "KZ",
            "duration": "00:01:38.378"
        },
        {
            "ranking": 14,
            "nickname": "TSUC",
            "duration": "00:01:38.502"
        },
        {
            "ranking": 15,
            "nickname": "pyro",
            "duration": "00:01:38.681"
        },
        {
            "ranking": 16,
            "nickname": "TOKWAx27",
            "duration": "00:01:38.807"
        },
        {
            "ranking": 17,
            "nickname": "idee",
            "duration": "00:01:39.593"
        },
        {
            "ranking": 18,
            "nickname": "Mikado",
            "duration": "00:01:39.855"
        },
        {
            "ranking": 19,
            "nickname": "Cson",
            "duration": "00:01:40.158"
        },
        {
            "ranking": 20,
            "nickname": "Wayne13",
            "duration": "00:01:40.868"
        }
    ]');
        return $result;
    }
}
