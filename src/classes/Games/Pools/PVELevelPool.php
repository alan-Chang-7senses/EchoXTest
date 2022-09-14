<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\PVEAccessor;
use Games\PVE\Holders\PVELevelInfoHolder;
use stdClass;

class PVELevelPool extends PoolAccessor
{
    private static PVELevelPool $instance;
    
    public static function Instance() : PVELevelPool{
        if(empty(self::$instance)) self::$instance = new PVELevelPool();
        return self::$instance;
    }

    protected string $keyPrefix = 'pveLevel_';

    public function FromDB(int|string $id): PVELevelInfoHolder|stdClass|false
    {
        $holder = new PVELevelInfoHolder();

        $rows = (new PVEAccessor)->rowsInfoByID($id);
        if($rows === false)return false;
        $row = $rows[0];

        $holder->levelID = $row->LevelID;
        $holder->recommendLevel = $row->RecommendLevel;
        $holder->power = $row->Power;
        $holder->levelName = $row->LevelName;
        $holder->sceneID = $row->SceneID;
        $holder->trackAttribute = $row->TrackAttribute;
        $holder->weather = $row->Weather;
        $holder->wind = $row->Wind;
        $holder->windSpeed = $row->WindSpeed;
        $holder->dayNight = $row->DayNight;
        $holder->userTrackNumber = $row->UserTrackNumber;
        $holder->firstRewardID = $row->FirstRewardID;
        $holder->sustainRewardID = $row->SustainRewardID;

        //TODO：處理跑道上的AI
        foreach($rows as $r)
        {
            $holder->aiInfo[$r->AIID] = $r->TrackNumber;
        }

        return $holder;
    }
}