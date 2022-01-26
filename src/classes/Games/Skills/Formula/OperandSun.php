<?php

namespace Games\Skills\Formula;

use Consts\Sessions;
use Games\Consts\DNASun;
use Games\Consts\SkillFormula;
use Games\DataPools\ScenePool;
use Games\DataPools\UserPool;
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
        
        return match ($climate->lighting) {
            DNASun::Normal => SkillFormula::SunNoneValue,
            $this->factory->player->sun => SkillFormula::SunSameValue,
            default => SkillFormula::SunDiffValue,
        };
    }
}
