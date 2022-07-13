<?php

namespace Games\Exceptions;

use Exceptions\NormalException;
/**
 * Description of PlayerException
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerException extends NormalException{
    
    const PlayerNotExist = 3001;
    const NoSuchSkill = 3002;
    const OverSlot = 3003;
}
