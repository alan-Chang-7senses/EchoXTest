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
    const NicknameInValid = 3004;
    const NicknameLengthError = 3005;
    const NicknameNotEnglish = 3006;

    const AlreadyRankMax = 3007;
    const NotReachMaxLevelYet = 3008;

    const SkillLevelMax = 3009;
}
