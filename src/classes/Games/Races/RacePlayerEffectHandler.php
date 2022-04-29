<?php

namespace Games\Races;

use Consts\Globals;
use Games\Consts\RaceValue;
use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerEffectPool;
use stdClass;
use Games\Consts\SkillValue;
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
            };
        }
        
        return $playerHandler;
    }
}
