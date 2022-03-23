<?php

namespace Consts;

/**
 * Description of Predefined
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Predefined {
    
    const FormatDatetime = 'Y-m-d H:i:s';
    
    const FormatAccount = '/^[A-Za-z0-9_-]{4,16}$/';
    const FormatPassword = '/^.{4,16}$/';
    const UserEnabled = 1;
    const Maintaining = 'true';
    
    const SysLocal = 'local';
}
