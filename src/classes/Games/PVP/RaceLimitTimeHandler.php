<?php

namespace Games\PVP;

use Consts\Globals;
use DateTime;
use Games\Exceptions\RaceException;
use Generators\ConfigGenerator;
use stdClass;

/**
 * Description of PVPLimitTime
 * 
 * @author Liu Shu Ming <mingoliu@7senses.com>
 */
class RaceLimitTimeHandler {

    public function DetectLimitData() : array {

        $limitTimeData = json_decode(ConfigGenerator::Instance()->PvP_B_LimitTimeData ?? '[]');
        if (empty($limitTimeData)) {
            throw new RaceException(RaceException::NoLimitTimeDataConfig);
        }

        $arr = [];
        foreach ($limitTimeData as $data) {
            $tempData = new stdClass;
            $tempData->start = $data->start;
            $tempData->end = $data->end;

            $arr[] = $tempData;
        }

        return $arr;
    }

    public function GetStartLimitTime(array $arr) {

        $nowTime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $firstStartTime = 0;
        foreach ($arr as $data) {
            $startTime = (new DateTime($data->start))->format('U');

            if ($firstStartTime == 0) {
                $firstStartTime = $startTime;
            }

            if ($nowTime < $startTime) {
                return $startTime - $nowTime;
            }
        }

        return 86400 + $firstStartTime - $nowTime;
    }

    public function GetEndLimitTime(array $arr) {

        $nowTime = (int) $GLOBALS[Globals::TIME_BEGIN];
        $firstEndTime = 0;
        foreach ($arr as $data) {
            $endTime = (new DateTime($data->end))->format('U');

            if ($firstEndTime == 0) {
                $firstEndTime = $endTime;
            }

            if ($nowTime < $endTime) {
                return $endTime - $nowTime;
            }
        }

        return 86400 + $firstEndTime - $nowTime;
    }
}