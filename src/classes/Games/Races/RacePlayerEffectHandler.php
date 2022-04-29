<?php

namespace Games\Races;

use Consts\Globals;
use Games\Consts\SkillValue;
use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerEffectPool;
use stdClass;
/**
 * Description of RacePlayerEffectHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RacePlayerEffectHandler {
    
    private int|string $id;
    private RacePlayerEffectPool $pool;
    
    public stdClass $info;
    
    public function __construct(int|string $id) {
        $this->id = $id;
        $this->pool = RacePlayerEffectPool::Instance();
        $this->info = $this->pool->$id;
    }
    
    public function AddAll(array $binds) : void{
        $this->pool->Save($this->id, 'NewData', $binds);
    }
    
    public static function EffectPlayer(PlayerHandler $playerHandler, RacePlayerHandler $racePlayerHandler) : PlayerHandler{
        
        $current = $GLOBALS[Globals::TIME_BEGIN];
        $playerInfo = $playerHandler->GetInfo();
        
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerHandler->GetInfo()->id);
        foreach($racePlayerEffectHandler->info->list as $playerEffect){
            
            if($current > $playerEffect->EndTime) continue;
            
            $value = $playerEffect->EffectValue;
            match ($playerEffect->EffectType){
                SkillValue::EffectH => $playerHandler->offsetH += $value,
                SkillValue::EffectS => $playerHandler->offsetS += $value,
                SkillValue::EffectSPD => $playerInfo->velocity += $value,
                SkillValue::EffectPOW => $playerInfo->breakOut += $value,
                SkillValue::EffectFIG => $playerInfo->will += $value,
                SkillValue::EffectINT => $playerInfo->intelligent += $value,
                SkillValue::EffectSTA => $playerInfo->stamina += $value,
                SkillValue::EffectAdaptDune => $playerHandler->offsetDune += $value,
                SkillValue::EffectAdaptCraterLake => $playerHandler->offsetCraterLake += $value,
                SkillValue::EffectAdaptVolcano => $playerHandler->offsetVolcano += $value,
                SkillValue::EffectAdaptTailwind => $playerHandler->offsetTailwind += $value,
                SkillValue::EffectAdaptHeadwind => $playerHandler->offsetHeadwind += $value,
                SkillValue::EffectAdaptCrosswind => $playerHandler->offsetCrosswind += $value,
                SkillValue::EffectAdaptSunny => $playerHandler->offsetSunny += $value,
                SkillValue::EffectAdaptAurora => $playerHandler->offsetAurora += $value,
                SkillValue::EffectAdaptSandDust => $playerHandler->offsetSandDust += $value,
                SkillValue::EffectAdaptFlat => $playerHandler->offsetFlat += $value,
                SkillValue::EffectAdaptUpslope => $playerHandler->offsetUpslope += $value,
                SkillValue::EffectAdaptDownslope => $playerHandler->offsetDownslope += $value,
                SkillValue::EffectAdaptSun => $playerHandler->offsetSun += $value,
            };
        }
        
        return $playerHandler;
    }
}
