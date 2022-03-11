<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Scenes\SceneHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of CurrentScene
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CurrentScene extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $scene = InputHelper::post('scene');
        
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userHandler->SaveData(['scene' => $scene]);
        
        $result = new ResultData(ErrorCode::Success);
        $result->scene = (new SceneHandler($scene))->GetInfo();
        
        return $result;
    }
}
