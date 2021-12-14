<?php

namespace Helpers;

use Consts\ErrorCode;
use Exception;
/**
 * Description of InfoHelper
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class InfoHelper {
    
    protected string $extension = 'php';

    private $path;

    public function __construct($dirs) {

        if(!is_array($dirs)) $dirs = func_get_args ();

        $folderPath = implode(DIRECTORY_SEPARATOR, $dirs);
        $this->path = is_dir($folderPath) ? $folderPath.DIRECTORY_SEPARATOR : PathHelper::getPath($dirs);
    }

    public function __get($property) {

        $path = $this->path.$property;
        $file = $path.'.'.$this->extension;

        if(is_file($file)){

            $this->$property = $this->getConstents($file);

        }else if(is_dir($path)){

            $class = get_class($this);
            $this->$property = new $class($path);

        }else throw new Exception ('Info file not exist '.$file, ErrorCode::SystemError);

        return $this->$property;
    }
    
    protected function getConstents(string $file) : mixed{
        
        return require $file;
    }
}
