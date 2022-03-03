<?php

namespace Games\Skills;

use Games\Pools\SkillPool;
use Games\Skills\Holders\SkillInfoHolder;
use Games\Skills\SkillEffectHandler;
use Games\Skills\SkillMaxEffectHandler;
use Generators\DataGenerator;
/**
 * Description of SkillHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillHandler {
    
    private SkillPool $pool;
    private int|string $id;
    private SkillInfoHolder $info;
    private array|null $effects = null;
    private array|null $maxEffects = null;
    
    public function __construct(int|string $id) {
        $this->pool = SkillPool::Instance();
        $this->id = $id;
        $this->info = DataGenerator::ConventType($this->pool->$id, 'Games\Skills\Holders\SkillInfoHolder');
    }
    
    public function GetInfo() : SkillInfoHolder{
        return $this->info;
    }
    
    public function GetEffects() : array{
        if(is_array($this->effects)) return $this->effects;
        $this->effects = [];
        foreach($this->info->effects as $id){
            $handler = new SkillEffectHandler($id);
            $info = $handler->GetInfo();
            $this->effects[] = [
                'type' => $info->type,
                'target' => $info->target,
                'duration' => $info->duration,
                'formulaValue' => $handler->GetFormulaResult(),
            ];
        }
        return $this->effects;
    }
    
    public function GetMaxEffects() : array{
        if(is_array($this->maxEffects)) return $this->maxEffects;
        $this->maxEffects = [];
        foreach ($this->info->maxEffects as $id) {
            $handler = new SkillMaxEffectHandler($id);
            $info = $handler->GetInfo();
            $this->maxEffects[] = [
                'type' => $info->type,
                'typeValue' => $info->typeValue,
                'formulaValue' => $handler->GetFormulaResult(),
            ];
        }
        return $this->maxEffects;
    }
}
