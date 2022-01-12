<?php

namespace Games\Consts;

/**
 * Description of DNASun
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class DNASun {
    
    const Normal = 0;
    const Day = 1;
    const Night = 2;
    
    const AttrAdapt = [
        PlayerAttr::Fire => DNASun::Normal,
        PlayerAttr::Water => DNASun::Day,
        PlayerAttr::Wood => DNASun::Night,
    ];
}
