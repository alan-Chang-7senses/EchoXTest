<?php

namespace Games\Players;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Consts\NFTDNA;
use Games\Consts\UpgradeValue;
use Games\Pools\ItemInfoPool;
use stdClass;

class UpgradeUtility
{
    public static function GetSkillUpgradeChipID(int $skillID) : string|int
    {
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
        return UpgradeValue::SkillUpgradeSpeciesItem[$speciesCode];  
    }

    public static function GetSkillUpgradeRequireItems(int $skillLevel, int $chipID) : array
    {
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
        return $requireItemIDAmounts;
    }

    public static function GetUpgradeItemInfo($itemID,$holdAmount,$requiredAmount) : stdClass
    {
        $rt = new stdClass();
        $itemInfo = ItemInfoPool::Instance()->{ $itemID};
        $rt->itemID = $itemInfo->ItemID;
        $rt->itemName = $itemInfo->ItemName;
        $rt->description = $itemInfo->Description;
        $rt->itemType = $itemInfo->ItemType;
        $rt->useType = $itemInfo->UseType;
        $rt->stackLimit = $itemInfo->StackLimit;
        $rt->icon = $itemInfo->Icon;
        $rt->source = $itemInfo->Source;
        $rt->holdAmount = $holdAmount;
        $rt->requiredAmount = $requiredAmount;
        return $rt;
    }

}