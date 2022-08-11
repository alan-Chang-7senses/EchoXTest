<?php

namespace Games\Exceptions;

use Exceptions\NormalException;
/**
 * Description of UserException
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserException extends NormalException
{
    const UserNotExist = 2001;
    const NotHoldPlayer = 2002;
    //const ItemNotEnough = 2003;
    const UsernameTooLong = 2004;
    const UsernameAlreadyExist = 2005;
    const UsernameDirty = 2006;
    const UsernameNotEnglishOrNumber = 2007;
    const CanNotResetName = 2008;
    const UserNameNotSetYet = 2009;
    const AlreadyHadFreePeta = 2010;
}
