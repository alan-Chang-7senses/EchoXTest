<?php

namespace Processors\MainMenu;

use Accessors\PDOAccessor;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\NFTDNA;
use Games\Exceptions\CharacterException;
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
        
        $characterID = filter_input(INPUT_POST, 'characterID');
        $accessorMain->ClearCondition();
        if(!empty($characterID)) $accessorMain->WhereEqual('CharacterID', $characterID);
        $character = $accessorMain->FromTableJoinUsing('CharacterNFT', 'CharacterHolder', 'INNER', 'CharacterID')
                ->WhereEqual('UserID', $userID)->Limit(1)->Fetch();
        
        if($character === false) throw new CharacterException(CharacterException::NotFound);

        $player = new stdClass();
        $player->id = $character->CharacterID;
        $player->head = substr($character->HeadDNA, NFTDNA::PartStart, NFTDNA::PartLength);
        $player->body = substr($character->BodyDNA, NFTDNA::PartStart, NFTDNA::PartLength);
        $player->hand = substr($character->HandDNA, NFTDNA::PartStart, NFTDNA::PartLength);
        $player->leg = substr($character->LegDNA, NFTDNA::PartStart, NFTDNA::PartLength);
        $player->back = substr($character->BackDNA, NFTDNA::PartStart, NFTDNA::PartLength);
        $player->hat = substr($character->HatDNA, NFTDNA::PartStart, NFTDNA::PartLength);
        
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
