<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\SceneAccessor;
use Games\Scenes\Holders\SceneClimateHolder;
use Games\Scenes\Holders\SceneInfoHolder;
use Games\Scenes\Holders\SceneTrackHolder;
use Generators\DataGenerator;
use stdClass;
/**
 * Description of ScenePool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ScenePool extends PoolAccessor{
    
    private static ScenePool $instance;
    
    public static function Instance() : ScenePool{
        if(empty(self::$instance)) self::$instance = new ScenePool ();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'scene_';

    public function FromDB(int|string $id): stdClass|false {
        
        $sceneAccessor = new SceneAccessor();
        $sceneInfo = $sceneAccessor->rowInfoByID($id);
        
        $infoHolder = new SceneInfoHolder();
        $infoHolder->id = $id;
        $infoHolder->name = $sceneInfo->SceneName;
        $infoHolder->readySec = $sceneInfo->ReadyToStart;
        $infoHolder->env = $sceneInfo->SceneEnv;
        
        $sceneTracks = $sceneAccessor->rowsTrackBySceneID($id);
        $infoHolder->tracks = [];
        foreach($sceneTracks as $track){
            $holder = new SceneTrackHolder();
            $holder->id = $track->SceneTrackID;
            $holder->type = $track->TrackType;
            $holder->step = $track->Step;
            $holder->length = $track->Length;
            $holder->shape = $track->Shape;
            $holder->direction = $track->Direction;
            $infoHolder->tracks[] = $holder;
        }
        
        $sceneClimates = $sceneAccessor->rowsClimateBySceneID($id);
        $infoHolder->climates = [];
        foreach ($sceneClimates as $climate) {
            $holder = new SceneClimateHolder();
            $holder->id = $climate->SceneClimateID;
            $holder->weather = $climate->Weather;
            $holder->windDirection = $climate->WindDirection;
            $holder->windSpeed = $climate->WindSpeed;
            $holder->startTime = $climate->StartTime;
            $holder->lighting = $climate->Lighting;
            $infoHolder->climates[] = $holder;
        }
        
        return DataGenerator::ConventType($infoHolder, 'stdClass');
    }
}
