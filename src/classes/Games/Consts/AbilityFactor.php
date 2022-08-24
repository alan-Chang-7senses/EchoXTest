<?php

namespace Games\Consts;

/**
 * Description of AbilityFactor
 * NFT 數值能力換算因子
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class AbilityFactor {
    
    /** NFT DB 能力值換算除數 */
    const NFTDivisor = 100;
    /** 角色能力值的小數位 */
    const Decimals = 2;
    
    /** NFT 數值換算 速度公式 敏捷倍數 */
    const VelocityAgilityMultiplier = 0.6;
    /** NFT 數值換算 速度公式 力量倍數 */
    const VelocityStrengthMultiplier = 0.2;
    /** NFT 數值換算 速度公式 等級倍數 */
    const VelocityLevelMultiplier = 0.009;
    /** NFT 數值換算 速度公式 等級加值 */
    const VelocityLevelAdditional = 1.4;
    
    /** NFT 數值換算 耐力公式 體力倍數 */
    const StaminaConstitutionMultiplier = 0.5;
    /** NFT 數值換算 耐力公式 技巧倍數 */
    const StaminaDexterityMultiplier = 0.3;
    const StaminaLevelMultiplier = 0.009;
    const StaminaLevelAdditional = 1.4;
    
    /** NFT 數值換算 爆發公式 力量倍數 */
    const BreakOutStrengthMultiplier = 0.5;
    /** NFT 數值換算 爆發公式 技巧倍數 */
    const BreakOutDexterityMultiplier = 0.3;
    const BreakOutLevelMultiplier = 0.009;
    const BreakOutLevelAdditional = 1.4;
    
    /** NFT 數值換算 鬥志公式 體力倍數 */
    const WillConstitutionMultiplier = 0.5;
    /** NFT 數值換算 鬥志公式 力量倍數 */
    const WillStrengthMultiplier = 0.3;
    const WillLevelMultiplier = 0.009;
    const WillLevelAdditional = 1.4;
    
    /** NFT 數值換算 聰慧公式 技巧倍數 */
    const IntelligentDexterityMultiplier = 0.4;
    /** NFT 數值換算 聰慧公式 敏捷倍數 */
    const IntelligentAgilityMultiplier = 0.4;
    const IntelligentLevelMultiplier = 0.009;
    const IntelligentLevelAdditional = 1.4;

    /** NFT 數值換算 X值*/
    const XValues = [0.007,0.0065,0.006,0.0055,0.005,0.0045,0.004,0.0035,0.003,0.0025];

    const Delta = 1.25;

    const LevelDivisor = 5;

    const Velocity = 0;
    const Stamina = 1;
    const BreakOut = 2;
    const Will = 3;
    const Intelligent = 4;
    const AbilityMultiplier = [
            AbilityFactor::Velocity => [0.2,0.6,0,0,0.28,0.84,0,0],
            AbilityFactor::Stamina => [0,0,0.5,0.3,0,0,0.7,0.42],
            AbilityFactor::BreakOut => [0.5,0,0,0.3,0.7,0,0,0.42],
            AbilityFactor::Will => [0.3,0,0.5,0,0.42,0,0.7,0],
            AbilityFactor::Intelligent => [0,0.4,0,0.4,0,0.56,0,0.56]
    ];

    /**同步率相同時的優先順序 */
    const AbilityWeight = 
    [
        'velocity' => 5,
        'stamina' => 4,
        'breakOut' => 3,
        'will' => 2,
        'intelligent' => 1,
    ];

}
