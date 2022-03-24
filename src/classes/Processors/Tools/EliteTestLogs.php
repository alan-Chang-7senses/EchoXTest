<?php

namespace Processors\Tools;

use Consts\ErrorCode;
use Games\Accessors\EliteTestAccessor;
use Holders\ResultData;
/**
 * Description of EliteTestLogs
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class EliteTestLogs extends BaseTools{
    
    public function Process(): ResultData {
        
        $accessor = new EliteTestAccessor();
        
        $this->ShowList('UserRanking', $accessor->rowsUserRanking());
        $this->ShowList('SkillTotal', $accessor->rowsSkillTotal());
        $this->ShowList('TotalRaceBeginHours', $accessor->rowsTotalRaceBeginHours());
        $this->ShowList('FastestFinishTime', $accessor->rowsFastestFinishTime(true));
        $this->ShowList('SlowestFinishTime', $accessor->rowsFastestFinishTime(false));
        $this->ShowList('TotalLoginHours', $accessor->rowsTotalLoginHours());
        $this->ShowList('MostBeginRace', $accessor->rowsMostUserRace('BeginAmount'));
        $this->ShowList('MostFinishRace', $accessor->rowsMostUserRace('FinishAmount'));
        $this->ShowList('MostWinRace', $accessor->rowsMostUserRace('Win'));
        
        $row = $accessor->rowAvgUserRace();
        echo 'AvgUserRace'.PHP_EOL. implode(chr(9), $row).PHP_EOL.PHP_EOL;
        
        return new ResultData(ErrorCode::Success);
    }
    
    private function ShowList(string $label, array $rows):void{
        $data = [];
        foreach ($rows as $row) $data[] = implode(chr(9), (array)$row);
        echo $label.PHP_EOL. implode(PHP_EOL, $data).PHP_EOL.PHP_EOL;
    }
}
