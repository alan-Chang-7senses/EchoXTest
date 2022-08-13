<?php

namespace Games\Players;

use Games\Consts\AbilityFactor;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Players\Adaptability\BaseAdaptability;
use Games\Players\Holders\PlayerDnaHolder;
/**
 * NFT 到遊戲中角色能力值換算
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerAbility {
    
    /**
     * 速度
     * @param int $agility 敏捷
     * @param int $strength 力量
     * @param int $level 等級
     * @return float
     */
    public static function Velocity(int $agility, int $strength, int $level) : float{
        $nftValue = $agility / AbilityFactor::NFTDivisor * AbilityFactor::VelocityAgilityMultiplier + $strength / AbilityFactor::NFTDivisor * AbilityFactor::VelocityStrengthMultiplier;
        $levelValue = $level * AbilityFactor::VelocityLevelMultiplier + AbilityFactor::VelocityLevelAdditional;//在此多乘上X值的樣子。
        return number_format($nftValue * $levelValue, AbilityFactor::Decimals);
    }
    
    /**
     * 耐力
     * @param int $constitution 體力
     * @param int $dexterity 技巧
     * @param int $level 等級
     * @return float
     */
    public static function Stamina(int $constitution, int $dexterity, int $level) : float{
        $nftValue = $constitution / AbilityFactor::NFTDivisor * AbilityFactor::StaminaConstitutionMultiplier + $dexterity / AbilityFactor::NFTDivisor * AbilityFactor::StaminaDexterityMultiplier;
        $levelValue = $level * AbilityFactor::StaminaLevelMultiplier + AbilityFactor::StaminaLevelAdditional;
        return number_format($nftValue * $levelValue, AbilityFactor::Decimals);
    }
    
    /**
     * 爆發
     * @param int $strength 力量
     * @param int $dexterity 技巧
     * @param int $level 等級
     * @return float
     */
    public static function BreakOut(int $strength, int $dexterity, int $level) : float {
        $nftValue = $strength / AbilityFactor::NFTDivisor * AbilityFactor::BreakOutStrengthMultiplier + $dexterity / AbilityFactor::NFTDivisor * AbilityFactor::BreakOutDexterityMultiplier;
        $levelValue = $level * AbilityFactor::BreakOutLevelMultiplier + AbilityFactor::BreakOutLevelAdditional;
        return number_format($nftValue * $levelValue, AbilityFactor::Decimals);
    }
    
    /**
     * 鬥志
     * @param int $contitution 體力
     * @param int $strength 力量
     * @param int $level 等級
     * @return float
     */
    public static function Will(int $contitution, int $strength, int $level) : float {
        $nftValue = $contitution / AbilityFactor::NFTDivisor * AbilityFactor::WillConstitutionMultiplier + $strength / AbilityFactor::NFTDivisor * AbilityFactor::WillStrengthMultiplier;
        $levelValue = $level * AbilityFactor::WillLevelMultiplier + AbilityFactor::WillLevelAdditional;
        return number_format($nftValue * $levelValue, AbilityFactor::Decimals);
    }
    
    /**
     * 聰慧
     * @param int $dexterity 技巧
     * @param int $agility 敏捷
     * @param int $level 等級
     * @return float
     */
    public static function Intelligent(int $dexterity, int $agility, int $level) : float {
        $nftValue = $dexterity / AbilityFactor::NFTDivisor * AbilityFactor::IntelligentDexterityMultiplier + $agility / AbilityFactor::NFTDivisor * AbilityFactor::IntelligentAgilityMultiplier;
        $levelValue = $level * AbilityFactor::IntelligentLevelMultiplier + AbilityFactor::IntelligentLevelAdditional;
        return number_format($nftValue * $levelValue, AbilityFactor::Decimals);
    }
    
    /**
     * 適應性
     * @param PlayerDnaHolder $DNA 各部位的 DNA
     * @param BaseAdaptability $adaptability 適性資料持有物件
     * @param array $mainOffsets 主要偏移量陣列，顯性偏移或隱性偏移位數
     * @param int $adaptOffset 適性偏移位數，屬性或物種
     * @param int $adaptLength 適性偏移長度
     * @return void
     */
    public static function Adaptability(PlayerDnaHolder $DNA, BaseAdaptability $adaptability, array $mainOffsets, int $adaptOffset, int $adaptLength) : void {
        
        $code = '';
        foreach ($DNA as $dna) {
            foreach($mainOffsets as $offset){
                $code = substr(substr($dna, $offset, NFTDNA::MainSplitLength), $adaptOffset, $adaptLength);
                $adaptability->Assign($code);
            }
        }
    }
    
    /**
     * 比賽習慣
     * @param int $constitution 體力
     * @param int $strength 力量
     * @param int $dexterity 技巧
     * @param int $agility 敏捷
     * @return int
     */
    public static function Habit(int $constitution, int $strength, int $dexterity, int $agility) : int {
        
        $habits = [PlayerValue::Rush => $strength, PlayerValue::Stability => $constitution, PlayerValue::Priority => $agility, PlayerValue::Accumulate => $dexterity];
        arsort($habits);
        reset($habits);
        return key($habits);
    }

    /**
     * 計算數值
     * @param int $type 數值的種類
     * @param PlayerBaseInfoHolder 基礎數值的結構類
     */
    public static function GetAbilityValue(int $type,PlayerBaseInfoHolder $playerBaseInfo) : float{
        

        $levelMultiplier = ceil(($playerBaseInfo->level + $type) / AbilityFactor::LevelDivisor);
        $strength = $playerBaseInfo->strength / AbilityFactor::NFTDivisor;
        $agility = $playerBaseInfo->agility /  AbilityFactor::NFTDivisor;
        $constitution = $playerBaseInfo->constitution /  AbilityFactor::NFTDivisor;
        $dexterity = $playerBaseInfo->dexterity / AbilityFactor::NFTDivisor;

        $rt = ($strength * AbilityFactor::AbilityMultiplier[$type][0] + $agility * AbilityFactor::AbilityMultiplier[$type][1]
         + $constitution * AbilityFactor::AbilityMultiplier[$type][2] + $dexterity * AbilityFactor::AbilityMultiplier[$type][3])

         * ($levelMultiplier * AbilityFactor::XValues[$playerBaseInfo->strengthType])
          
         + ($strength * AbilityFactor::AbilityMultiplier[$type][4] + $agility * AbilityFactor::AbilityMultiplier[$type][5]
         + $constitution * AbilityFactor::AbilityMultiplier[$type][6] + $dexterity * AbilityFactor::AbilityMultiplier[$type][7])

         + $levelMultiplier * AbilityFactor::Delta;
         return number_format($rt, AbilityFactor::Decimals);
    }

}
