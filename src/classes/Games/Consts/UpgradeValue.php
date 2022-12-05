<?php

namespace Games\Consts;

class UpgradeValue
{
    const ModeSavingCoin = 0;
    const ModeNormal = 1;
    const ModeLuxury = 2;
    
    const BonuSuccessID = 0;
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

    const ItemIDBlueBerryRock = 2000;
    const ItemIDChipLion = 2011;
    const ItemIDChipDeer = null; // 暫時廢棄
    const ItemIDChipFox = 2013;
    const ItemIDChipCat = 2014;
    const ItemIDChipTiger = 2015;
    const ItemIDChipDog = 2016;
    const ItemIDChipMokey = 2017;
    const ItemIDChipMultiverse = 2002;
    const ItemIDChipSpecial = 2001;

    const Dust = 0;
    const Crystal = 1;
    const BlueBerryRock = 2;
    const Chip = 3;

    const SkillUpgradeFirst = 1;
    const SkillUpgradeSecond = 2;
    const SkillUpgradeThird = 3;
    const SkillUpgradeForth = 4;

    const SkillMaxRank = 5;

    const SkillUpOther = 0;

    const SkillRankUnit = 1;

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
            'dustAmount' => 250,
            'crystalAmount' => 0,
            'charge' => 2500,
        ],
        self::RankUpSecond => 
        [
            'dustAmount' => 1000,
            'crystalAmount' => 120,
            'charge' => 8500,
        ],
        self::RankUpThird => 
        [
            'dustAmount' => 1500,
            'crystalAmount' => 150,
            'charge' => 5950,
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
        5 => 5,//並不確定有無五階，先寫上。
    ];

    const SkillUpgradeCharge = 
    [
        self::SkillUpgradeFirst => 250,
        self::SkillUpgradeSecond => 2750,
        self::SkillUpgradeThird => 5950,
        self::SkillUpgradeForth => 6250,
    ];


    const SkillUpgradeItemAmount = 
    [
        self::SkillUpgradeFirst => 
        [
            self::BlueBerryRock => 10,
        ],
        self::SkillUpgradeSecond => 
        [
            self::BlueBerryRock => 15,
        ],
        self::SkillUpgradeThird => 
        [
            self::Chip => 10,
        ],
        self::SkillUpgradeForth => 
        [
            self::Chip => 15,
        ],
    ];

    const SkillUpgradeSpeciesItem = 
    [
        SpeciesValue::LionDNA => self::ItemIDChipLion,
        SpeciesValue::DeerDNA => self::ItemIDChipDeer,
        SpeciesValue::FoxDNA => self::ItemIDChipFox,
        SpeciesValue::CatDNA => self::ItemIDChipCat,
        SpeciesValue::TigerDNA => self::ItemIDChipTiger,
        SpeciesValue::DogDNA => self::ItemIDChipDog,
        SpeciesValue::MokeyDNA => self::ItemIDChipMokey,
        self::SkillUpOther => self::ItemIDChipSpecial,
    ];

    

}
