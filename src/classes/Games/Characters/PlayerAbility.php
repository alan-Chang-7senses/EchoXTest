<?php

namespace Games\Characters;

use Games\Consts\AbilityFactor;
use Games\Consts\NFTDNA;
use Games\Holders\BaseAdaptability;
/**
 * Description of AbilityFormula
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerAbility {
    
    public static function Velocity(int $agility, int $strength, int $level) : float{
        $nftValue = $agility / AbilityFactor::NFTDivisor * AbilityFactor::VelocityAgilityMultiplier + $strength / AbilityFactor::NFTDivisor * AbilityFactor::VelocityStrengthMultiplier;
        $levelValue = $level * AbilityFactor::VelocityLevelMultiplier + AbilityFactor::VelocityLevelAdditional;
        return number_format($nftValue + $levelValue, AbilityFactor::Decimals);
    }
    
    public static function Stamina(int $constitution, int $dexterity, int $level) : float{
        $nftValue = $constitution / AbilityFactor::NFTDivisor * AbilityFactor::StaminaConstitutionMultiplier + $dexterity / AbilityFactor::NFTDivisor * AbilityFactor::StaminaDexterityMultiplier;
        $levelValue = $level * AbilityFactor::StaminaLevelMultiplier + AbilityFactor::StaminaLevelAdditional;
        return number_format($nftValue + $levelValue, AbilityFactor::Decimals);
    }
    
    public static function BreakOut(int $strength, int $dexterity, int $level) : float {
        $nftValue = $strength / AbilityFactor::NFTDivisor * AbilityFactor::BreakOutStrengthMultiplier + $dexterity / AbilityFactor::NFTDivisor * AbilityFactor::BreakOutDexterityMultiplier;
        $levelValue = $level * AbilityFactor::BreakOutLevelMultiplier + AbilityFactor::BreakOutLevelAdditional;
        return number_format($nftValue + $levelValue, AbilityFactor::Decimals);
    }
    
    public static function Will(int $contitution, int $strength, int $level) : float {
        $nftValue = $contitution / AbilityFactor::NFTDivisor * AbilityFactor::WillConstitutionMultiplier + $strength / AbilityFactor::NFTDivisor * AbilityFactor::WillStrengthMultiplier;
        $levelValue = $level * AbilityFactor::WillLevelMultiplier + AbilityFactor::WillLevelAdditional;
        return number_format($nftValue + $levelValue, AbilityFactor::Decimals);
    }
    
    public static function Intelligent(int $dexterity, int $agility, int $level) : float {
        $nftValue = $dexterity / AbilityFactor::NFTDivisor * AbilityFactor::IntelligentDexterityMultiplier + $agility / AbilityFactor::NFTDivisor * AbilityFactor::IntelligentAgilityMultiplier;
        $levelValue = $level * AbilityFactor::IntelligentLevelMultiplier + AbilityFactor::IntelligentLevelAdditional;
        return number_format($nftValue + $levelValue, AbilityFactor::Decimals);
    }
    
    public static function Adaptability(array $DNA, BaseAdaptability $adaptability, array $mainOffsets, int $adaptOffset, int $adaptLength) : void {
        
        $code = '';
        foreach ($DNA as $dna) {
            foreach($mainOffsets as $offset){
                $code = substr(substr($dna, $offset, NFTDNA::MainSplitLength), $adaptOffset, $adaptLength);
                $adaptability->Assign($code);
            }
        }
    }
}
