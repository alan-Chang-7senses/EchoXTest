<?php

namespace Processors\Player;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\GameLogAccessor;
use Games\Accessors\PlayerAccessor;
use Games\Consts\ItemValue;
use Games\Consts\NFTDNA;
use Games\Consts\UpgradeValue;
use Games\Exceptions\ItemException;
use Games\Exceptions\PlayerException;
use Games\Exceptions\UserException;
use Games\Players\PlayerHandler;
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
        
        $results = new ResultData(ErrorCode::Success);
        
        $levelLimit = UpgradeValue::SkillLevelLimit[$playerInfo->rank];
        $skillLevel = $playerhandler->SkillLevel($skillID);
        
        //技能已滿等
        if($skillLevel >= $levelLimit)
        throw new PlayerException(PlayerException::SkillLevelMax,['playerID' => $playerID,'skillID' => $skillID]);

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $skillRow = $accessor->FromTable('SkillInfo')
                        ->WhereEqual('SkillID',$skillID)
                        ->Fetch();


        $accessor->ClearCondition();
        $skillPartRow = $accessor->FromTable('SkillPart')
                ->WhereEqual('AliasCode1',$skillRow->AliasCode)
                ->Fetch();
        $speciesCode = $skillPartRow === false ?
            UpgradeValue::SkillUpOther :
            substr($skillPartRow->PartCode, NFTDNA::PartStart, NFTDNA::SpeciesLength);        

        $chipID = UpgradeValue::SkillUpgradeSpeciesItem[$speciesCode];
        $requireItems = UpgradeValue::SkillUpgradeItemAmount[$skillLevel];
        $requireItemIDAmounts = [];
        //將物品編號轉為ItemID
        foreach($requireItems as $itemSerial => $amount)
        {
            match($itemSerial)
            {
                UpgradeValue::BlueBerryRock 
                => $requireItemIDAmounts[UpgradeValue::ItemIDBlueBerryRock] = $amount,
                UpgradeValue::Chip
                => $requireItemIDAmounts[$chipID] = $amount,
            };
        }

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
        (new PlayerAccessor())->ModifySkill($playerID,$skillID,['Level' => $skillLevel + UpgradeValue::SkillLevelUnit]);
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
        $userBagHandler->DecItems($itemsToDelete,ItemValue::EffectSkillLevel); 

       (new GameLogAccessor())->AddUpgradeLog($playerID,$skillID,UpgradeValue::SkillLevelUnit,-$charge,null,null,null);
        
        return $results;
    }
}