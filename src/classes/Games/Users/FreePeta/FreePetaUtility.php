<?php
namespace Games\Users\FreePeta;

use Games\Consts\FreePlayerValue;
use Games\Consts\PlayerValue;
use Games\FreePlayer\FreePlayerHandler;
use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Players\PlayerUtility;
use Games\Random\RandomUtility;
use Games\Users\UserHandler;
use stdClass;

class FreePetaUtility
{    
    public static function GetRandomElementInArray(array $a)
    {
        return $a[rand(0,count($a) - 1)];
    }

    public static function GetPartSkill(string $partCode,int $partType, $skillPartTable) : string 
    {        
        foreach($skillPartTable as $row)
        {            
            if($row->PartCode == $partCode && $row->PartType == $partType)
            {
                $aliasCode = $row->AliasCode1;
            }
        }
        return empty($aliasCode) ? false : $aliasCode;
    }

    public static function GetUserFreePlayerCount(int $userID) : int
    {
        $userInfo = (new UserHandler($userID))->GetInfo();;
        $count = 0;
        foreach($userInfo->players as $player)if($player < PlayerValue::freePetaMaxPlayerID)$count++; 
        return $count;
    }

    public static function PartcodeAllDNA(PlayerDnaHolder|stdClass $dna)
    {
        $dna->head = PlayerUtility::PartCodeByDNA($dna->head);
        $dna->body = PlayerUtility::PartCodeByDNA($dna->body);
        $dna->hand = PlayerUtility::PartCodeByDNA($dna->hand);
        $dna->leg = PlayerUtility::PartCodeByDNA($dna->leg);
        $dna->back = PlayerUtility::PartCodeByDNA($dna->back);
        $dna->hat = PlayerUtility::PartCodeByDNA($dna->hat);
    }

     /**
     * @param ?int $type 可指定獲取免費角色種類(速度型：1、平衡型：2、持久型：3)，未填入則隨機給予。
     */
    public static function GetFreePlayer(int $userID,?int $type = null) : PlayerInfoHolder|stdClass
    {
        $type = $type ?? RandomUtility::GetRandomObject(FreePlayerValue::FreePlayerTypeSpeed,FreePlayerValue::FreePlayerTypeBalance,FreePlayerValue::FreePlayerTypeLasting);
        $count = self::GetUserFreePlayerCount($userID);
        $id = $userID * PlayerValue::freePetaPlayerIDMultiplier + $count + 1;
        $info = (new FreePlayerHandler($type))->GetInfo();
        $info->id = $id;
        return $info;
    }
}