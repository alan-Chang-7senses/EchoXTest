<?php

namespace Games\Skills;

use Games\Players\PlayerHandler;
use Games\Pools\PlayerPool;
use Games\Pools\SkillPool;
use Games\Races\RacePlayerHandler;
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
    
    public PlayerHandler|null $playerHandler = null;
    public RacePlayerHandler|null $racePlayerHandler = null;

    public function __construct(int|string $id) {
        $this->pool = SkillPool::Instance();
        $this->id = $id;
        $this->info = $this->pool->$id;
    }
    
    public function GetInfo() : SkillInfoHolder|stdClass{
        return $this->info;
    }
    
    public function GetEffects() : array{
        if(is_array($this->effects)) return $this->effects;
        $this->effects = [];
        foreach($this->info->effects as $id){
            $handler = new SkillEffectHandler($id);
            $info = $handler->GetInfo();
            $effect = [
                'type' => $info->type,
            ];
            
            if($this->playerHandler !== null) $effect['formulaValue'] = $handler->GetFormulaResult($this, $this->playerHandler, $this->racePlayerHandler);
            
            $this->effects[] = $effect;
        }
        return $this->effects;
    }
    
    public function GetMaxEffects() : array{
        if(is_array($this->maxEffects)) return $this->maxEffects;
        $this->maxEffects = [];
        foreach ($this->info->maxEffects as $id) {
            $handler = new SkillMaxEffectHandler($id);
            $info = $handler->GetInfo();
            $maxEffect = [
                'type' => $info->type,
                'target' => $info->target,
            ];
            
            if($this->playerHandler) $maxEffect['formulaValue'] = $handler->GetFormulaResult($this, $this->playerHandler, $this->racePlayerHandler);
            
            $this->maxEffects[] = $maxEffect;
        }
        return $this->maxEffects;
    }
    
    public function SetSkillSlot(int $plyerID, int $skillID, int $slot) : mixed{
        $result = $this->pool->SetSkillSlot( $plyerID,  $skillID,  $slot);
        PlayerPool::Instance()->Delete($plyerID);
        return $result;
    }
}
