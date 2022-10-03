<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class PVEException extends NormalException
{
    const ChapterLock = 7001;
}