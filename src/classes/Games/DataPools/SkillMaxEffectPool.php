<?php

namespace Games\DataPools;

use Games\Accessors\SkillAccessor;
use stdClass;
/**
 * Description of SkillMaxEffectPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillMaxEffectPool extends BasePool{
    
    public static SkillMaxEffectPool $instance;
    
    public static function Instance() : SkillMaxEffectPool {
        if(empty(self::$instance)) self::$instance = new SkillMaxEffectPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'skillMaxEffect_';

    public function FromDB(int|string $id): stdClass|false {
        
        return (new SkillAccessor())->rowMaxEffectByMaxEffectID($id);
    }
}
