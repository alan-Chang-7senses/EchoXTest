<?php

namespace Processors\User;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Scenes\SceneHandler;
use Games\Scenes\SceneUtility;
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
        
        $sceneHandler = new SceneHandler($scene);
        $sceneInfo = $sceneHandler->GetInfo();
        $climates = SceneUtility::CurrentClimate($sceneInfo->climates);
        
        $result = new ResultData(ErrorCode::Success);
        $result->scene = [
            'id' => $sceneInfo->id,
            'name' => $sceneInfo->name,
            'env' => $sceneInfo->env,
            'weather' => $climates->weather,
            'windDirection' => $climates->windDirection,
            'windSpeed' => $climates->windSpeed,
            'lighting' => $climates->lighting,
        ];
        
        return $result;
    }
}
