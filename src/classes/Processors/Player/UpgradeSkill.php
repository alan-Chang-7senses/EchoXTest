<?php

namespace Processors\Player;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\GameLogAccessor;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Consts\SkillValue;
use Games\Consts\UpgradeValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\PlayerException;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
use Games\Skills\SkillHandler;
use Games\Users\UserBagHandler;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

class UpgradeSkill extends BaseProcessor{

    public function Process(): ResultData
    {
        $playerID = InputHelper::post('playerID');
        $skillID = InputHelper::post('skillID');
        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        $userBagHandler = new UserBagHandler($userID);
        
        if(!in_array($playerID,$userInfo->players))
        throw new UserException(UserException::NotHoldPlayer,['player' => $playerID]);
        
        $playerhandler = new PlayerHandler($playerID);
        $playerInfo = $playerhandler->GetInfo();
        
        if(!$playerhandler->HasSkill($skillID))
        throw new PlayerException(PlayerException::NoSuchSkill, ['[player]' => $playerInfo->id, '[skillID]' => $skillID]);
        
        $results = new ResultData(ErrorCode::Success);        
        $skillHandler = new SkillHandler($skillID);
        $skillInfo = $skillHandler->GetInfo();

        if($playerhandler->SkillLevel($skillID) >= SkillValue::LevelMax)
        {
            //技能已滿等
        }

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $skillPartRow = $accessor->FromTable('SkillPart')
                ->WhereEqual('AliasCode1',$skillInfo->name)
                ->Fetch();
        if($skillPartRow === false)
        {
            //非部位技能，使用特殊晶片升級。
            return $results;
        }

        $speciesCode = substr($skillPartRow->PartCode, NFTDNA::PartStart, NFTDNA::SpeciesLength);


        
        $skillInfo->name;

        return $results;
    }
}