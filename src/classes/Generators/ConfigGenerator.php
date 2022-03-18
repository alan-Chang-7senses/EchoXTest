<?php

namespace Generators;

use Accessors\MemcacheAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Exception;
/**
 * Description of ConfigGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ConfigGenerator {
    
    private static ConfigGenerator $instance;
    
    public static function Instance() : ConfigGenerator {
        if(empty(self::$instance)) self::$instance = new ConfigGenerator();
        return self::$instance;
    }
    
    public function __get($property) {
        
        $key = 'Config'.$property;
        $memcache = MemcacheAccessor::Instance();
        $value = $memcache->get($key);
        
        if($value === false){
            
            $row = (new PDOAccessor(getenv(EnvVar::DBLabelMain)))->FromTable('Configs')->WhereEqual('Name', $property)->Fetch();
            if(empty($row)) throw new Exception ('Config '.$property.' undefined', ErrorCode::ConfigError);
            
            $memcache->set($key, $row->Value);
            $this->$property = $row->Value;
            
        }else $this->$property = $value;
        
        return $this->$property;
    }
}
