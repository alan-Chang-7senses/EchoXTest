<?php

namespace Games\Players;

use Games\Consts\AdaptablilityLevel;
use Games\Consts\NFTDNA;
/**
 * Description of PlayerUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerUtility {
    
    public static function PartCodeByDNA(string $dna) : string{
        return substr($dna, NFTDNA::PartStart, NFTDNA::PartLength);
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
}
