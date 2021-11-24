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
        $data = $memcache->get($key);
        
        if($data === false){
            
            $row = (new PDOAccessor(getenv(EnvVar::DBLabel)))->FromTable('Configs')->WhereEqual('Name', $property)->Fetch();
            if(empty($row)) throw new Exception ('Config '.$property.' undefined', ErrorCode::ConfigError);
            
            $memcache->set($key, json_encode($row));
            $this->$property = $row;
            
        }else $this->$property = json_decode($data);
        
        return $this->$property;
    }
}
