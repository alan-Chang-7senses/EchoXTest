<?php

namespace Processors\Leaderboard;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Exceptions\LeaderboardException;
use Holders\ResultData;
use Processors\BaseProcessor;

class LeaderBoardRatingInfo extends BaseProcessor
{
    public function Process(): ResultData
    {
        $staticAccessor = AccessorFactory::Static();

        $nowtime = (int)$GLOBALS[Globals::TIME_BEGIN];
        $seasonInfo = $staticAccessor->FromTable("QualifyingData")
                ->WhereLess("StartTime", $nowtime)
                ->WhereGreater("EndTime", $nowtime)
                ->SelectExpr("QualifyingSeasonID")
                ->FetchAll();
        $seasonInfo = array_column($seasonInfo, "QualifyingSeasonID");

        $rows = $staticAccessor->ClearAll()
            ->FromTable('Leaderboard')
            ->WhereIn('SeasonID', $seasonInfo)
            ->FetchAll();
        if($rows === false)throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);

        $result = new ResultData(ErrorCode::Success);

        $rewardAPI = new LeaderBoardRewardInfo();
        $rewardsInfo = $rewardAPI->Process()->rewardInfo;
        $rewardsInfoMap = array_column($rewardsInfo, "seasonId");

        if(empty($rewardsInfo))throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);

        $result->leaderBoards = [];
        foreach($rows as $row)
        {
            $seasonID = $row->SeasonID;
            $reward = array_search($seasonID, $rewardsInfoMap);
            if( false !== $reward ) $reward = $rewardsInfo[$reward]->rewards;
            else $reward = null;

            array_push($result->leaderBoards,
            [
                'id' => $row->Serial,
                'group' => $row->Group,
                'mainLeaderboardTitle' => $row->MainLeaderboradName,
                'subLeaderboardTitle' => $row->SubLeaderboardName,
                'ruleHint' => $row->CompetitionRuleHint,
                'ratingTarget' => $row->RecordType,
                'rankRuleHint' => $row->RankRuleHint,
                'rewards' => $reward,
            ]);
        }

        return $result;
    }
}