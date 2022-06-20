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
    const IncorrectPlayerNumber = 4003;
    const OtherUserInRace = 4004;
    const PlayerReached = 4005;
    const Finished = 4006;
    const PlayerNotReached = 4007;
    const RankingNoMatch = 4008;
    const FinishFailure = 4009;
    const EnergyNotEnough = 4010;
    const PlayerNotInThisRace = 4011;
    const NotBotPlayer = 4012;
    const NotBotInMatch = 4013;
    const EnergyNotRunOut = 4014;
}
