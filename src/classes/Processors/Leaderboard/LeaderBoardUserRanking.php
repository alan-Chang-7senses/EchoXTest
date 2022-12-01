<?php

namespace Processors\Leaderboard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\Sessions;
use Processors\BaseProcessor;
use Games\Exceptions\LeaderboardException;
use Games\Leadboards\LeadboardUtility;
use Games\Users\UserHandler;
use Holders\ResultData;
use stdClass;

class LeaderBoardUserRanking extends BaseProcessor
{
    protected bool $mustSigned = false;

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
                ->SelectExpr("QualifyingSeasonID, Lobby")
                ->FetchAll();
        foreach( $seasonInfo as $item )
        {
            $newRankInfo = $this->ConstructPlayerRankInfo($item->QualifyingSeasonID, $userInfo->player);
            if( false === $newRankInfo ) continue;
            $newRankInfo->lobby = $item->Lobby;
            array_push($seasonRatinInfo, $newRankInfo);
        }

        $seasonIdList = array_column($seasonInfo, "QualifyingSeasonID");
        $totalRankInfo = $this->ConstructTotalRankInfo($seasonIdList, $userId);

        //if( false === $rankingInfo ) throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);

        $result = new ResultData(ErrorCode::Success);
        $result->rankInfo = $seasonRatinInfo;
        $result->totalrankInfo = $totalRankInfo;
        return $result;
    }

    private function ConstructPlayerRankInfo(int $seasonId, int $playerId)
    {
        $rankInfo = LeadboardUtility::GetPlayersRateRanking($seasonId);
        if( false === $rankInfo ) return false;

        $result = new stdClass;
        $result->rankInfo = $rankInfo;

        $ownRankInfo = LeadboardUtility::GetPlayerOwnRateRanking($rankInfo, $playerId, $seasonId);
        if( false !== $ownRankInfo ) $result->ownRank = $ownRankInfo;

        return $result;
    }

    private function ConstructTotalRankInfo(array $seasonId, int $userId)
    {
        $rankInfo = LeadboardUtility::GetUsersRateRanking($seasonId);
        if( false === $rankInfo ) return false;

        $result = new stdClass;
        $result->rankInfo = $rankInfo;

        $ownRankInfo = LeadboardUtility::GetUserOwnRateRanking($rankInfo, $userId, $seasonId);
        if( false !== $ownRankInfo ) $result->ownRank = $ownRankInfo;

        return $result;
    }
}

?>