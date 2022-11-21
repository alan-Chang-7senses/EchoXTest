<?php

namespace Processors\Leaderboard\CB15;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Processors\Leaderboard\BaseWebLeaderboard;
/**
 * Description of UserRichest
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserRichest extends BaseWebLeaderboard{
    
    public function Process(): ResultData {
        
//        $accessor = new PDOAccessor(EnvVar::DBMain);
//        $rows = $accessor->FromTable('Users')->WhereGreater('UserID', 0)
//                ->OrderBy('Coin', 'DESC')->Limit($this->length, $this->offset)->FetchAll();
//        
//        $list = [];
//        $ranking = $this->offset + 1;
//        foreach($rows as $row){
//            
//            $list[] = [
//                'ranking' => $ranking,
//                'nickname' => $row->Nickname,
//                'coin' => $row->Coin,
//            ];
//            
//            ++$ranking;
//        }
        
        $result = new ResultData(ErrorCode::Success);
//        $result->list = $list;
        $result->list = json_decode('[
        {
            "ranking": 1,
            "nickname": "idee",
            "coin": 32445001
        },
        {
            "ranking": 2,
            "nickname": "3gu",
            "coin": 32284856
        },
        {
            "ranking": 3,
            "nickname": "A3",
            "coin": 30912236
        },
        {
            "ranking": 4,
            "nickname": "Kbz",
            "coin": 30072119
        },
        {
            "ranking": 5,
            "nickname": "Kally07",
            "coin": 20611815
        },
        {
            "ranking": 6,
            "nickname": "Vinic77",
            "coin": 17921971
        },
        {
            "ranking": 7,
            "nickname": "Wayne13",
            "coin": 13793889
        },
        {
            "ranking": 8,
            "nickname": "pyro21",
            "coin": 12103315
        },
        {
            "ranking": 9,
            "nickname": "Difkccp",
            "coin": 12012464
        },
        {
            "ranking": 10,
            "nickname": "pakyouidee",
            "coin": 9593982
        },
        {
            "ranking": 11,
            "nickname": "Drichhhh",
            "coin": 9399423
        },
        {
            "ranking": 12,
            "nickname": "Cson",
            "coin": 9009476
        },
        {
            "ranking": 13,
            "nickname": "Kouvo",
            "coin": 8626841
        },
        {
            "ranking": 14,
            "nickname": "PetaRoxanne",
            "coin": 8432278
        },
        {
            "ranking": 15,
            "nickname": "Goraniwr",
            "coin": 7458315
        },
        {
            "ranking": 16,
            "nickname": "TSUC",
            "coin": 6431432
        },
        {
            "ranking": 17,
            "nickname": "1930",
            "coin": 6180422
        },
        {
            "ranking": 18,
            "nickname": "Cloudstrike17",
            "coin": 6027206
        },
        {
            "ranking": 19,
            "nickname": "Inosente",
            "coin": 5288986
        },
        {
            "ranking": 20,
            "nickname": "mumu",
            "coin": 4974503
        }
    ]');
        return $result;
    }
}
