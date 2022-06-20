<?php

namespace Games\Races;

use Consts\Globals;
use Games\Consts\SkillValue;
use Games\Players\PlayerHandler;
use Games\Pools\RacePlayerEffectPool;
use stdClass;
use Games\Consts\RaceValue;
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
        $racePlayerInfo = $racePlayerHandler->GetInfo();
        
        $racePlayerEffectHandler = new RacePlayerEffectHandler($racePlayerInfo->id);
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
                SkillValue::EffectHP => $racePlayerHandler->SaveData(['hp' => $racePlayerInfo->hp + $value * RaceValue::DivisorHP]),
                SkillValue::EffectEnergyAll => $racePlayerHandler->SaveData(['energy' => array_map(function($val) use ($value) { return $val + $value; }, $racePlayerInfo->energy)]),
                SkillValue::EffectEnergyRed => self::AddEnergy($racePlayerHandler, SkillValue::EnergyRed, $value),
                SkillValue::EffectEnergyYellow => self::AddEnergy($racePlayerHandler, SkillValue::EnergyYellow, $value),
                SkillValue::EffectEnergyBlue => self::AddEnergy($racePlayerHandler, SkillValue::EnergyBlue, $value),
                SkillValue::EffectEnergyGreen => self::AddEnergy($racePlayerHandler, SkillValue::EnergyGreen, $value),
                default => null
            };
        }
        
        return $playerHandler;
    }
    
    private static function AddEnergy(RacePlayerHandler $racePlayerHandler, int $type, float $value) : void{
        $energy = $racePlayerHandler->GetInfo()->energy;
        $energy[$type] += $value;
        $racePlayerHandler->SaveData(['energy' => $energy]);
    }
}
