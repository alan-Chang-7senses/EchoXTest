<?php

namespace Games\Races;

use Games\Consts\AchievementValue;
use Games\Consts\SkillValue;

class EnergyRunOutBonus
{
    public function __construct(string $achievementCode)
    {
        foreach($this->achievements as $code => $achievement)
        {
            if($achievementCode[$code] == AchievementValue::HasAchievement)
            $this->$achievement();
        }
    }
    public function GetRunOutBonus(){return $this->runOutRewards;}

    private array $achievements = 
    [
        AchievementValue::Glory => 'Glory',
        AchievementValue::Bright => 'Bright',
    ];
    private array $runOutRewards = 
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
            'number' => 3,
            'proportion' => 30,
            'duration' => 20,
            'type' => SkillValue::EffectH,
            'value' => -0.5,
        ],
        [
            'number' => 4,
            'proportion' => 40,
            'duration' => 10,
            'type' => SkillValue::EffectS,
            'value' => 1.5,
        ],
    ];
    private function Glory() : void
    {
        for($i = 0; $i <count($this->runOutRewards); $i++ )
        {
            match($this->runOutRewards[$i]['number'])
            {
                1 => $this->runOutRewards[$i]['proportion'] = 40,
                2 => $this->runOutRewards[$i]['proportion'] = 30,
                3 => $this->runOutRewards[$i]['proportion'] = 10,
                4 => $this->runOutRewards[$i]['proportion'] = 20,
            };
        }
    } 
    private function Bright() : void
    {
        $biggestReward = 0;
        $this->runOutRewards[$biggestReward]['value'] *= 2;
    } 
}