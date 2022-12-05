<?php

namespace Games\Races;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Consts\Sessions;
use DateTime;
use Games\Accessors\AccessorFactory;
use Games\Consts\PlayerValue;
use Games\Consts\RaceValue;
use Games\Pools\PlayerPool;
use Games\PVP\CompetitionsInfoHandler;
use Games\PVP\QualifyingHandler;
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
        if ($lobby == RaceValue::LobbyStudy) {
            return RaceValue::NoTicketID;
        } else {
            return CompetitionsInfoHandler::Instance($lobby)->GetInfo()->ticketId;
        }
    }
    
    public static function GetTicketCost(int $lobby): int {
        if ($lobby == RaceValue::LobbyStudy) {
            return 0;
        } else {
            return CompetitionsInfoHandler::Instance($lobby)->GetInfo()->ticketCost;
        }
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
     * 取得目前各大廳賽季編號 (0: 尚未開放, >0: 賽季編號)
     * @param array $lobby 大廳編號
     * @return int (0: 尚未開放, >0: 賽季編號)
     */
    public static function GetSeasonIDByLobby(int $lobby): int {
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $data = $accessor->FromTable('QualifyingSeasonData')->WhereEqual('Lobby', $lobby)->WhereEqual('Status', RaceValue::QualifyingSeasonOpen)->Fetch();
        if ($data == false) {
            return RaceValue::NOSeasonID;
        }
        else {
            return $data->SeasonID;
        }
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

    /**
     * 結束競賽時。計算並紀錄每個角色積分。
     * @param array $racePlayerInfos 所有比賽角色RacePlayerInfo組成之集合
     */
    public static function RecordRatingForEachPlayer(array $racePlayerInfos, int $lobby) : array
    {
        //不計排行榜的賽制不計分
        if(!in_array($lobby,array_keys(RaceValue::LobbyCompetition)))
        {
            $rt = [];
            foreach($racePlayerInfos as $raningInfos)
            {
                $rt[$raningInfos->player]['new'] = 0;
                $rt[$raningInfos->player]['old'] = 0;
            }
            return $rt;
        }
        $competitionHandler = CompetitionsInfoHandler::Instance($lobby);

        $qualifyingHandler = new QualifyingHandler();
        $seasonID = $qualifyingHandler->GetSeasonIDByLobby($lobby);

        $accessor = AccessorFactory::Main();
        $playerIDs = array_column($racePlayerInfos,'player');
        $allRatings = [];
        $playCount = [];

        $whereValues = $accessor->valuesForWhereIn($playerIDs);
        $whereValues->bind['SeasonID'] = $seasonID;
        // //有可能是false，或有缺少資料。必須給預設值。
        $rows = $accessor->ClearCondition()
                 ->executeBindFetchAll('SELECT `PlayerID`, `Rating`, `UpdateTime`, `PlayCount` FROM `LeaderboardRating`
                                        WHERE `PlayerID` IN '.$whereValues->values.'
                                        AND SeasonID = :SeasonID',$whereValues->bind);
        
        foreach($rows as $row)
        {
            $allRatings[$row->PlayerID] = $row->Rating;
            $playCount[$row->PlayerID] = $row->PlayCount;
        }
        //取先前賽季的分數
        $accessor->ClearCondition()->PrepareName('GetPreSeasonRating');
        foreach($playerIDs as $playerID)
        {
            if(!isset($allRatings[$playerID]))
            {
                $row = $accessor->ClearCondition()
                                ->SelectExpr('Rating')
                                ->FromTable('LeaderboardRating')
                                ->WhereEqual('PlayerID',$playerID)
                                ->WhereEqual('Lobby',$lobby)
                                ->OrderBy('UpdateTime','DESC')
                                ->Limit(1)
                                ->Fetch();
                $oldRating = !empty($row) ? $row->Rating : null;
                $allRatings[$playerID] = $competitionHandler->GetResetRating($oldRating);
            }
            if(!isset($playCount[$playerID]))
            {
                $playCount[$playerID] = 0;
            }
        }
        $binds = [];
        $ratingResults = [];
        foreach($racePlayerInfos as $racePlayerInfo)
        {
            //找出自己以外的
            $allRatingsTemp = $allRatings;
            $playerID = $racePlayerInfo->player;
            //不幫機器人記排行榜。
            if($playerID < PlayerValue::BotIDLimit)continue;
            if($playerID < PlayerValue::freePetaMaxPlayerID)continue;
            unset($allRatingsTemp[$playerID]);
            $otherPlayerRatings = array_values($allRatingsTemp);
            $rating = $competitionHandler->GetRating($allRatings[$playerID],$otherPlayerRatings,$racePlayerInfo->ranking);
            $binds[] = 
            [
                'PlayerID' => $playerID,
                'SeasonID' => $seasonID,
                'Lobby' => $lobby,
                'Rating' => $rating,
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                'PlayCount' => $playCount[$playerID] + 1,
            ];
            $ratingResults[$playerID]['new'] = $rating;
            $ratingResults[$playerID]['old'] = $allRatings[$playerID];

        }

        $accessor->ClearAll()->FromTable('LeaderboardRating')->AddAll($binds,true);

        return $ratingResults;
    }    
    public static function GetSeasonResetRating(int $lobby, int $playerID) : int
    {
        $accessor = AccessorFactory::Main();                                   

        $row = $accessor->ClearCondition()->SelectExpr('Rating')
                                   ->FromTable('LeaderboardRating')
                                   ->WhereEqual('PlayerID',$playerID)
                                   ->WhereEqual('Lobby',$lobby)
                                   ->OrderBy('UpdateTime','DESC')
                                   ->Limit(1)
                                   ->Fetch(); 
        $oldRating = !empty($row) ? $row->Rating : null;
        return CompetitionsInfoHandler::Instance($lobby)->GetResetRating($oldRating);
    }
}
