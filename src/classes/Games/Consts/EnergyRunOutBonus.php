<?php

namespace Games\Consts;

class EnergyRunOutBonus
{
    const RunOutRewards = 
    [
        [
            'number' => 1,
            'proportion' => 10,
            'duration' => 10,
            'type' => SkillValue::EffectS,
            'value' => 5,
        ],
        [
            'number' => 2,
            'proportion' => 20,
            'duration' => 0,
            'type' => SkillValue::EffectHP,
            'value' => 35,
        ],
        [
            'number' => 4,
            'proportion' => 40,
            'duration' => 10,
            'type' => SkillValue::EffectS,
            'value' => 1.5,
        ],
        [
            'number' => 3,
            'proportion' => 30,
            'duration' => 20,
            'type' => SkillValue::EffectH,
            'value' => -0.5,
        ],
    ]; 
}