<?php

namespace Processors\Player;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\GameLogAccessor;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\UpgradeLogAccessor;
use Games\Consts\ItemValue;
use Games\Consts\UpgradeValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\PlayerException;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
use Games\Players\UpgradeUtility;
use Games\Pools\PlayerPool;
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
        
        $levelLimit = UpgradeValue::SkillLevelLimit[$playerInfo->rank];
        $skillLevel = $playerhandler->SkillLevel($skillID);
        
        //技能已滿等
        if($skillLevel >= $levelLimit)
        throw new PlayerException(PlayerException::SkillLevelMax,['playerID' => $playerID,'skillID' => $skillID]);

        $chipID = UpgradeUtility::GetSkillUpgradeChipID($skillID);
        $requireItemIDAmounts = UpgradeUtility::GetSkillUpgradeRequireItems($skillLevel,$chipID);

        $charge = UpgradeValue::SkillUpgradeCharge[$skillLevel];
        //檢查金幣是否足夠
        if($userInfo->coin < $charge)
        throw new UserException(UserException::UserCoinNotEnough,['userID' => $userID]);

        //檢查所需物品數量是否足夠
        foreach($requireItemIDAmounts as $itemID => $amount)
        {
            // if($amount <= 0)continue;
            if($userBagHandler->GetItemAmount($itemID) < $amount)
            throw new ItemException(ItemException::ItemNotEnough,['item' => $itemID]);            
        }

        //升級
        (new PlayerAccessor())->ModifySkill($playerID,$skillID,['Level' => $skillLevel + UpgradeValue::SkillRankUnit]);
        PlayerPool::Instance()->Delete($playerID);                        
        //扣錢
        $userHandler->SaveData(['coin' => $userInfo->coin - $charge]);
        //扣道具
        $itemsToDelete = [];
        foreach($requireItemIDAmounts as $itemID => $amount)
        {
            $item = new stdClass();
            $item->ItemID = $itemID;
            $item->Amount = $amount;
            $itemsToDelete[] = $item;
        }
        $userBagHandler->DecItems($itemsToDelete,ItemValue::CauseSkillUpgrade); 

    //    (new GameLogAccessor())->AddUpgradeLog($playerID,$skillID,UpgradeValue::SkillRankUnit,-$charge,null,null,null);
       (new UpgradeLogAccessor())->AddUpgradeSkill($playerID,$charge,$skillID,UpgradeValue::SkillRankUnit);
        
       $results = new ResultData(ErrorCode::Success);
       return $results;
    }
}