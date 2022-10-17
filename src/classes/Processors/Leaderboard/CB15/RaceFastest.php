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
            "nickname": "Cidadiniz",
            "duration": "00:01:00.169"
        },
        {
            "ranking": 2,
            "nickname": "QQ",
            "duration": "00:01:05.889"
        },
        {
            "ranking": 3,
            "nickname": "A1",
            "duration": "00:01:06.588"
        },
        {
            "ranking": 4,
            "nickname": "PetaDee",
            "duration": "00:01:07.261"
        },
        {
            "ranking": 5,
            "nickname": "TQT",
            "duration": "00:01:07.780"
        },
        {
            "ranking": 6,
            "nickname": "BDWill",
            "duration": "00:01:14.685"
        },
        {
            "ranking": 7,
            "nickname": "zZ",
            "duration": "00:01:18.281"
        },
        {
            "ranking": 8,
            "nickname": "Vinic77",
            "duration": "00:01:19.099"
        },
        {
            "ranking": 9,
            "nickname": "King",
            "duration": "00:01:20.869"
        },
        {
            "ranking": 10,
            "nickname": "Mikado",
            "duration": "00:01:21.935"
        },
        {
            "ranking": 11,
            "nickname": "SaintPerv",
            "duration": "00:01:22.524"
        },
        {
            "ranking": 12,
            "nickname": "F1",
            "duration": "00:01:25.673"
        },
        {
            "ranking": 13,
            "nickname": "0149",
            "duration": "00:01:25.767"
        },
        {
            "ranking": 14,
            "nickname": "IVY",
            "duration": "00:01:27.875"
        },
        {
            "ranking": 15,
            "nickname": "applekts",
            "duration": "00:01:28.804"
        },
        {
            "ranking": 16,
            "nickname": "Asta",
            "duration": "00:01:33.079"
        },
        {
            "ranking": 17,
            "nickname": "0149",
            "duration": "00:01:34.361"
        },
        {
            "ranking": 18,
            "nickname": "Ivvy",
            "duration": "00:01:34.720"
        },
        {
            "ranking": 19,
            "nickname": "Sakura25",
            "duration": "00:01:34.919"
        },
        {
            "ranking": 20,
            "nickname": "pyro21",
            "duration": "00:01:35.180"
        }
    ]');
        return $result;
    }
}
