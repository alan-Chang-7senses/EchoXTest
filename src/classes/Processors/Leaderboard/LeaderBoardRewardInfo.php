<?php

namespace Processors\Leaderboard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Games\PVE\PVEUtility;
use Games\Users\RewardHandler;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;

class LeaderBoardRewardInfo extends BaseProcessor
{
    protected bool $mustSigned = false;
    public function Process() : ResultData
    {
        $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $leaderboards = $accessor->FromTable("QualifyingData")
            ->WhereLess("StartTime", $nowtime)
            ->WhereGreater("EndTime", $nowtime)
            ->SelectExpr("SeasonID, Lobby")
            ->FetchAll();
        $accessor->ClearAll();

        $leaderboardId = [];
        $leaderboardMap = [];
        foreach( $leaderboards as $item )
        {
            $targetSeason = $leaderboardMap[$item->SeasonID] = new stdClass;
            $targetSeason->seasonId = $item->SeasonID;
            $targetSeason->lobby = $item->Lobby;
            array_push($leaderboardId, $item->SeasonID);
        }

        $rewards = $accessor->FromTable("SeasonRankingRewardNew")
            ->WhereIn("SeasonID", $leaderboardId)
            ->SelectExpr("SeasonID, 'Rank', RewarID")
            ->OrderBy(["SeasonID", "'Rank'"])
            ->FetchAll();
        $accessor->ClearAll();

        $currId = -1;
        $currRewardId = -1;
        $targetSeason = null;
        $targetReward = null;
        foreach( $rewards as $reward )
        {
            if( $currId != $reward->SeasonID )
            {
                $currId = $reward->SeasonID;
                $currRewardId = -1;
                $targetSeason = $leaderboardMap[$reward->SeasonID];
                $targetSeason->rewards = [];
            }

            if( $currRewardId != $reward->RewarID )
            {
                $currRewardId = $reward->RewarID;
                $targetReward = new stdClass;

                $targetReward->minRank = $reward->Rank;
                $targetReward->maxRank = $reward->Rank;

                $rewardHandler = new RewardHandler($reward->RewarID);
                $targetReward->items = PVEUtility::HandleRewardReturnValue($rewardHandler->GetItems());

                array_push($targetSeason->rewards, $targetReward);
            }
            else $targetReward->maxRank = $reward->Rank;
        }

        $result = new ResultData(ErrorCode::Success);
        $result->rewardInfo = [];
        foreach( $leaderboardMap as $item ) array_push($result->rewardInfo, $item);

        return $result;
    }
}

?>