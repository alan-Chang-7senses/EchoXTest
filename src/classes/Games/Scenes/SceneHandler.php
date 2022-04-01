<?php

namespace Games\Scenes;

use Games\Pools\ScenePool;
use Games\Scenes\Holders\SceneClimateHolder;
use Games\Scenes\Holders\SceneInfoHolder;
use stdClass;
/**
 * Description of SceneHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SceneHandler {
    
    private SceneInfoHolder|stdClass $info;
    
    public function __construct(int|string $id) {
        $this->info = ScenePool::Instance()->$id;
    }
    
    public function GetInfo() : SceneInfoHolder|stdClass{
        return $this->info;
    }
    
    public function GetClimate() : SceneClimateHolder|stdClass{
        return SceneUtility::CurrentClimate($this->info->climates);
    }
}
