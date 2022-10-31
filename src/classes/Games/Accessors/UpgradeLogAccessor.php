<?php

namespace Games\Accessors;

use Consts\Globals;
use Consts\Sessions;

class UpgradeLogAccessor extends BaseAccessor
{
    public function AddUpgradeLevelLog(int $playerID,int $coinCost,?array $bonusType, int $expAdd)
    {
        $this->LogAccessor()->FromTable('UpgradeLevel')->Add(
        [
            'UserID' => $_SESSION[Sessions::UserID],
            'PlayerID' => $playerID,
            'CoinCost' => $coinCost,
            'BonusType' => empty($bonusType) ? 0 : $bonusType[0],
            'ExpAdd' => $expAdd,
            'Time' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
    }
    public function AddUpgradeRankLog(int $playerID,int $coinCost,int $rankAdd)
    {
        $this->LogAccessor()->FromTable('UpgradeRank')->Add(
        [
            'UserID' => $_SESSION[Sessions::UserID],
            'PlayerID' => $playerID,
            'CoinCost' => $coinCost,
            'RankAdd' => $rankAdd,
            'Time' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
    }
    public function AddUpgradeSkill(int $playerID,int $coinCost,int $skillID,int $skillRankAdd)
    {
        $this->LogAccessor()->FromTable('UpgradeSkill')->Add(
        [
            'UserID' => $_SESSION[Sessions::UserID],
            'PlayerID' => $playerID,
            'CoinCost' => $coinCost,
            'SkillID' => $skillID,
            'skillRankAdd' => $skillRankAdd,
            'Time' => $GLOBALS[Globals::TIME_BEGIN],
        ]);
    }
}