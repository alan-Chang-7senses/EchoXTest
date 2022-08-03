<?php

namespace Games\Pools;

use stdClass;
use Accessors\PoolAccessor;
use Games\Accessors\RewardAccessor;

class RewardPool extends PoolAccessor
{

    private static RewardPool $instance;
    protected string $keyPrefix = 'RewardItem_';

    public static function Instance(): RewardPool
    {
        if (empty(self::$instance))
            self::$instance = new RewardPool();
        return self::$instance;
    }

    public function FromDB(int|string $rewardID): stdClass|false
    {
        $rewardAccessor = new RewardAccessor();
        $infos = $rewardAccessor->rowInfoByID($rewardID);
        if ($infos == false) {
            return false;
        }

        $result = new stdClass();
        $result->Modes = $infos[0]->Modes;
        $result->Times = $infos[0]->Times;
        $result->TotalProportion = 0;
        $result->Contents = [];

        foreach ($infos as $info) {
            $content = new stdClass();
            $content->ItemID = $info->ItemID;
            $content->Amount = $info->Amount;
            $content->Proportion = $info->Proportion;

            $result->TotalProportion += $info->Proportion;
            $result->Contents[] = $content;
        }
        return $result;
    }
}