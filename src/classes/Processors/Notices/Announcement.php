<?php

namespace Processors\Notices;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Generators\DataGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class Announcement extends BaseProcessor
{
    public function Process(): ResultData
    {
        $lang = InputHelper::post('lang');
        $timezone = InputHelper::post('timezone');

        $currentTime = $GLOBALS[Globals::TIME_BEGIN];
        
        $accessor = AccessorFactory::Static();
        $rows = $accessor->FromTable('Announcement')
                         ->WhereEqual('Lang',$lang)
                         ->WhereCondition('PublishTime','<=',$currentTime)
                         ->WhereGreater('FinishTime',$currentTime)
                         ->OrderBy('PublishTime','DESC')
                         ->FetchAll();
        
        $result = new ResultData(ErrorCode::Success);
        $announcement = [];
        if($rows !== false)
        {
            foreach($rows as $row)
            {
                $announceTime = DataGenerator::TimestringByTimezone($row->CreateTime, $timezone, 'Y-m-d');
                $announcement[] = 
                [
                    'graphURL' => $row->GraphURL,
                    'type' => $row->Type,
                    'title' => $row->Title,
                    'announceTime' => $announceTime,
                    'content' => $row->Content
                ];
            }
        }
        $result->announcement = empty($announcement) ? null : $announcement;
        return $result;
    }
}