<?php

namespace Games\Leadboards;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Accessors\AccessorFactory;
use Games\Consts\RaceValue;
use Generators\ConfigGenerator;
use stdClass;
/**
 * Description of LeadboardUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LeadboardUtility {
    
    const LeadRateContents = [
        RaceValue::LobbyCoin => [
            'table' => 'LeaderboardLeadCoin',
            'seasonIdFunc' => 'Games\Races\RaceUtility::QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_1',
        ],
        RaceValue::LobbyCoinB => [
            'table' => 'LeaderboardLeadCoinB',
            'seasonIdFunc' => 'Games\Races\RaceUtility::QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_1',
        ],
        RaceValue::LobbyPT => [
            'table' => 'LeaderboardLeadPT',
            'seasonIdFunc' => 'Games\Races\RaceUtility::QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_2',
        ],
        RaceValue::LobbyPetaTokenB => [
            'table' => 'LeaderboardLeadPTB',
            'seasonIdFunc' => 'Games\Races\RaceUtility::QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_2',
        ],
    ];
    
    public static function PlayerLeadRanking(int $lobby, int $playerID, int $seasonID) : stdClass {
        
        $leadRateContent = self::LeadRateContents[$lobby];
        $table = $leadRateContent['table'];
        $treshold = ConfigGenerator::Instance()->{$leadRateContent['tresholdParam']};
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        
        $playerLead = $accessor->SelectExpr('PlayerID, PlayCount, LeadRate, UpdateTime, Nickname, ItemName')
                ->FromTableJoinUsing($table, 'PlayerHolder', 'LEFT', 'PlayerID')
                ->FromTableJoinUsingNext('PlayerNFT', 'LEFT', 'PlayerID')
                ->WhereEqual('SeasonID', $seasonID)->WhereEqual('PlayerID', $playerID)
                ->Fetch();
        
        $result = new stdClass();
        $result->ranking = 0;
        $result->nickname = (string)($playerLead->Nickname ?? $playerID);
        $result->itemName = (string)($playerLead->ItemName ?? $playerID);
        $result->leadRate = 0;
        $result->playCount = $playerLead->PlayCount ?? 0;
        
        if(!empty($playerLead) && $playerLead->PlayCount >= $treshold){
            
            $accessor->ClearCondition();
            $rows = $accessor->SelectExpr('*')->FromTable($table)
                    ->WhereEqual('SeasonID', $seasonID)->WhereEqual('LeadRate', $playerLead->LeadRate)->FetchAll();

            $accessor->ClearCondition();
            $ranking = $accessor->SelectExpr('COUNT(*) AS cnt')
                    ->WhereEqual('SeasonID', $seasonID)->WhereGreater('LeadRate', $playerLead->LeadRate)->WhereCondition('PlayCount', '>=', $treshold)
                    ->Fetch()->cnt;

            foreach($rows as $row){

                if($row->PlayerID == $playerID) continue;
                if($row->PlayCount > $playerLead->PlayCount){
                    ++$ranking;
                    continue;
                }else if($row->PlayCount == $playerLead->PlayCount && $row->UpdateTime < $playerLead->UpdateTime) ++$ranking;
            }
            
            $result->ranking = ++$ranking;
            $result->leadRate = $playerLead->LeadRate / RaceValue::DivisorPercent;
        }
        
        return $result;
    }

    /**
     * 取得特定角色當前的排名
     */
    public static function GetPlayerRateRanking(int $lobby, int $playerID, int $seasonID) : int|false
    {
        $accessor = AccessorFactory::Main();

        $row = $accessor->FromTable('LeaderboardRating')
                 ->WhereEqual('PlayerID',$playerID)
                 ->WhereEqual('SeasonID', $seasonID)
                 ->WhereEqual('Lobby',$lobby)
                 ->Fetch();
        if($row === false)return false; //不在排行榜中
        $row = $accessor->ClearCondition()
                        ->SelectExpr('COUNT(*) AS cnt')
                        ->FromTable('LeaderboardRating')
                        ->WhereEqual('SeasonID',$seasonID)
                        ->WhereEqual('Lobby',$lobby)
                        ->WhereGreater('Rating',$row->Rating)
                        ->Fetch();
        return $row->cnt + 1;
    }

    /**
     * 取得該賽制之賽季所有角色的排行，可使用參數指定排名區間
     * @param int $seasonID 賽季ID
     * @param int $lobby 賽制大廳ID
     * @param int $offset 要獲得名次的起始名次。未輸入則從第一名開始獲取
     * @param int $endRank 要獲得角色的最大名次。未輸入則使用默認數量1~100名
     * @param return array 回傳值：型別為RatingRestult的陣列。若無資料則回傳false
     */
    public static function GetPlayersRateRanking(int $seasonID, int $lobby, int $offset = 1, int $endRank = 100 ) : array|false
    {
        $offset = min(1,$offset);
        $accessor = AccessorFactory::Main();
        $rows = $accessor->SelectExpr('PlayerID AS ID, Rating')
                 ->FromTable('LeaderboardRating')
                 ->WhereEqual('SeasonID',$seasonID)
                 ->WhereEqual('Lobby', $lobby)
                 ->OrderBy('Rating','DESC')
                 ->Limit($endRank)
                 ->FetchAll();
        if(empty($rows))return false;
        return self::HandleRankingInfo($rows,$offset,$endRank);
    }

    /**
     * 取得該賽制之賽季所有使用者排行，可使用參數指定排名區間
     * @param int $seasonID 賽季ID
     * @param int $lobby 賽制大廳ID。目前無法指定
     * @param int $offset 要獲得名次的起始名次。未輸入則從第一名開始獲取
     * @param int $endRank 要獲得使用者的最大名次。未輸入則使用默認數量1~100名
     * @param return array 回傳值：型別為RatingRestult的陣列。若無資料則回傳false
     */
    public static function GetUsersRateRanking(int $seasonID, int $lobby = 0, int $offset = 1, int $endRank = 100) : array | false
    {

        $offset = min(1,$offset);
        $accessor = AccessorFactory::Main();
        $rows = $accessor->selectExpr('SUM(Rating) Rating, UserID AS ID')
                 ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                 ->WhereEqual('SeasonID',$seasonID)
                 ->WhereIn('Lobby',[RaceValue::LobbyPT,RaceValue::LobbyPetaTokenB])
                 ->GroupBy('UserID')
                 ->OrderBy('Rating','DESC')
                 ->Limit($endRank)
                 ->FetchAll();
        if(empty($rows))return false;
        return self::HandleRankingInfo($rows,$offset,$endRank);
    }

    private static function HandleRankingInfo(array $rows,int $offset, int $endRank) : array
    {
        $rankingInfo = [];
        $ranking = 1;
        $offset = max(1,$offset);

        for($i = $offset - 1; $ranking <= $endRank && $i < count($rows); $i++)
        {
            $ratingResult = new RatingResult();
            $ratingResult->id = $rows[$i]->ID;
            $ratingResult->rate = $rows[$i]->Rating;
            $ratingResult->rank = $ranking;
            $rankingInfo[] =  $ratingResult;
            
            if(isset($rows[$i + 1]) && $rows[$i + 1]->Rating != $rows[$i]->Rating)
            {
                $ranking = $i + 2;
            }
        }
        return $rankingInfo;
    }
}
