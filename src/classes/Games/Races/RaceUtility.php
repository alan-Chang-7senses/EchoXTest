<?php

namespace Games\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Sessions;
use Games\Consts\PlayerValue;
use Games\Consts\RaceValue;
use Games\Pools\PlayerPool;
use Games\Scenes\SceneHandler;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
/**
 * Description of RaceUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class RaceUtility {

    public static function RandomEnergy(int $slotNumber): array {
        $count = RaceValue::BaseEnergyCount - RaceValue::EnergyFixedCount + $slotNumber;
        return self::RandomEnergyBase($count);
    }

    public static function RandomEnergyAgain(int $skillHoleCount, int $equipmentCount): array {

        $count = RaceValue::BaseEnergyCount + $skillHoleCount + $equipmentCount;
        
        $count = max(RaceValue::EnergyAgainMin, min(RaceValue::EnergyAgainMax, $count));

        return self::RandomEnergyBase($count);
    }

    private static function RandomEnergyBase(int $count): array {
        $energy = array_fill(0, RaceValue::EnergyTypeCount, 0);
        $max = RaceValue::EnergyTypeCount - 1;
        for ($i = 0; $i < $count; ++$i) {
            ++$energy[random_int(0, $max)];
        }
        return $energy;
    }

    public static function RatioEnergy(array $counts, int $total): array {

        $energy = [];
        $amount = 0;
        for ($i = 0; $i < RaceValue::EnergyTypeCount; ++$i) {
            $value = round($counts[$i] / $total * RaceValue::EnergyFixedCount);
            $energy[] = $value;
            $amount += $value;
        }

        $remain = RaceValue::EnergyFixedCount - $amount;
        for ($i = 0; $i < $remain; ++$i) {
            ++$energy[$i % RaceValue::EnergyTypeCount];
        }

        return $energy;
    }

    public static function BindRacePlayerEffect(int $racePlayerID, int $type, float $value, float $start, float $end): array {
        return [
            'RacePlayerID' => $racePlayerID,
            'EffectType' => $type,
            'EffectValue' => $value,
            'StartTime' => $start,
            'EndTime' => $end,
        ];
    }

    public static function GetCurrentSceneSunValue(): int {
        $userHolder = (new UserHandler($_SESSION[Sessions::UserID]))->GetInfo();
        $climates = (new SceneHandler($userHolder->scene))->GetClimate();
        return $climates->lighting;
    }

    public static function GetTicketID(int $lobby): int {

        $config = ConfigGenerator::Instance();
        return match ($lobby) {
            RaceValue::LobbyCoinA, RaceValue::LobbyCoinB => $config->PvP_B_TicketId_1,
            RaceValue::LobbyPetaTokenA, RaceValue::LobbyPetaTokenB => $config->PvP_B_TicketId_2,
            default => RaceValue::NoTicketID,
        };
    }

    public static function GetMaxTickets(int $lobby): int {
        switch ($lobby) {
            case RaceValue::LobbyCoinA:
            case RaceValue::LobbyCoinB:
                return ConfigGenerator::Instance()->PvP_B_MaxTickets_1;
            case RaceValue::LobbyPetaTokenA:
            case RaceValue::LobbyPetaTokenB:
                return ConfigGenerator::Instance()->PvP_B_MaxTickets_2;
        }
        return 0;
    }

    public static function GetTicketCount(int $lobby): int {

        $config = ConfigGenerator::Instance();
        return match ($lobby) {
            RaceValue::LobbyCoinA, RaceValue::LobbyCoinB => $config->PvP_B_FreeTicketId_1_Count,
            RaceValue::LobbyPetaTokenA, RaceValue::LobbyPetaTokenB => $config->PvP_B_FreeTicketId_2_Count,
            default => 0,
        };
    }

    public static function CheckPlayerID(int $lobby, int $playerID): bool {
        return match ($lobby) {
            RaceValue::LobbyPetaTokenA, RaceValue::LobbyPetaTokenB => (strlen($playerID) == PlayerValue::LengthNFTID),
            default => true,
        };
    }

    public static function GetLeadRateForWriteDB(int $leadCount, int $playCount): int {
        return intval($leadCount / $playCount * RaceValue::DivisorLeadRate);
    }

    public static function QualifyingSeasonID(): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        return $accessor->FromTable('QualifyingSeason')->OrderBy('QualifyingSeasonID', 'DESC')->Limit(1)->Fetch()->QualifyingSeasonID;
    }

    /**
     * 結束競賽狀態後恢復角色等級
     * 除完賽、棄賽、清理問題賽局、作弊出局外，請勿使用。
     * @param array $playerIDs
     * @return void
     */
    public static function FinishRestoreLevel(array $playerIDs) : void {
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $values = $accessor->valuesForWhereIn($playerIDs);
        $accessor->executeBind('UPDATE PlayerLevel SET `Level` = IF(`LevelBackup` > 0, `LevelBackup`, `Level`), `LevelBackup` = 0 WHERE PlayerID IN '.$values->values, $values->bind);
        $accessor->executeBind('UPDATE PlayerSkill SET `Level` = IF(`LevelBackup` > 0, `LevelBackup`, `Level`), `LevelBackup` = 0 WHERE PlayerID IN '.$values->values, $values->bind);
        
        PlayerPool::Instance()->DeleteAll($playerIDs);
    }
}
