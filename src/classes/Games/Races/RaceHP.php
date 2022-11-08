<?php

namespace Games\Races;

use Consts\Globals;
use Consts\Sessions;
use Games\Accessors\AccessorFactory;
use Games\Races\Holders\RaceHPHolder;
use stdClass;

class RaceHP
{
    private static RaceHP $instance;
    public int $currentUserID;
    private RaceHPHolder |stdClass $hInfo;

    public static function Instance(): RaceHP {
        if (empty(self::$instance)) {
            self::$instance = new RaceHP();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->currentUserID = $_SESSION[Sessions::UserID];
    }

    private function GetInfo(int $racePlayerID) : stdClass
    {
        return $this->hInfo = $this->hInfo ?? AccessorFactory::Main()
                               ->FromTable('RaceHP')
                               ->WhereEqual('RacePlayerID',$racePlayerID)
                               ->Fetch();
    }

    
    public function Ready(array $readyUserInfos, array $racePlayerIDs) {
        foreach ($readyUserInfos as $readyUserInfo) {
            foreach ($readyUserInfos as $readyUserInfo) {
                $playerID = $readyUserInfo['player'];
                $racePlayerID = $racePlayerIDs[$playerID];
    
                $bind = 
                [
                    'RacePlayerID' => $racePlayerID,
                    'HValue' => $readyUserInfo['hp'],
                ];

                AccessorFactory::Main()->FromTable('RaceHP')->Add($bind);
            }
        }
    }

    public function Start(array $racePlayerIDs) 
    {

        foreach ($racePlayerIDs as $racePlayerID) {
            if ($this->GetInfo($racePlayerID) === false) continue;
            $this->Update($racePlayerID,[]);
        }
    }

    
    private function Update(int|string $racePlayerID, array $bind) {

        $accessor = AccessorFactory::Main();
        $accessor->Transaction(function () use ($accessor, $racePlayerID, $bind) {

            $accessor->FromTable('RaceHP')->WhereEqual('RacePlayerID', $racePlayerID)->ForUpdate()->Fetch();
            $bind['UpdateTime'] = $GLOBALS[Globals::TIME_BEGIN];
            $accessor->ClearCondition()->FromTable('RaceHP')->WhereEqual('RacePlayerID', $racePlayerID)->Modify($bind);
        });
    }

}