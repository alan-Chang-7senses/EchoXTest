<?php

namespace Games\Consts;

/**
 * Description of PlayerValue
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerValue {
    
    const BotIDLimit = 0;
    
    const Head = 1;
    const Body = 2;
    const Hand = 3;
    const Leg = 4;
    const Back = 5;
    const Hat = 6;
    
    /** 太陽屬性 無差異值 */
    const SunNone = 100;
    /** 太陽屬性 與賽道日照一致值 */
    const SunSame = 120;
    /** 太陽屬性 與賽道日照相反值 */
    const SunDiff = 80;
    
    /** 比賽習慣 狂衝 */
    const Rush = 1;
    /** 比賽習慣 穩定 */
    const Stability = 2;
    /** 比賽習慣 優先 */
    const Priority = 3;
    /** 比賽習慣 蓄力 */
    const Accumulate = 4;
}
