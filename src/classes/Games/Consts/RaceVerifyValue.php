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

    /* 驗證階段 */
    const VerifyStageReady = 1;
    const VerifyStageStart = 2;
    const VerifyStageSkill = 3;
    const VerifyStageOtherSkill = 4;
    const VerifyStagePlayerValue = 5;
    const VerifyStageEnergyAgain = 6;
    const VerifyStageReachEnd = 7;
    const VerifyStageFinish = 8;

}
