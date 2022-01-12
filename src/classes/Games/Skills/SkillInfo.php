<?php

namespace Games\Skills;

use Games\Consts\Keys;
use Accessors\MemcacheAccessor;
/**
 * Description of SkillInfo
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillInfo {
    
    public static SkillInfo $instance;
    
    public static function Instance() : SkillInfo {
        if(empty(self::$instance)) self::$instance = new SkillInfo();
        return self::$instance;
    }
    
    public function __get($id) {
        
        $key = Keys::SkillPrefix.$id;
        $mem = MemcacheAccessor::Instance();
        
        $info = $mem->get($key);
        if($info !== false) $info = json_decode ($info);
        else {
            
            $mem->set($key, json_encode($info));
        }
        
        $this->$key = $info;
        return $info;
    }
    
    
}
