<?php

namespace Games\Consts;

/**
 * Description of Divisors
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SyncRate {
    
    const Divisor = 1000000;
    const Max = self::Divisor;
    const Min = 0;

    const PVEMultiplier = 0.01 * self::Divisor / 100;
    const PVPMultiplier = 0.2 * self::Divisor / 100;
    const ExpeditionMultiplier = 0.1 * self::Divisor / 100;

    const PVP = 0;
    const PVE = 1;
    const Expedition = 2;
}
