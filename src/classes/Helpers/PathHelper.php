<?php

namespace Helpers;

use Consts\Globals;
/**
 * Description of PathHelper
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PathHelper {
    
    public static function getPath(string|array $dirs) : string{
        
        $path = is_array($dirs) ? implode(DIRECTORY_SEPARATOR, $dirs) : implode(DIRECTORY_SEPARATOR, func_get_args());
        return $GLOBALS[Globals::ROOT].$path.DIRECTORY_SEPARATOR;
    }
    
    public static function getFilePath(string $file, string|array $dirs) : string{
        
        $path = '';
        if(is_array($dirs)) $path = implode(DIRECTORY_SEPARATOR, $dirs);
        else{
            $args = func_get_args();
            unset($args[0]);
            $path = implode(DIRECTORY_SEPARATOR, $args);
        }
        
        return $GLOBALS[Globals::ROOT].$path.DIRECTORY_SEPARATOR.$file;
    }
}
