<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Accessors\PVEAccessor;
use Games\Consts\ItemValue;
use Games\Consts\PVEValue;
use Games\Exceptions\PVEException;
use Games\PVE\PVEChapterData;
use Games\PVE\PVEUtility;
use Games\PVE\UserPVEHandler;
use Games\Users\RewardHandler;
use Games\Users\UserUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class PVEMedalReward extends BaseProcessor
{

    public function Process(): ResultData
    {

        //需收到章節ID、欲領取獎勵階段編號。

        $phase = InputHelper::post('phase');
        $chapterID = InputHelper::post('chapterID');

        //檢查是否有辦法領獎。
        $userID = $_SESSION[Sessions::UserID];
        $userPVEHandler = new UserPVEHandler($userID);
        $chapterRow = PVEChapterData::GetChapterInfo($chapterID);
        if(empty($chapterRow))throw new PVEException(PVEException::ChapterRewardNotAvailible);
        $medalAmount = $userPVEHandler->GetChapterMedalAmount($chapterID);
        $rewardAvailible = match((int)$phase)
        {
            PVEValue::ChapterRewardFirst => $medalAmount >= $chapterRow->medalAmountFirst,
            PVEValue::ChapterRewardSecond => $medalAmount >= $chapterRow->medalAmountSecond,
            PVEValue::ChapterRewardThird => $medalAmount >= $chapterRow->medalAmountThird,
            default => false,
        };
        //檢查是否未達領獎標準
        if(!$rewardAvailible)
        throw new PVEException(PVEException::ChapterRewardNotAvailible);

        //獎賞已領過：false。
        if((new PVEAccessor())->AddChapterRewardInfo($chapterID,$phase))
        {
            $rewardID = match((int)$phase)
            {
                PVEValue::ChapterRewardFirst => $chapterRow->rewardIDFirst,
                PVEValue::ChapterRewardSecond => $chapterRow->rewardIDSecond,
                PVEValue::ChapterRewardThird => $chapterRow->rewardIDThrid,
            };
            $rewardHandler = new RewardHandler($rewardID);
            $items = $rewardHandler->GetItems();
            UserUtility::AddItems($userID,$items,ItemValue::CausePVEMedalReward);
            $result = new ResultData(ErrorCode::Success);
            //打包前端
            $result->reward = PVEUtility::HandleRewardReturnValue($items);
            return $result;
        }
        throw new PVEException(PVEException::ChapterRewardNotAvailible);

    }
}