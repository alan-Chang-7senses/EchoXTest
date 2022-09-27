<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
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

        // $rows = (new PVEAccessor)->rowsInfoByID($id);
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $row = $accessor->FromTable('PVELevel')->WhereEqual('LevelID',$id)->Fetch();
        if($row === false)return false;

        $holder->levelID = $row->LevelID;
        $holder->chapterID = $row->ChapterID;
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
        $holder->aiInfo[$row->FirstAI] = $row->FirstAITrackNumber;
        $holder->aiInfo[$row->SecondAI] = $row->SecondAITrackNumber;
        $holder->aiInfo[$row->ThirdAI] = $row->ThirdAITrackNumber;

        return $holder;
    }
}