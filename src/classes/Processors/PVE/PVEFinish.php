<?php

namespace Processors\PVE;

use Consts\ErrorCode;
use Consts\Sessions;
use Games\Consts\ItemValue;
use Games\Consts\PVEValue;
use Games\Consts\RaceValue;
use Games\Exceptions\PVEException;
use Games\PVE\PVEUtility;
use Games\PVE\UserPVEHandler;
use Games\PVP\RaceRoomsHandler;
use Games\Races\RaceHandler;
use Games\Races\RacePlayerHandler;
use Games\Users\UserHandler;
use Games\Users\UserUtility;
use Holders\ResultData;
use Processors\BaseProcessor;

class PVEFinish extends BaseProcessor
{
    public function Process(): ResultData
    {
        $userInfo = (new UserHandler($_SESSION[Sessions::UserID]))->GetInfo();
        $userPVEHandler = new UserPVEHandler($userInfo->id);
        $roomInfo = (new RaceRoomsHandler())->GetRoomInfo($userPVEHandler->GetInfo()->raceRoomID);
        if(empty($roomInfo))throw new PVEException(PVEException::UserNotInPVE);
        if(empty($roomInfo->RaceID))throw new PVEException(PVEException::UserNotInPVE);
        $raceHandler = new RaceHandler($roomInfo->RaceID);

        $raceInfo = $raceHandler->GetInfo();
        $racePlayerID = $raceInfo->racePlayers->{$userInfo->player};
        $racePlayerInfo = (new RacePlayerHandler($racePlayerID))->GetInfo();


        //沒跑完不會有獎牌
        if($racePlayerInfo->status == RaceValue::StatusFinish)
        {
            $medalAmount = match($racePlayerInfo->ranking)
                            {
                                PVEValue::RankingFirst => 3,
                                PVEValue::RankingSecond => 2,
                                PVEValue::RankingThird => 1,
                                default => 0,
                            };
        }
        //已跑到終點才有獎牌數量。
        $levelID = PVEUtility::GetUserProcessingLevelID();
        if($levelID === null) throw new PVEException(PVEException::UserNotInPVE);
        $isLevelClear = !empty($medalAmount);
        //過關，且還未出關卡
        $items = $isLevelClear ?
        PVEUtility::GetLevelReward($levelID,$medalAmount) : null;
        if(!empty($items))
        {       
            UserUtility::AddItems($userInfo->id,$items,ItemValue::CausePVEClearLevel);
            $items = PVEUtility::HandleRewardReturnValue($items);
        }
        $result = new ResultData(ErrorCode::Success);
        $bind = ['levelID' => $levelID, 'Status' => PVEValue::LevelStatusIdle];

        // //獎牌不為0，且獎牌大於原先獎牌數量，才存檔獎牌。
        $bind['medalAmount'] = $medalAmount ?? 0;

        //save會自動判斷獎牌數。
        $userPVEHandler->SaveLevel($bind);

        $result->isCleared = $isLevelClear;
        $result->items = $items;
        $result->medalAmount = $isLevelClear ? $medalAmount : 0;

        // $result->isReach = $racePlayerInfo->status == RaceValue::StatusReach;
        return $result;
    }
}