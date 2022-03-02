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
    const UserNotInRace = 4002;
    const OverPlayerMax = 4003;
    const OtherUserInRace = 4004;
    const PlayerReached = 4005;
    const Finished = 4006;
}
