<?php

namespace Games\Consts;

class ActionPointValue
{
    const APRankLessThanOne = 1;
    const APRankTwo = 2;
    const APRankThree = 3;
    const APRankFour = 4;
    const APRankFive = 5;
    const APRankSix = 6;
    const APRankSeven = 7;
    const APRankEight = 8;
    const APRankNine = 9;
    const APRankMoreThanTen = 10;

    const ActionPointDivisor = 1000;

    const APLimit = 'APLimit';
    const APIncreaseRate = 'APIncreaseRate';

    const APRecoverInfo = 
    [
        self::APRankLessThanOne =>
        [
            self::APLimit => 80,
            self::APIncreaseRate => 360,
        ],
        self::APRankTwo =>
        [
            self::APLimit => 120,
            self::APIncreaseRate => 276,
        ],
        self::APRankThree =>
        [
            self::APLimit => 150,
            self::APIncreaseRate => 240,
        ],
        self::APRankFour =>
        [
            self::APLimit => 180,
            self::APIncreaseRate => 211,
        ],
        self::APRankFive =>
        [
            self::APLimit => 210,
            self::APIncreaseRate => 189,
        ],
        self::APRankSix =>
        [
            self::APLimit => 240,
            self::APIncreaseRate => 171,
        ],
        self::APRankSeven =>
        [
            self::APLimit => 270,
            self::APIncreaseRate => 156,
        ],
        self::APRankEight =>
        [
            self::APLimit => 300,
            self::APIncreaseRate => 144,
        ],
        self::APRankNine =>
        [
            self::APLimit => 350,
            self::APIncreaseRate => 133,
        ],
        self::APRankMoreThanTen =>
        [
            self::APLimit => 400,
            self::APIncreaseRate => 124,
        ],
    ];

    const CauseNone = 0;
    const CauseSystemReward = 1;
    const CauseUseUCG = 2;
    const CausePVENormal = 3;
    const CausePVERush = 4;
}