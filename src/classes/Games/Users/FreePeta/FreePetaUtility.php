<?php
namespace Games\Users\FreePeta;

use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\PlayerUtility;
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

    public static function PartcodeAllDNA(PlayerDnaHolder|stdClass $dna)
    {
        $dna->head = PlayerUtility::PartCodeByDNA($dna->head);
        $dna->body = PlayerUtility::PartCodeByDNA($dna->body);
        $dna->hand = PlayerUtility::PartCodeByDNA($dna->hand);
        $dna->leg = PlayerUtility::PartCodeByDNA($dna->leg);
        $dna->back = PlayerUtility::PartCodeByDNA($dna->back);
        $dna->hat = PlayerUtility::PartCodeByDNA($dna->hat);
    }
}