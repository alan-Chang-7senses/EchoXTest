<?php
namespace Games\Exceptions;

use Exceptions\NormalException;
/**
 * Description of RaceException
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceException extends NormalException{
    
    const UserInRace = 4001;
    const OverPlayerMax = 4002;
    const OtherUserInRace = 4003;
}
