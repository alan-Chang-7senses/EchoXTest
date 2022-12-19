<?php

namespace Games\Players;

use Games\Accessors\AccessorFactory;
use Games\Consts\AbilityFactor;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Players\Holders\PlayerBaseInfoHolder;
use Games\Pools\ItemInfoPool;
use Games\Pools\SkillUpgradeItemsPool;
use stdClass;

class UpgradeUtility
{
    const SkillPartDNAColumns = 
    [
        PlayerValue::Head => 'HeadDNA',
        PlayerValue::Body => 'BodyDNA',
        PlayerValue::Hand => 'HandDNA',
        PlayerValue::Leg => 'LegDNA',
        PlayerValue::Back => 'BackDNA',
        PlayerValue::Hat => 'HatDNA',
    ];

    public static function GetSkillUpgradeRequire(int $skillLevel,int $skillID,int $playerID) : stdClass
    {
        $speciesCode = 0; //未找到種族碼
        $staticAccessor = AccessorFactory::Static();
        $rows = $staticAccessor->selectExpr('PartCode, PartType')
                       ->FromTableJoinOn('SkillInfo','SkillPart','LEFT','AliasCode','AliasCode1')
                       ->WhereEqual('SkillID',$skillID)
                       ->FetchAll();
        if($rows !== false)
        {
            $dnaRow = AccessorFactory::Main()
                       ->FromTable('PlayerNFT')
                       ->WhereEqual('PlayerID',$playerID)
                       ->Fetch();
            foreach($rows as $row)
            {
                if(!empty($row->PartCode) && !empty($row->PartType))
                {
                    $partColumn = self::SkillPartDNAColumns[$row->PartType];
                    $partCode = PlayerUtility::PartCodeByDNA($dnaRow->$partColumn);
                    if($row->PartCode == $partCode)
                    {
                        $speciesCode = substr($partCode, NFTDNA::PartStart, NFTDNA::SpeciesLength);
                        break;
                    }
                }
            }
        }
        $rt = new stdClass();
        $handler = new UpgradeItemsHandler($skillLevel + 1,$speciesCode,SkillUpgradeItemsPool::Instance());
        $rt->items = $handler->GetUpgradeItems(); 
        $rt->coin = $handler->GetCoinCost();
        return $rt;
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
    
    public static function GetAbilityValueByLevel(int $playerID, int $targetLevel) : stdClass
    {
        $info = (new PlayerBaseValueHandler($playerID))->GetInfo();
        $playerInfo = (new PlayerHandler($playerID))->GetInfo();
        $sync = $playerInfo->sync;
        $strengthLevel = $playerInfo->strengthLevel;
        $baseInfo = new PlayerBaseInfoHolder($targetLevel,$strengthLevel,$info->strength,$info->agility,$info->constitution,$info->dexterity);
        $res = new stdClass();
        $res->velocity = PlayerAbility::GetAbilityValue(AbilityFactor::Velocity,$baseInfo);
        $res->stamina = PlayerAbility::GetAbilityValue(AbilityFactor::Stamina,$baseInfo);
        $res->breakOut = PlayerAbility::GetAbilityValue(AbilityFactor::BreakOut,$baseInfo);
        $res->will = PlayerAbility::GetAbilityValue(AbilityFactor::Will,$baseInfo);
        $res->intelligent = PlayerAbility::GetAbilityValue(AbilityFactor::Intelligent,$baseInfo);
        SyncRateUtility::ApplySyncRateBonus($res,$sync);
        return $res;
    }
    

}