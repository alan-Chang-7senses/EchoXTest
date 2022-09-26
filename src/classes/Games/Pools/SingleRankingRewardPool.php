<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use stdClass;
/**
 * Description of SingleRankingRewardPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SingleRankingRewardPool extends PoolAccessor {
    
    private static SingleRankingRewardPool $instance;
    
    public static function Instance() : SingleRankingRewardPool {
        if(empty(self::$instance)) self::$instance = new SingleRankingRewardPool ();
        return self::$instance;
    }

    protected string $keyPrefis = 'singleRankingReward_';

    public function FromDB(int|string $id): stdClass|false {
        
        list($playerNUmber, $ranking) = explode('_', $id);
        
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $row = $accessor->FromTable('SingleRankingReward')
                ->WhereEqual('PlayerNumber', $playerNUmber)->WhereEqual('Ranking', $ranking)
                ->Fetch();
        
        $holder = new stdClass();
        $holder->coinReward = $row->CoinReward ?? 0;
        $holder->petaTokenReward = $row->PetaTokenReward ?? 0;
        $holder->coinRewardB = $row->CoinRewardB ?? 0;
        $holder->petaTokenRewardB = $row->PetaTokenRewardB ?? 0;
        
        return $holder;
    }

    public function GetInfo(int $playerNumber, int $ranking) : stdClass{
        
        $key = $playerNumber.'_'.$ranking;
        return $this->$key;
    }
}
