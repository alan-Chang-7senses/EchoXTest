<?php

namespace Accessors;

use Consts\EnvVar;
use Memcache;
/**
 * Description of MemcacheGenerator
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class MemcacheAccessor {
    
    private static MemcacheAccessor $instance;
    
    public static function Instance() : MemcacheAccessor {
        if(empty(self::$instance)) self::$instance = new MemcacheAccessor ();
        return self::$instance;
    }
    
    private Memcache $memcache;
    
    public function __construct() {
        
        $this->memcache = new Memcache();
        $this->memcache->addServer(getenv(EnvVar::MemcahcedHost), getenv(EnvVar::MemcachedPort));
    }
    
    public function get(string $key) : string|false {
        return $this->memcache->get($key);
    }
    
    public function set(string $key, string $value) : bool{
        return $this->memcache->set($key, $value);
    }
    
    public function delete(string $key) : bool{
        return $this->memcache->delete($key);
    }
    
    public function flush() : bool {
        return $this->memcache->flush();
    }
}
