<?php

namespace Games\Scenes;

use Games\Pools\ScenePool;
use Games\Scenes\Holders\SceneClimateHolder;
use Games\Scenes\Holders\SceneInfoHolder;
use Generators\DataGenerator;
use stdClass;
/**
 * Description of SceneHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SceneHandler {
    
    private SceneInfoHolder $info;
    
    public function __construct(int|string $id) {
        $this->info = DataGenerator::ConventType(ScenePool::Instance()->$id, 'Games\Scenes\Holders\SceneInfoHolder');
    }
    
    public function GetInfo() : SceneInfoHolder{
        return $this->info;
    }
    
    public function GetClimate() : SceneClimateHolder|stdClass{
        return SceneUtility::CurrentClimate($this->info->climates);
    }
}
