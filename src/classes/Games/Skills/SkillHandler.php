<?php

namespace Games\Skills;

use Games\Pools\SkillPool;
use Games\Skills\Holders\SkillInfoHolder;
use Games\Skills\SkillEffectHandler;
use Games\Skills\SkillMaxEffectHandler;
use stdClass;
/**
 * Description of SkillHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillHandler {
    
    private SkillPool $pool;
    private int|string $id;
    private SkillInfoHolder|stdClass $info;
    private array|null $effects = null;
    private array|null $maxEffects = null;
    
    public function __construct(int|string $id) {
        $this->pool = SkillPool::Instance();
        $this->id = $id;
        $this->info = $this->pool->$id;
    }
    
    public function GetInfo() : SkillInfoHolder|stdClass{
        return $this->info;
    }
    
    public function GetEffects(bool $formulaValue = false) : array{
        if(is_array($this->effects)) return $this->effects;
        $this->effects = [];
        foreach($this->info->effects as $id){
            $handler = new SkillEffectHandler($id);
            $info = $handler->GetInfo();
            $effect = [
                'type' => $info->type,
                'duration' => $info->duration,
            ];
            if($formulaValue) $effect['formulaValue'] = $handler->GetFormulaResult();
            $this->effects[] = $effect;
        }
        return $this->effects;
    }
    
    public function GetMaxEffects(bool $formulaValue = false) : array{
        if(is_array($this->maxEffects)) return $this->maxEffects;
        $this->maxEffects = [];
        foreach ($this->info->maxEffects as $id) {
            $handler = new SkillMaxEffectHandler($id);
            $info = $handler->GetInfo();
            $maxEffect = [
                'type' => $info->type,
                'target' => $info->target,
                'typeValue' => $info->typeValue,
            ];
            if($formulaValue) $maxEffect['formulaValue'] = $handler->GetFormulaResult();
            $this->maxEffects[] = $maxEffect;
        }
        return $this->maxEffects;
    }
}
