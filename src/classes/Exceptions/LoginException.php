<?php

namespace Exceptions;

use Throwable;

/**
 * Description of LoginException
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LoginException extends NormalException{
    
    const SignOut = 1001;
    const FormatError = 1002;
    const NoAccount = 1003;
    const PasswordError = 1004;
    const DisabledAccount = 1005;
    
    public function __construct(int $code = 0, array $bind = [], Throwable $previous = NULL) {
        if($code == self::SignOut) $this->signOut = true;
        return parent::__construct($code, $bind, $previous);
    }
}
