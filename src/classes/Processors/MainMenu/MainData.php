<?php

namespace Processors\MainMenu;

use Accessors\PDOAccessor;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Players\Avatar;
use Generators\ConfigGenerator;
use Generators\DataGenerator;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of MainData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class MainData extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $userID = $_SESSION[Sessions::UserID];
        $configs = ConfigGenerator::Instance();
        
        $accessorMain = new PDOAccessor('KoaMain');
        $user = $accessorMain->FromTable('Users')->WhereEqual('UserID', $userID)->Fetch();
        
        $accessorStatic = new PDOAccessor('KoaStatic');
        $sceneInfo = $accessorStatic->FromTable('SceneInfo')->Fetch();
        
        $todaySecond = DataGenerator::TodaySecondByTimezone($configs->TimezoneDefault);
        $sceneClimate = $accessorStatic->ClearCondition()->FromTable('SceneClimate')
                ->WhereEqual('SceneID', $sceneInfo->SceneID)
                ->WhereLess('StartTime', $todaySecond)
                ->OrderBy('StartTime', 'DESC')->Limit(1)->Fetch();
        
        $map = new stdClass();
        $map->windDirection = $sceneClimate->WindDirection;
        $map->windSpeed = $sceneClimate->WindSpeed;
        $map->sceneEnv = $sceneInfo->SceneEnv;
        $map->weather = $sceneClimate->Weather;
        $map->lighting = $sceneClimate->Lighting;
        
        $playerID = filter_input(INPUT_POST, 'characterID');
        $player = Avatar::PlayerPartByID($userID, $playerID);
        
        $result = new ResultData(ErrorCode::Success);
        $result->name = $user->Nickname;
        $result->money = $user->Money;
        $result->energy = $user->Vitality;
        $result->roomMax = (int)$configs->AmountRoomPeopleMax;
        $result->map = $map;
        $result->player = $player;
        
        return $result;
    }
}
