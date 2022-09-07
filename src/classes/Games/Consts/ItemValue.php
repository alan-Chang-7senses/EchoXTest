<?php

namespace Games\Consts;

/**
 * Description of ItemValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ItemValue
{

    const EffectExp = 101;
    const EffectFireUp = 201;
    const EffectFireUpHigh = 202;
    const EffectWaterUp = 203;
    const EffectWaterUpHigh = 204;
    const EffectWoodUp = 205;
    const EffectWoodUpHigh = 206;
    const EffectSkillLevel = 207;

    Const ItemCannotStack = 0;

    const UseCannot = 0;
    const UseDirectly = 1;
    const UseChoose = 2;

    const CurrencyPower = -1;
    const CurrencyCoin = -2;
    const CurrencyDiamond = -3;
    const CurrencyPetaToken = -4;

    const CauseDefault = 0;
    const CauseUsed = 1;
    const CauseMail = 2;
    const CauseRace = 3;
    const CauseGainExp = 4;
    const CauseRankUp = 5;
    const CauseSkillUpgrade = 6;
    const CauseCreateUser = 7;

    const ActionObtain = 1;
    const ActionUsed = 2;
}
