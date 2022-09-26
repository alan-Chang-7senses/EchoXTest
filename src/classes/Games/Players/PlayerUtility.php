<?php

namespace Games\Players;

use Games\Consts\AdaptablilityLevel;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Consts\SceneValue;
use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\Holders\PlayerInfoHolder;
use stdClass;
/**
 * Description of PlayerUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerUtility {
    
    public static function PartCodeByDNA(string $dna) : string{
        return substr($dna, NFTDNA::PartStart, NFTDNA::PartLength);
    }
    
    public static function PartCodes(stdClass|PlayerInfoHolder $playerInfo) : PlayerDnaHolder{
        
        $parts = new PlayerDnaHolder();
        
        if($playerInfo->skeletonType == NFTDNA::SkeletonTypePhantaBear){
            
            $parts->head = $playerInfo->dna->head != NFTDNA::EmptyPartPhantaBear ? self::PartCodeByDNA($playerInfo->dna->head) : '';
            $parts->body = $playerInfo->dna->body != NFTDNA::EmptyPartPhantaBear ? self::PartCodeByDNA($playerInfo->dna->body) : '';
            $parts->hand = '';
            $parts->leg = $playerInfo->dna->leg != NFTDNA::EmptyPartPhantaBear ? self::PartCodeByDNA($playerInfo->dna->leg) : '';
            $parts->back = $playerInfo->dna->back != NFTDNA::EmptyPartPhantaBear ? self::PartCodeByDNA($playerInfo->dna->back) : '';
            $parts->hat = $playerInfo->dna->hat != NFTDNA::EmptyPartPhantaBear ? self::PartCodeByDNA($playerInfo->dna->hat) : '';
            
        }else {
            $parts->head = self::PartCodeByDNA($playerInfo->dna->head);
            $parts->body = self::PartCodeByDNA($playerInfo->dna->body);
            $parts->hand = self::PartCodeByDNA($playerInfo->dna->hand);
            $parts->leg = self::PartCodeByDNA($playerInfo->dna->leg);
            $parts->back = self::PartCodeByDNA($playerInfo->dna->back);
            $parts->hat = self::PartCodeByDNA($playerInfo->dna->hat);
        }
            
        return $parts;
    }

    public static function SpeciesCodeByDNA(string $dna) : string{
        return substr($dna, NFTDNA::SpeciesAdaptOffset, NFTDNA::SpeciesLength);
    }

    public static function AdaptValueByPoint(int $point) : int{
        
        return match ($point) {
            AdaptablilityLevel::ParamA => AdaptablilityLevel::ValueA,
            AdaptablilityLevel::ParamB => AdaptablilityLevel::ValueB,
            AdaptablilityLevel::ParamC => AdaptablilityLevel::ValueC,
            AdaptablilityLevel::ParamD => AdaptablilityLevel::ValueD,
            AdaptablilityLevel::ParamE => AdaptablilityLevel::ValueE,
            AdaptablilityLevel::ParamF => AdaptablilityLevel::ValueF,
            default => match (true) {
                $point >= AdaptablilityLevel::ParamS => AdaptablilityLevel::ValueS,
                default => 0,
            },
        };
    }
    
    public static function SunValueByLighting(int $sunAdapt, int $lighting) : float{
        return match ($sunAdapt) {
            SceneValue::SunNone => PlayerValue::SunNone,
            $lighting => PlayerValue::SunSame,
            default => PlayerValue::SunDiff,
        };
    }
    
    public static function GetIDName(int $playerID) : string {
        if($playerID < PlayerValue::BotIDLimit) return 'Robot'.$playerID;
        if(strlen($playerID) == PlayerValue::LengthNFTID) return 'NFT'. intval(substr($playerID, 3));
        return (string)$playerID;
    }
}
