<?php

namespace Games\Skills\Formula;

use Consts\Sessions;
use Games\Pools\ScenePool;
use Games\Pools\UserPool;
use Games\Races\RaceUtility;
use Games\Scenes\SceneUtility;
/**
 * Description of OperandSun
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandSun extends BaseOperand{
    
    public function Process(): float {
        
        $userInfo = UserPool::Instance()->{$_SESSION[Sessions::UserID]};
        $sceneInfo = ScenePool::Instance()->{$userInfo->scene};
        $climate = SceneUtility::CurrentClimate($sceneInfo->climates);
        
        return RaceUtility::SunValueByPlayer($climate->lighting, $this->factory->player->sun);
    }
}
