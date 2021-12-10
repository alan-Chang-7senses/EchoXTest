<?php

namespace Exceptions;

/**
 * Description of LoginException
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LoginException extends NormalException{
    
    const FormatError = 1001;
    const NoAccount = 1002;
    const PasswordError = 1003;
    const DisabledAccount = 1004;
}
