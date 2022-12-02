<?php

namespace Games\Leadboards;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Consts\RaceValue;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Users\UserHandler;
use Generators\ConfigGenerator;
use stdClass;
/**
 * Description of LeadboardUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LeadboardUtility {

    const LeadRateContents = [
        RaceValue::LobbyCoinA => [
            'table' => 'LeaderboardLeadCoin',
            'seasonIdFunc' => 'Games\Races\RaceUtility::QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_1',
        ],
        RaceValue::LobbyCoinB => [
            'table' => 'LeaderboardLeadCoinB',
            'seasonIdFunc' => 'Games\Races\RaceUtility::QualifyingSeasonID',
            'tresholdParam' => 'PvP_B_Treshold_1',
        ],
        RaceValue::LobbyPetaTokenA => [
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
    public static function GetPlayerOwnRateRanking(array $src, int $playerID, int $seasonID) : RatingResult | false
    {
        $idList = array_column($src, "id");
        $userIdx = array_search($playerID, $idList);
        if( false !== $userIdx ) return clone $src[$userIdx];

        $accessor = AccessorFactory::Main();

        $row = $accessor
                 ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                 ->WhereEqual('PlayerID',$playerID)
                 ->WhereEqual('SeasonID', $seasonID)
                 ->Fetch();
        if($row === false)return false; //不在排行榜中

        $playerRate = $row->Rating;
        $userId = $row->UserID;
        $row = $accessor->ClearCondition()
                        ->SelectExpr('COUNT(*) AS cnt')
                        ->FromTable('LeaderboardRating')
                        ->WhereEqual('SeasonID',$seasonID)
                        ->WhereGreater('Rating', $playerRate)
                        ->Fetch();

        $info = (new PlayerHandler($playerID))->GetInfo();
        $idName = PlayerUtility::GetIDName($playerID);

        $result = new RatingResult();
        $result->userId = $userId;
        $result->petaName = (string)$idName;
        $result->petaId = $playerID;
        $result->rank = $row->cnt + 1;
        $result->rate = $playerRate;
        return $result;
    }

    /**
     * 取得該賽制之賽季所有角色的排行，可使用參數指定排名區間
     * @param int $seasonID 賽季ID
     * @param int $offset 要獲得名次的起始名次。未輸入則從第一名開始獲取
     * @param int $endRank 要獲得角色的最大名次。未輸入則使用默認數量1~100名
     * @param return array 回傳值：型別為RatingRestult的陣列。若無資料則回傳false
     */
    public static function GetPlayersRateRanking(int $seasonID, int $offset = 1, int $endRank = 100 ) : array|false
    {
        $offset = min(1,$offset);
        $accessor = AccessorFactory::Main();
        $rows = $accessor->SelectExpr('PlayerID, Rating, UserID')
                 ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                 ->WhereEqual('SeasonID',$seasonID)
                 ->OrderBy('Rating','DESC')
                 ->Limit($endRank)
                 ->FetchAll();
        if(empty($rows))return false;
        return self::HandleRankingInfo($rows,$offset,$endRank);
    }

    /**
     * 取得該賽制之賽季所有使用者排行，可使用參數指定排名區間
     * @param int $seasonID 賽季ID
     * @param int $offset 要獲得名次的起始名次。未輸入則從第一名開始獲取
     * @param int $endRank 要獲得使用者的最大名次。未輸入則使用默認數量1~100名
     * @param return array 回傳值：型別為RatingRestult的陣列。若無資料則回傳false
     */
    public static function GetUsersRateRanking(array $seasonID, int $offset = 1, int $endRank = 100) : array | false
    {

        $offset = min(1,$offset);
        $accessor = AccessorFactory::Main();
        $rows = $accessor->selectExpr('SUM(Rating) Rating, UserID AS ID')
        $rows = $accessor->selectExpr('SUM(Rating) Rating, UserID')
                 ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                 ->WhereEqual('SeasonID',$seasonID)
                 ->WhereIn('Lobby',[RaceValue::LobbyPT,RaceValue::LobbyPetaTokenB])
                 ->WhereIn('SeasonID', $seasonID)

                 ->GroupBy('UserID')
                 ->OrderBy('Rating','DESC')
                 ->Limit($endRank)
                 ->FetchAll();
        if(empty($rows))return false;
        return self::HandleRankingInfo($rows,$offset,$endRank);
        return self::HandleRankingInfo($rows, $offset, $endRank);
    }

    /**
     * 取得該賽制之賽季排行，用於執行派獎
     * @param int $seasonID 賽季ID
     * @param int $lobby 賽制大廳ID。目前無法指定
     * @param int $endRank 要獲得使用者的最大名次。未輸入則使用默認數量1~100名
     * @param return array 回傳值：型別為RatingRestult的陣列。若無資料則回傳false
     */
    public static function GetSeasonRankingForPlayer(int $seasonID, int $lobby = 0, int $endRank = 100) : array | false
    {
        $accessor = AccessorFactory::Main();
        $rows = $accessor->selectExpr('Rating, UserID, PlayerID')
                 ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                 ->WhereEqual('SeasonID',$seasonID)
                 ->WhereEqual('Lobby', $lobby)
                 ->WhereGreater('Rating', 0)
                 ->OrderBy('Rating','DESC')
                 ->FetchAll();
        if(empty($rows))return false;
        return self::HandleRankingInfo($rows,1,$endRank);
    }

    /**
     * 取得該賽制之賽季總積分排行，用於執行派獎
     * @param int $seasonID 賽季ID
     * @param int $lobby 賽制大廳ID。目前無法指定
     * @param int $endRank 要獲得使用者的最大名次。未輸入則使用默認數量1~100名
     * @param return array 回傳值：型別為RatingRestult的陣列。若無資料則回傳false
     */
    public static function GetSeasonRankingForUser(int $seasonID, int $lobby = 0, int $endRank = 100) : array | false
    {
        $accessor = AccessorFactory::Main();
        $rows = $accessor->selectExpr('SUM(Rating) Rating, UserID')
                 ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                 ->WhereEqual('SeasonID',$seasonID)
                 ->WhereIn('Lobby',[RaceValue::LobbyPetaTokenA,RaceValue::LobbyPetaTokenB])
                 ->GroupBy('UserID')
                 ->WhereGreater('Rating', 0)
                 ->OrderBy('Rating','DESC')
                 ->FetchAll();
        if(empty($rows))return false;
        return self::HandleRankingInfo($rows,1,$endRank);
    }

    public static function GetUserOwnRateRanking(array $src, int $userID, array $seasonID) : RatingResult | false
    {
        $idList = array_column($src, "id");
        $userIdx = array_search($userID, $idList);
        if( false !== $userIdx ) return clone $src[$userIdx];

        $accessor = AccessorFactory::Main();
        $userRating = $accessor->selectExpr('SUM(Rating) Rating')
                 ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                 ->WhereIn('SeasonID', $seasonID)
                 ->WhereEqual('UserID', $userID)
                 ->Fetch();
        if( $userRating === false ) return false; //不在排行榜中

        $row = $accessor->ClearCondition()
                        ->SelectExpr('SUM(Rating) Rating, UserID')
                        ->FromTableJoinUsing('LeaderboardRating','PlayerHolder','INNER','PlayerID')
                        ->WhereIn('SeasonID', $seasonID)
                        ->GroupBy('UserID')
                        ->OrderBy('Rating','DESC')
                        ->FetchAll();

        $userInfo = (new UserHandler($userID))->GetInfo();
        $playerID = $userInfo->player;
        $info = (new PlayerHandler($playerID))->GetInfo();
        $idName = PlayerUtility::GetIDName($playerID);

        $result = new RatingResult();
        $result->userId = $userID;
        $result->petaName = (string)$idName;
        $result->petaId = $playerID;
        $result->rank = array_search($userRating->Rating, array_column($row, "Rating")) + 1;
        $result->rate = $userRating->Rating;
        return $result;
    }

    private static function HandleRankingInfo(array $rows,int $offset, int $endRank) : array
    {
        $rankingInfo = [];
        $ranking = 1;
        $currRating = -1;
        $sameRank = 1;
        $offset = max(1,$offset);

        if( !property_exists($rows[0], "PlayerID") )
        {
            foreach( $rows as $row )
            {
                $userInfo = (new UserHandler($row->UserID))->GetInfo();
                $row->PlayerID = $userInfo->player;
            }
        }

        for( $i=0 ; $ranking<=$endRank && $i<count($rows) ; ++$i )
        {
            $info = (new PlayerHandler($rows[$i]->PlayerID))->GetInfo();
            $idName = PlayerUtility::GetIDName($rows[$i]->PlayerID);

            $ratingResult = new RatingResult();
            $ratingResult->userId = $rows[$i]->UserID;
            $ratingResult->petaName = (string)$idName;
            $ratingResult->petaId = $rows[$i]->PlayerID;
            $ratingResult->rate = $rows[$i]->Rating;
            $ratingResult->rank = $ranking;

            if( $currRating != $rows[$i]->Rating )
            {
                $currRating = $rows[$i]->Rating;
                $ranking += $sameRank;
                $sameRank = 1;
            }
            else
            {
                ++$sameRank;
            }
            array_push($rankingInfo, $ratingResult);
        }
        return $rankingInfo;
    }
}
