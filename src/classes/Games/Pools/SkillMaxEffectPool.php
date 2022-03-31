<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\SkillAccessor;
use Games\Skills\Holders\SkillMaxEffectHolder;
use Generators\DataGenerator;
use stdClass;
/**
 * Description of SkillMaxEffectPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillMaxEffectPool extends PoolAccessor{
    
    private static SkillMaxEffectPool $instance;
    
    public static function Instance() : SkillMaxEffectPool {
        if(empty(self::$instance)) self::$instance = new SkillMaxEffectPool ();
        return self::$instance;
    }

    protected string $keyPrefix = 'skillMaxEffect_';

    public function FromDB(int|string $id): stdClass|false {
        
        $row = (new SkillAccessor())->rowMaxEffectByMaxEffectID($id);
        
        $holder = new SkillMaxEffectHolder();
        $holder->id = $id;
        $holder->type = $row->EffectType;
        $holder->target = $row->Target;
        $holder->typeValue = $row->TypeValue;
        $holder->formula = $row->Formula;
        
        return DataGenerator::ConventType($holder, 'stdClass');
    }
}
