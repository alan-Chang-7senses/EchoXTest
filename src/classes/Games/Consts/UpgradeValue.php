<?php

namespace Games\Consts;

class UpgradeValue
{
    const ModeSavingCoin = 0;
    const ModeNormal = 1;
    const ModeLuxury = 2;
    
    const BonusBigSuccessID = 1;
    const BonusUltimateSuccessId = 2;

    const BigSuccessStart = 5;
    const Divisor = 100;

    const UpgradeItemIDSmall = 1001;
    const UpgradeItemIDMiddle = 1002;
    const UpgradeItemIDLarge = 1003;

    const RankUpFirst = 1;
    const RankUpSecond = 2;
    const RankUpThird = 3;
    const RankUpForth = 4;

    const RankUnit = 1;

    const ItemIDSilverDust = 1111;
    const ItemIDSilverCrystal = 1112;

    const ItemIDSunDust = 1121;
    const ItemIDSunCrystal = 1122;

    const ItemIDStarDust = 1131;
    const ItemIDStarCrystal = 1132;

    const Dust = 0;
    const Crystal = 1;

    const RankUpItem = 
    [
        PlayerAttr::Fire => 
        [
            self::Dust => self::ItemIDSilverDust,
            self::Crystal => self::ItemIDSilverCrystal,
        ],
        PlayerAttr::Water => 
        [
            self::Dust => self::ItemIDSunDust,
            self::Crystal => self::ItemIDSunCrystal
        ],
        PlayerAttr::Wood => 
        [
            self::Dust => self::ItemIDStarDust,
            self::Crystal => self::ItemIDStarCrystal
        ],
    ];

    const RankUpItemAmount = 
    [
        self::RankUpFirst => 
        [
            'dustAmount' => 30,
            'crystalAmount' => 0,
            'charge' => 30000,
        ],
        self::RankUpSecond => 
        [
            'dustAmount' => 50,
            'crystalAmount' => 12,
            'charge' => 218000,
        ],
        self::RankUpThird => 
        [
            'dustAmount' => 90,
            'crystalAmount' => 18,
            'charge' => 342000,
        ],
    ];
    
    const RankUpItemValue = 
    [
        PlayerAttr::Fire => 
        [
            self::Dust => ItemValue::EffectFireUp,
            self::Crystal => ItemValue::EffectFireUpHigh,
        ],
        PlayerAttr::Water => 
        [
            self::Dust => ItemValue::EffectWaterUp,
            self::Crystal => ItemValue::EffectWaterUpHigh,
        ],
        PlayerAttr::Wood => 
        [
            self::Dust => ItemValue::EffectWoodUp,
            self::Crystal => ItemValue::EffectWoodUpHigh,
        ],
    ];

    const SkillLevelLimit = 
    [
        self::RankUpFirst => 2,
        self::RankUpSecond => 3,
        self::RankUpThird => 4,
        self::RankUpForth => 5,
    ];
}
