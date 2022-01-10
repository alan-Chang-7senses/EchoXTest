<?php

namespace Processors\MainMenu;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\SceneAccessor;
use Games\Accessors\UserAccessor;
use Games\Exceptions\PlayerException;
use Games\Utilities\PlayerUtility;
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
        
        $playerID = filter_input(INPUT_POST, 'characterID');
        $playerAccessor = new PlayerAccessor();
        
        $row = $playerID !== null ? $playerAccessor->rowPlayerJoinHolderByUserAndPlayerID($userID, $playerID) : $playerAccessor->rowPlayerJoinHolderByUserID($userID);
        if($row === false) throw new PlayerException(PlayerException::NotFound);

        $player = new stdClass();
        $player->id = $row->PlayerID;
        $player->head = PlayerUtility::PartCodeByDNA($row->HeadDNA);
        $player->body = PlayerUtility::PartCodeByDNA($row->BodyDNA);
        $player->hand = PlayerUtility::PartCodeByDNA($row->HandDNA);
        $player->leg = PlayerUtility::PartCodeByDNA($row->LegDNA);
        $player->back = PlayerUtility::PartCodeByDNA($row->BackDNA);
        $player->hat = PlayerUtility::PartCodeByDNA($row->HatDNA);

        $userAccessor = new UserAccessor();
        $user = $userAccessor->rowUserByID($userID);
        
        $sceneAccessor = new SceneAccessor();
        $sceneInfo = $sceneAccessor->rowInfoByID(1);
        $sceneClimate = $sceneAccessor->rowCurrentClimateBySceneID($sceneInfo->SceneID, DataGenerator::TodaySecondByTimezone($configs->TimezoneDefault));
        
        $map = new stdClass();
        $map->windDirection = $sceneClimate->WindDirection;
        $map->windSpeed = $sceneClimate->WindSpeed;
        $map->sceneEnv = $sceneInfo->SceneEnv;
        $map->weather = $sceneClimate->Weather;
        $map->lighting = $sceneClimate->Lighting;
        
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
