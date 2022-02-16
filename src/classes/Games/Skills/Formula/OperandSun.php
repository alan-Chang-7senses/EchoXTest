<?php

namespace Games\Skills\Formula;

use Consts\Sessions;
use Games\Consts\PlayerValue;
use Games\Consts\SceneValue;
use Games\Scenes\SceneHandler;
use Games\Users\UserHandler;
/**
 * Description of OperandSun
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class OperandSun extends BaseOperand{
    
    public function Process(): float {
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $sceneHandler = new SceneHandler($userHandler->GetInfo()->scene);
        $climate = $sceneHandler->GetClimate();
        
        return match ($this->factory->player->sun) {
            SceneValue::SunNone => PlayerValue::SunNone,
            $climate->lighting => PlayerValue::SunSame,
            default => PlayerValue::SunDiff,
        };
    }
}
