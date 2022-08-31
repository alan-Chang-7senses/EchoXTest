<?php

namespace Games\Players;

use Games\Consts\AbilityFactor;
use Games\Consts\NFTDNA;
use Games\Consts\PlayerValue;
use Games\Consts\SyncRate;
use Games\Players\Adaptability\BaseAdaptability;
use Games\Players\Holders\PlayerDnaHolder;
use Games\Players\Holders\PlayerInfoHolder;
use stdClass;

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

         * ($levelMultiplier * AbilityFactor::XValues[$playerBaseInfo->strengthLevel])
          
         + ($strength * AbilityFactor::AbilityMultiplier[$type][4] + $agility * AbilityFactor::AbilityMultiplier[$type][5]
         + $constitution * AbilityFactor::AbilityMultiplier[$type][6] + $dexterity * AbilityFactor::AbilityMultiplier[$type][7])

         + $levelMultiplier * AbilityFactor::Delta;
         return number_format($rt, AbilityFactor::Decimals);
    }    

    /**算出數值大小排名。數值相同依照企劃說明排序。回傳陣列內容數字依照常數定義 */
    public static function GetAbilityDesc(PlayerInfoHolder|stdClass $info) : array
    {
        $abilities = 
        [
            [AbilityFactor::Velocity => $info->velocity],
            [AbilityFactor::Stamina => $info->stamina],
            [AbilityFactor::BreakOut => $info->breakOut],
            [AbilityFactor::Will => $info->will],
            [AbilityFactor::Intelligent => $info->intelligent],
        ];

        usort($abilities,function($a, $b)
        {
            $aVal = array_values($a)[0];
            $bVal = array_values($b)[0];
            if($aVal < $bVal)return 1;
            if($aVal == $bVal)
            {
                $akey = array_keys($a)[0];
                $bkey = array_keys($b)[0];
                return $akey > $bkey ? 1 : -1;
            }
            return -1;
        });
        $rt = [];
        foreach($abilities as $ability)$rt[] = array_keys($ability)[0];        
        return $rt;
    }

    /**同步率應介於0~1之間 */
    public static function ApplySyncRateBonus(PlayerInfoHolder|stdClass $holder, int|float $syncRate) : void
    {
        $abilityDesc = self::GetAbilityDesc($holder);
        match(true)
        {
            self::IsBetween(AbilityFactor::SyncRateTypeMax[0],AbilityFactor::SyncRateTypeMax[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[0],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeSecond[0],AbilityFactor::SyncRateTypeSecond[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[1],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeThird[0],AbilityFactor::SyncRateTypeThird[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[2],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeFourth[0],AbilityFactor::SyncRateTypeFourth[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[3],$holder,AbilityFactor::SyncRateBonus),

            self::IsBetween(AbilityFactor::SyncRateTypeFifth[0],AbilityFactor::SyncRateTypeFifth[1],$syncRate)
            => self::ModifyPlayerValueByValueID($abilityDesc[4],$holder,AbilityFactor::SyncRateBonus),

            default => null,
        };
    }

    public static function IsBetween(int|float $max, int|float $min, int|float $value)
    {
        return ($value >= $min) && ($value <= $max);
    }

    public static function ModifyPlayerValueByValueID(int $valueID,PlayerInfoHolder|stdClass $holder,int|float $modifyCoefficient)
    {
        match($valueID)
        {
            AbilityFactor::Velocity => $holder->velocity = (float)number_format($modifyCoefficient * $holder->velocity,AbilityFactor::Decimals),
            AbilityFactor::Stamina => $holder->stamina = (float)number_format($modifyCoefficient * $holder->stamina,AbilityFactor::Decimals),
            AbilityFactor::BreakOut => $holder->breakOut = (float)number_format($modifyCoefficient * $holder->breakOut,AbilityFactor::Decimals),
            AbilityFactor::Will => $holder->will = (float)number_format($modifyCoefficient * $holder->will,AbilityFactor::Decimals),
            AbilityFactor::Intelligent => $holder->intelligent = (float)number_format($modifyCoefficient * $holder->intelligent,AbilityFactor::Decimals),
        };
        
    }

    public static function GetAbilityValueByLevel(int $playerID, int $targetLevel,int $strengthLevel) : stdClass
    {
        $info = (new PlayerBaseValueHandler($playerID))->GetInfo();
        $sync = (new PlayerHandler($playerID))->GetInfo()->sync;
        $baseInfo = new PlayerBaseInfoHolder($targetLevel,$strengthLevel,$info->strength,$info->agility,$info->constitution,$info->dexterity);
        $res = new stdClass();
        $res->velocity = self::GetAbilityValue(AbilityFactor::Velocity,$baseInfo);
        $res->stamina = self::GetAbilityValue(AbilityFactor::Stamina,$baseInfo);
        $res->breakOut = self::GetAbilityValue(AbilityFactor::BreakOut,$baseInfo);
        $res->will = self::GetAbilityValue(AbilityFactor::Will,$baseInfo);
        $res->intelligent = self::GetAbilityValue(AbilityFactor::Intelligent,$baseInfo);
        self::ApplySyncRateBonus($res,$sync);
        return $res;
    }

}
