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
            "nickname": "A1",
            "coin": 20565674
        },
        {
            "ranking": 2,
            "nickname": "Kouvo",
            "coin": 18723472
        },
        {
            "ranking": 3,
            "nickname": "BDWill",
            "coin": 17117482
        },
        {
            "ranking": 4,
            "nickname": "Drichhhh",
            "coin": 15819245
        },
        {
            "ranking": 5,
            "nickname": "PetaDee",
            "coin": 15680076
        },
        {
            "ranking": 6,
            "nickname": "Neo",
            "coin": 13726933
        },
        {
            "ranking": 7,
            "nickname": "Vinic77",
            "coin": 10130505
        },
        {
            "ranking": 8,
            "nickname": "Cloudstrike17",
            "coin": 9449908
        },
        {
            "ranking": 9,
            "nickname": "QQ",
            "coin": 8925819
        },
        {
            "ranking": 10,
            "nickname": "pyro21",
            "coin": 7784924
        },
        {
            "ranking": 11,
            "nickname": "TQT",
            "coin": 5915545
        },
        {
            "ranking": 12,
            "nickname": "Tetzu",
            "coin": 5618252
        },
        {
            "ranking": 13,
            "nickname": "Fallen1318",
            "coin": 5346180
        },
        {
            "ranking": 14,
            "nickname": "Asta",
            "coin": 5252229
        },
        {
            "ranking": 15,
            "nickname": "78Oren",
            "coin": 4637726
        },
        {
            "ranking": 16,
            "nickname": "0149",
            "coin": 3932044
        },
        {
            "ranking": 17,
            "nickname": "SaintPerv",
            "coin": 3735219
        },
        {
            "ranking": 18,
            "nickname": "721Hale",
            "coin": 3651343
        },
        {
            "ranking": 19,
            "nickname": "X",
            "coin": 2727448
        },
        {
            "ranking": 20,
            "nickname": "King",
            "coin": 2312513
        }
    ]');
        return $result;
    }
}
