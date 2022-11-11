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

    private function __construct()
    {
    }
    
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

    // public function EnergyAgain(int $racePlayerID): int {
    //     return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StateEnergyAgain, 0, 0);
    // }

    public function ApplyEnergyBonus(int $racePlayerID, float $newH){
        $this->UpdateHP($racePlayerID,$newH);
    }

    // public function ReachEnd(int $racePlayerID, float $distance): int {
    //     return $this->UpdatePlayer($racePlayerID, RaceVerifyValue::StateReachEnd, 0, $distance);
    // }

    public function LaunchSkill(int $racePlayerIDSelf, float $h)
    {
        $this->UpdateHP($racePlayerIDSelf,$h);
    }

    public function LaunchOthersSkill(array $others, stdClass $racePlayerIDs) 
    {
        // $result = [];
        foreach ($others as $other) {
            $playerID = $other['id']; //$playerID
            $racePlayerID = $racePlayerIDs->{$playerID};
            $result[$playerID] = $this->UpdateHP($racePlayerID, $other['h']);
        }
        // return $result;
    }

    public function PlayerValues(int $racePlayerID, float $newH) : int
    {
        $this->UpdateHP($racePlayerID,$newH);
        $row = AccessorFactory::Main()->FromTable('RacePlayer')->WhereEqual('RacePlayerID',$racePlayerID)
                               ->Fetch();
        return $row->HP;
    }

    private function UpdateHP(int $racePlayerID, float $newH)
    {
        $row = AccessorFactory::Main()
               ->executeBindFetch('SELECT t1.RacePlayerID ,t1.UpdateTime, t1.HValue, t2.HP
               FROM RaceHP t1 JOIN RacePlayer t2 ON t1.RacePlayerID = t2.RacePlayerID
               WHERE t1.RacePlayerID = :RacePlayerID;',['RacePlayerID' => $racePlayerID]);
        if($row === false)return; //角色不在RaceHP表
        if($row->UpdateTime == 0)return;
        $timeSpan = max(0,$GLOBALS[Globals::TIME_BEGIN] - $row->UpdateTime);
        $hpDiff = intval(round($row->HValue * $timeSpan * RaceValue::DivisorHP));
        $this->Update($racePlayerID,$newH,$hpDiff);
        RacePlayerPool::Instance()->Delete($racePlayerID);
    }


    
    private function Update(int $racePlayerID, float $newHValue, int $hpDiff) {

        $accessor = AccessorFactory::Main();
        $bind = ['RacePlayerID' => $racePlayerID ,'HValue' => $newHValue];
        $accessor->Transaction(function () use ($accessor, $racePlayerID, $bind, $hpDiff) {

            $row = $accessor->FromTableJoinUsing('RaceHP','RacePlayer','INNER','RacePlayerID')
                     ->WhereEqual('RacePlayerID', $racePlayerID)->ForUpdate()->Fetch();
            $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];
            $bind['HP'] = max(0,$row->HP - $hpDiff);


            $accessor->executeBind('UPDATE RaceHP t1 INNER JOIN RacePlayer t2 
            ON t1.RacePlayerID = t2.RacePlayerID 
            SET t1.HValue = :HValue, t2.HP = :HP, t1.UpdateTime = :UpdateTime 
            WHERE t1.RacePlayerID = :RacePlayerID',$bind);

            // $accessor->FromTable('RacePlayer')->WhereEqual('RacePlayerID',$racePlayerID)
            //          ->Modify(['HP' => $row->HP - $bind['HP']]);
            // $accessor->ClearCondition()->FromTable('RaceHP')->WhereEqual('RacePlayerID',$racePlayerID)
            // ->Modify(['HValue' => $bind['HValue'],'UpdateTime' => $bind['UpdateTime']]);

        });
    }

}