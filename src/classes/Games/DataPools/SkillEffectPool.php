<?php

namespace Games\DataPools;

use Games\Accessors\SkillAccessor;
use stdClass;
/**
 * Description of SkillEffectPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectPool extends BasePool{
    
    public static SkillEffectPool $instance;
    
    public static function Instance(): SkillEffectPool {
        if(empty(self::$instance)) self::$instance = new SkillEffectPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'skillEffect_';

    public function FromDB(int|string $id): stdClass|false {
        
        return (new SkillAccessor())->rowEffectByEffectID($id);
    }
}
