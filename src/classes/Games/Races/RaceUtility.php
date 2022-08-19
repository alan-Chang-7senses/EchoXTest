<?php
namespace Games\Races;

use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Scenes\SceneHandler;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
/**
 * Description of RaceUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceUtility {
    
    public static function RandomEnergy(int $slotNumber) : array{
        $count = RaceValue::BaseEnergyCount - RaceValue::EnergyFixedCount + $slotNumber;
        return self::RandomEnergyBase($count);
    }
    
    public static function RandomEnergyAgain(float $stamina) : array{
        $count = floor(20 * $stamina / 135);
        if($count >= RaceValue::EnergyAgainMax) $count = RaceValue::EnergyAgainMax;
        else if($count <= RaceValue::EnergyAgainMin) $count = RaceValue::EnergyAgainMin;
        return self::RandomEnergyBase($count);
    }
    
    private static function RandomEnergyBase(int $count) : array{
        $energy = array_fill(0, RaceValue::EnergyTypeCount, 0);
        $max = RaceValue::EnergyTypeCount - 1;
        for($i = 0; $i < $count; ++$i){
            ++$energy[random_int(0, $max)];
        }
        return $energy;
    }
    
    public static function RatioEnergy(array $counts, int $total) : array {
        
        $energy = [];
        $amount = 0;
        for($i = 0; $i < RaceValue::EnergyTypeCount; ++$i){
             $value = round($counts[$i] / $total * RaceValue::EnergyFixedCount);
             $energy[] = $value;
             $amount += $value;
        }
        
        $remain = RaceValue::EnergyFixedCount - $amount;
        for($i = 0; $i < $remain; ++$i){
            ++$energy[$i % RaceValue::EnergyTypeCount];
        }
        
        return $energy;
    }
    
    public static function BindRacePlayerEffect(int $racePlayerID, int $type, float $value, float $start, float $end) : array{
        return [
            'RacePlayerID' => $racePlayerID,
            'EffectType' => $type,
            'EffectValue' => $value,
            'StartTime' => $start,
            'EndTime' => $end,
        ];
    }

    public static function GetCurrentSceneSunValue():int
    {
        $userHolder =(new UserHandler($_SESSION[Sessions::UserID]))->GetInfo();
        $climates = (new SceneHandler($userHolder->scene))->GetClimate();        
        return $climates->lighting;
    }
    
    public static function GetTicketID(int $lobby) : int{
        
        $config = ConfigGenerator::Instance();
        return match ($lobby) {
            RaceValue::LobbyCoin => $config->PvP_B_TicketId_1,
            RaceValue::LobbyPT => $config->PvP_B_TicketId_2,
            default => RaceValue::NoTicketID,
        };
    }
}
