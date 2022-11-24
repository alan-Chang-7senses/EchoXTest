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
     * 取得該賽制之賽季所有角色的排行
     * @param int $seasonID 賽季ID
     * @param int $lobby 賽制大廳ID
     * @param int $offset 要獲得名次的起始名次。未輸入則從第一名開始獲取
     * @param int $length 要獲得角色名次的數量。未輸入則獲得到指定數量
     */
    public static function GetPlayersRateRanking(int $seasonID, int $lobby, int $offset = 1, int $length = 100 ) : array|false
    {
        $accessor = AccessorFactory::Main();
        $rows = $accessor->SelectExpr('PlayerID, Rating')
                 ->FromTable('LeaderboardRating')
                 ->WhereEqual('SeasonID',$seasonID)
                 ->WhereEqual('Lobby', $lobby)
                 ->OrderBy('Rating','DESC')
                 ->FetchAll();
        if(empty($rows))return false;
        $rankingInfo = [];
        $ranking = 0;
        $offset = max(1,$offset);

        for($i = $offset - 1; $i < min(count($rows),$length); $i++)
        {
            if(isset($rows[$i - 1]) && $rows[$i - 1]->Rating != $rows[$i]->Rating)
            {
                $ranking = $i + 1;
            }
            $rankingInfo[] =  ['playerID' => $rows[$i]->PlayerID,'rate' => $rows[$i]->Rating, 'rank' => $ranking];
        }
        return $rankingInfo;
    }
}
