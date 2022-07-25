<?php

namespace Games\Players;

use Games\Consts\AdaptablilityLevel;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Consts\SceneValue;
use Games\Exceptions\PlayerException;

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
    
    public static function SunValueByLighting(int $sunAdapt, int $lighting) : float{
        return match ($sunAdapt) {
            SceneValue::SunNone => PlayerValue::SunNone,
            $lighting => PlayerValue::SunSame,
            default => PlayerValue::SunDiff,
        };
    }

    
    public static function ValidateName($nickName){
        $pattern = "/幹|操/i";
        $nickNameLength = strlen($nickName);
        if($nickNameLength > 24) throw new PlayerException(PlayerException::NicknameLengthError);        
        if(preg_match($pattern,$nickName,$match)) throw new PlayerException(PlayerException::NicknameInValid,['[nickname]' => $nickName]);
    }
}
