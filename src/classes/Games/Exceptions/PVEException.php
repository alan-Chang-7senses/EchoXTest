<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class PVEException extends NormalException
{
    const ChapterLock = 7001;
    const LevelLock = 7002;
    const UserNotInPVE = 7003;
    const UserInPVE = 7004;
    const LevelCannotRush = 7005;
}