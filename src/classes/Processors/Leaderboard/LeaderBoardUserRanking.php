<?php

namespace Processors\Leaderboard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Processors\BaseProcessor;
use Games\Consts\RaceValue;
use Games\Exceptions\LeaderboardException;
use Games\Leadboards\LeadboardUtility;
use Games\PVP\CompetitionsInfoHandler;
use Games\Users\UserHandler;
use Holders\ResultData;
use stdClass;

class LeaderBoardUserRanking extends BaseProcessor
{
    //protected bool $mustSigned = false;

    public function Process() : ResultData
    {
        $userId = $_SESSION[Sessions::UserID];

        $userHandler = new UserHandler($userId);
        $userInfo = $userHandler->GetInfo();

        $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];
        $accessor = new PDOAccessor(EnvVar::DBStatic);

        $seasonRatinInfo = [];
        $seasonInfo = $accessor->FromTable("QualifyingData")
                ->WhereLess("StartTime", $nowtime)
                ->WhereGreater("EndTime", $nowtime)
                ->SelectExpr("SeasonID, Lobby")
                ->FetchAll();
        $seasonIdList = array_column($seasonInfo, "SeasonID");

        $recordTypeMap = [];
        {
            $leaderBoards = $accessor->ClearAll()
                ->SelectExpr('SeasonID, RecordType')
                ->FromTable('Leaderboard')
                ->WhereIn('SeasonID', $seasonIdList)
                ->FetchAll();
            if($leaderBoards === false)throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);

            foreach( $leaderBoards as $leaderBoard ) $recordTypeMap[$leaderBoard->SeasonID] = $leaderBoard->RecordType;
        }

        foreach( $seasonInfo as $item )
        {
            $newRankInfo = false;
            if( !array_key_exists($item->SeasonID, $recordTypeMap) ) continue;

            $treshold = 0;
            if ( $item->Lobby != RaceValue::LobbyStudy) $treshold = CompetitionsInfoHandler::Instance($item->Lobby)->GetInfo()->treshold;

            switch( $recordTypeMap[$item->SeasonID] )
            {
                case 0:// peta rank
                    $newRankInfo = $this->ConstructPlayerRankInfo($item->SeasonID, $userInfo->player, $treshold);
                    break;

                case 1:// user rank
                    $newRankInfo = $this->ConstructTotalRankInfo([$item->SeasonID], $userId, $treshold);
                    break;

                default:break;
            }
            if( false === $newRankInfo ) continue;

            $newRankInfo->lobby = $item->Lobby;
            array_push($seasonRatinInfo, $newRankInfo);
        }

        $totalRankInfo = $this->ConstructTotalRankInfo($seasonIdList, $userId);

        //if( false === $rankingInfo ) throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);

        $result = new ResultData(ErrorCode::Success);
        $result->rankInfo = $seasonRatinInfo;
        $result->totalrankInfo = $totalRankInfo;
        return $result;
    }

    private function ConstructPlayerRankInfo(int $seasonId, int $playerId, int $treshold = 1)
    {
        $rankInfo = LeadboardUtility::GetPlayersRateRanking($seasonId, $treshold);
        //if( false === $rankInfo ) return false;

        $result = new stdClass;
        $result->rankInfo = false === $rankInfo ? [] : $rankInfo;

        $ownRankInfo = LeadboardUtility::GetPlayerOwnRateRanking($rankInfo, $playerId, $seasonId, $treshold);
        if( false !== $ownRankInfo ) $result->ownRank = $ownRankInfo;

        return $result;
    }

    private function ConstructTotalRankInfo(array $seasonId, int $userId, $treshold = 1)
    {
        $rankInfo = LeadboardUtility::GetUsersRateRanking($seasonId, $treshold);
        //if( false === $rankInfo ) return false;

        $result = new stdClass;
        $result->rankInfo = false === $rankInfo ? [] : $rankInfo;

        $ownRankInfo = LeadboardUtility::GetUserOwnRateRanking($rankInfo, $userId, $seasonId, $treshold);
        if( false !== $ownRankInfo ) $result->ownRank = $ownRankInfo;

        return $result;
    }
}

?>