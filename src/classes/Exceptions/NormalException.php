<?php

namespace Exceptions;

use Consts\Folders;
use Exception;
use Helpers\InfoHelper;
use Throwable;
/**
 * Description of NormalException
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NormalException extends Exception{
    
    public bool $signOut = false;

    public function __construct(int $code = 0, array $bind = [], Throwable $previous = NULL) {
        
        $lang = new InfoHelper(Folders::Exception);
        $class = get_class($this);
        return parent::__construct(strtr($lang->{substr($class, strripos($class, '\\') + 1)}[$code], $bind), $code, $previous);
    }
}
