<?php

namespace Games\Races;

use DateTime;
use stdClass;
use Consts\Globals;
use Exception;
use Generators\ConfigGenerator;
use Games\Pools\QualifyingSeasonPool;
use Games\Accessors\QualifyingSeasonAccessor;

class QualifyingHandler
{
    private QualifyingSeasonPool $pool;
    private QualifyingSeasonAccessor $pdoAccessor;

    public function __construct()
    {
        $this->pool = QualifyingSeasonPool::Instance();
        $this->pdoAccessor = new QualifyingSeasonAccessor();
    }


    public function ChangeSeason(int $forceNewArenaID = null, bool $startRightNow): int
    {
        $lastQualifyingSeasonID = -1;
        $nowSeason = $this->pool->{ ""};        
        if ($forceNewArenaID == null) {
            //正常排程換季            
            $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];

            if ($nowSeason->QualifyingSeasonID == null) {
                //目前資料表沒有賽季,根據設定時間開啟賽季                   
                $arenaID = 1;
                $newSeason = $this->GetNewSeasonInfo(false);
                $startTime = $newSeason->StartTime;
                $endTime = $newSeason->EndTime;
            }
            else {
                //目前資料表有賽季
                if ($nowtime >= $nowSeason->EndTime) {
                    $lastQualifyingSeasonID = $nowSeason->QualifyingSeasonID;
                    $arenaID = $nowSeason->ArenaID + 1;
                    $newSeason = $this->GetNewSeasonInfo(true);
                    $startTime = $newSeason->StartTime;
                    $endTime = $newSeason->EndTime;
                }
                else {
                    //進行中賽季, 排程時間間距太短
                    $this->pool->Delete("");
                    throw new Exception("進行中賽季未結束");
                }
            }
        }
        else {
            //強制換季
            if ($nowSeason->QualifyingSeasonID != null) {
                $lastQualifyingSeasonID = $nowSeason->QualifyingSeasonID;
            }

            $newSeason = $this->GetNewSeasonInfo($startRightNow);
            $arenaID = $startRightNow;
            $startTime = $newSeason->StartTime;
            $endTime = $newSeason->EndTime;
        }

        $this->pool->Delete("");
        $this->pdoAccessor->AddNewSeason($arenaID, $startTime, $endTime);
        return $lastQualifyingSeasonID;
    }

    public function GetNowSeason()
    {
        return $this->pool->{ ""};
    }

    public function SendPrizes(int $qualifyingSeasonID):bool
    {
        if ($qualifyingSeasonID == -1)
        {
            return false;
        }
        //todo send prize
        return true;
    }

    private function GetNewSeasonInfo(bool $startNow): stdClass
    {
        $startTimeValue = ConfigGenerator::Instance()->{ "PvP_B_SeasonStartTime"};
        $startTime = (new DateTime($startTimeValue))->format('U');
        $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];
        $sessionTime = $this->SeasonDurations();

        $result = new stdclass();
        if ($startTime < $nowtime) //過去時間        
        {
            $passSeasons = floor(($nowtime - $startTime) / $sessionTime);
            if ($startNow == false) {
                $passSeasons++;
            }
            $startTime = $startTime + $passSeasons * $sessionTime;
        }
        else {
            if ($startNow) {
                $diffSeasons = floor(($startTime - $nowtime) / $sessionTime) + 1;
                $startTime = $startTime - $diffSeasons * $sessionTime;
            }
        }

        $result->StartTime = $startTime;
        $result->EndTime = $startTime + $sessionTime;
        return $result;
    }

    private function SeasonDurations(): int
    {
         $weekTimeValue = ConfigGenerator::Instance()->{ "PvP_B_WeeksPerSeacon"};
         return $weekTimeValue * 604800;    
        //return 2 * 60; //test 2分鐘一季
    }


}
