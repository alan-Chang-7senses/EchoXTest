<?php

namespace Games\Consts;

/**
 * Description of AdaptablilityLevel
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class AdaptablilityLevel {
    
    const ParamS = 6;
    const ParamA = 5;
    const ParamB = 4;
    const ParamC = 3;
    const ParamD = 2;
    const ParamE = 1;
    const ParamF = 0;
    
    const Enviroment = 0;
    const Climate = 1;
    const Terrian = 2;
    const Wind = 3;

    //舊數值
    const ValueS = 105;
    const ValueA = 104;
    const ValueB = 103;
    const ValueC = 102;
    const ValueD = 101;
    const ValueE = 100;
    const ValueF = 99;

    const AdaptablilityValues = 
    [
        self::Enviroment => 
        [
            self::ParamS => 105,
            self::ParamA => 104,
            self::ParamB => 103,
            self::ParamC => 102,
            self::ParamD => 101,
            self::ParamE => 100,
            self::ParamF => 99,
        ],
        self::Climate => 
        [
            self::ParamS => 107,
            self::ParamA => 105,
            self::ParamB => 103,
            self::ParamC => 101,
            self::ParamD => 99,
            self::ParamE => 97,
            self::ParamF => 95,
        ],
        self::Terrian => 
        [
            self::ParamS => 105,
            self::ParamA => 104,
            self::ParamB => 103,
            self::ParamC => 102,
            self::ParamD => 101,
            self::ParamE => 100,
            self::ParamF => 99,
        ],
        self::Wind => 
        [
            self::ParamS => 105,
            self::ParamA => 104,
            self::ParamB => 103,
            self::ParamC => 102,
            self::ParamD => 101,
            self::ParamE => 100,
            self::ParamF => 99,
        ],
    ];

}
