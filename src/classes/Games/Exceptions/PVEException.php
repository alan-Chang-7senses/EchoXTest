<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class PVEException extends NormalException
{
    const ChapterLock = 7001;
    const UserNotInPVE = 7002;
    const UserInPVE = 7003;
}