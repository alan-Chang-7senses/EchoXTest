<?php

namespace Processors\PVP;

use Consts\ErrorCode;
use Games\Accessors\NoticeAccessor;
use Games\Consts\NoticeValue;
use Games\PVP\RaceLimitTimeHandler;
use Generators\ConfigGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/**
 * Description of PVPLimitTime
 * 
 * @author Liu Shu Ming <mingoliu@7senses.com>
 */
class PVPLimitTime extends BaseProcessor {
    
    public function Process(): ResultData {
        
        $lang = InputHelper::post('lang');

        $result = new ResultData(ErrorCode::Success);

        // PT賽 每日限時 起始與結束時間資訊
        $raceLimitTimeHandler = new RaceLimitTimeHandler();
        $data = $raceLimitTimeHandler->DetectLimitData();

        // PT賽 每日限時 跑馬燈資料
        $accessor = new NoticeAccessor();
        $startMarqueeList = json_decode(ConfigGenerator::Instance()->PvP_B_LimitTimeStartMarqueeID ?? '[]');
        $startRows = $accessor->rowsRaceMarqueeAssoc($lang, $startMarqueeList, NoticeValue::StatusRaceLimitTime);
        $endMarqueeList = json_decode(ConfigGenerator::Instance()->PvP_B_LimitTimeEndMarqueeID ?? '[]');
        $endRows = $accessor->rowsRaceMarqueeAssoc($lang, $endMarqueeList, NoticeValue::StatusRaceLimitTime);
        

        $result->startRemainSeconds = $raceLimitTimeHandler->GetStartLimitTime($data);
        $result->endRemainSeconds = $raceLimitTimeHandler->GetEndLimitTime($data);
        $result->startMarquee = array_column($startRows, 'Content');
        $result->endMarquee = array_column($endRows, 'Content');

        return $result;
    }
}