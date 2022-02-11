<?php
namespace Games\Exceptions;

use Exceptions\NormalException;
/**
 * Description of RaceException
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceException extends NormalException{
    
    const UserInRace = 2001;
    const OverPlayerMax = 2002;
    const UserNotExist = 2003;
    const OtherUserInRace = 2004;
}
