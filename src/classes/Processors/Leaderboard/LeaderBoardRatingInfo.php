<?php

namespace Processors\Leaderboard;

use Consts\ErrorCode;
use Games\Accessors\AccessorFactory;
use Games\Exceptions\LeaderboardException;
use Holders\ResultData;
use Processors\BaseProcessor;

class LeaderBoardRatingInfo extends BaseProcessor
{
    public function Process(): ResultData
    {
        $staticAccessor = AccessorFactory::Static();
        $rows = $staticAccessor->FromTable('Leaderboard')->FetchAll();
        if($rows === false)throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);

        $result = new ResultData(ErrorCode::Success);

        $rewardAPI = new LeaderBoardRewardInfo();
        $rewardsInfo = $rewardAPI->Process()->rewardInfo;

        if(empty($rewardsInfo))throw new LeaderboardException(LeaderboardException::NoAnyLeaderboardData);
        $result->leaderBoards = [];
        foreach($rows as $row)
        {
            $seasonID = $row->SeasonID;
            foreach($rewardsInfo as $rewardInfo)
            {
                if($rewardInfo->seasonId == $seasonID)
                $rewards = $rewardInfo->rewards;
            }

            $result->leaderBoards[] = 
            [
                'id' => $row->Serial,
                'group' => $row->Group,
                'mainLeaderboardTitle' => $row->MainLeaderboradName,
                'subLeaderboardTitle' => $row->SubLeaderboardName,
                'ruleHint' => $row->CompetitionRuleHint,
                'ratingTarget' => $row->RecordType,
                'rankRuleHint' => $row->RankRuleHint,
                'rewards' => $rewards,
            ];
        }
        
        return $result;
    }
}