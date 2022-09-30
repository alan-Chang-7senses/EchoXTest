<?php

namespace Games\Consts;

/*
 * Description of RaceVerifyValue
 * 比賽驗證常數
 */

class RaceVerifyValue {

    const Decimals = 2;

    /* 驗證結果 */
    const VerifyNotCheat = 0;
    const VerifyCheat = 1;
    const VerifyWaitStart = 2;
    const VerifyNoInfo = 3;

    /* 驗證狀態 */
    const StateReady = 1;
    const StateStart = 2;
    const StateSkill = 3;
    const StateOtherSkill = 4;
    const StatePlayerValue = 5;
    const StateEnergyAgain = 6;
    const StateReachEnd = 7;
    const StateEnergyBonus = 8;

}
