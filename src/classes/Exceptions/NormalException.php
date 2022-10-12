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
    
    const SignOut = 1000;
    
    private array $bind;
    
    public function __construct(int $code = 0, array $bind = [], Throwable $previous = NULL) {
        
        $this->bind = $bind;
        $lang = new InfoHelper(Folders::Exception);
        $class = get_class($this);
        return parent::__construct(strtr($lang->{substr($class, strripos($class, '\\') + 1)}[$code], $bind), $code, $previous);
    }
    
    public function GetBind() : array{
        return $this->bind;
    }
}
