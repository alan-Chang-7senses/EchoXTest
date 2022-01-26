<?php

namespace Games\Consts;

/**
 * Description of SkillFormula
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SkillFormula {
    
    const SkillH = 0.66;
    const SkillS = 6.6;
    
    const SunNoneValue = 100;
    const SunSameValue = 120;
    const SunDiffValue = 80;
    
    const ClimateAccelerationSunny = 1;
    const ClimateAccelerationAurora = 1.2;
    const ClimateAccelerationSandDust = 1;
    
    const ClimateLoseSunny = 0;
    const ClimateLoseAurora = 0;
    const ClimateLoseSandDust = 0.5;
    
    const MaxEffectEnvDune = 1;
    const MaxEffectEnvCraterLake = 2;
    const MaxEffectEnvVolcano = 3;
    
    const MaxEffectTailwind = 1;
    const MaxEffectHeadwind = 2;
    const MaxEffectCrosswind = 3;
    
    const MaxEffectClimateSunny = 1;
    const MaxEffectClimateAurora = 2;
    const MaxEffectClimateSandDust = 3;
}