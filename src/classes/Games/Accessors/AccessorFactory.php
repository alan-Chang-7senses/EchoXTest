<?php

namespace Games\Accessors;

use Consts\EnvVar;
use Accessors\PDOAccessor;
/**
 * Description of AccessorFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class AccessorFactory {
    
    public static function Main() : PDOAccessor{
        return new PDOAccessor(EnvVar::DBMain);
    }
    
    public static function Static() : PDOAccessor{
        return new PDOAccessor(EnvVar::DBStatic);
    }
    
    public static function Log() : PDOAccessor{
        return new PDOAccessor(EnvVar::DBLog);
    }
}
