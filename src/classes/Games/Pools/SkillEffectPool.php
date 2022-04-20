<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\SkillAccessor;
use Games\Skills\Holders\SkillEffectHolder;
use stdClass;
/**
 * Description of SkillEffectPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillEffectPool extends PoolAccessor{
    
    private static SkillEffectPool $instance;
    
    public static function Instance(): SkillEffectPool {
        if(empty(self::$instance)) self::$instance = new SkillEffectPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'skillEffect_';

    public function FromDB(int|string $id): stdClass|false {
        
        $row = (new SkillAccessor())->rowEffectByEffectID($id);
        $holder = new SkillEffectHolder();
        $holder->id = $id;
        $holder->type = $row->EffectType;
        $holder->formula = $row->Formula;
        
        return $holder;
    }
}
