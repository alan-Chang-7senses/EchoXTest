<?php

namespace Games\Races;

use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Consts\RaceValue;
use Games\Pools\RacePlayerPool;
use stdClass;

class RaceHP
{
    private static RaceHP $instance;

    public static function Instance(): RaceHP {
        if (empty(self::$instance)) {
            self::$instance = new RaceHP();
        }
        return self::$instance;
    }

    private function __construct(){}
    
    public function Ready(array $readyUserInfos, array $racePlayerIDs) 
    {
        foreach ($readyUserInfos as $readyUserInfo) {
            $playerID = $readyUserInfo['player'];
            $racePlayerID = $racePlayerIDs[$playerID];

            $bind = 
            [
                'RacePlayerID' => $racePlayerID,
                'HValue' => $readyUserInfo['h'],
            ];
            AccessorFactory::Main()->FromTable('RaceHP')->Add($bind);
        }
    
    }

    public function Start(array $racePlayerIDs) 
    {
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        $accessor = AccessorFactory::Main();
        $accessor->FromTable('RaceHP')
                ->WhereIn('RacePlayerID',$racePlayerIDs)
                ->Modify(['UpdateTime' => $currentTime]);
    }

    public function UpdateHP(int $racePlayerID, float $newH) : int
    {
    
        $accessor = AccessorFactory::Main();
        $accessor->Transaction(function () use ($accessor, $racePlayerID,$newH) {
            $row = $accessor->FromTableJoinUsing('RaceHP', 'RacePlayer','INNER', 'RacePlayerID')
                     ->SelectExpr('RaceHP.UpdateTime, HValue, HP')
                     ->WhereEqual('RacePlayerID', $racePlayerID)->ForUpdate()->Fetch();
            if($row === false)return; //角色不在RaceHP表
            if($row->UpdateTime == 0)return;
            $timeSpan = max(0,$GLOBALS[Globals::TIME_BEGIN] - $row->UpdateTime);
            $hpDiff = intval(round($row->HValue * $timeSpan * RaceValue::DivisorHP));

            $bind = 
            [
                'RacePlayerID' => $racePlayerID,
                'HValue' => $newH,
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                'HP' => max(0,$row->HP - $hpDiff),
            ];


            $accessor->executeBind('UPDATE RaceHP t1 INNER JOIN RacePlayer t2 
            ON t1.RacePlayerID = t2.RacePlayerID 
            SET t1.HValue = :HValue, t2.HP = :HP, t1.UpdateTime = :UpdateTime 
            WHERE t1.RacePlayerID = :RacePlayerID',$bind);

        });
        
        $row = $accessor->ClearCondition()->FromTable('RacePlayer')
                    ->SelectExpr('HP')->WhereEqual('RacePlayerID',$racePlayerID)
                    ->Fetch();
        RacePlayerPool::Instance()->Delete($racePlayerID);
        return $row->HP; 
    }


}