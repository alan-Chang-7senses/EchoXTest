<?php

namespace Processors\Notices;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class Announcement extends BaseProcessor
{
    public function Process(): ResultData
    {
        $lang = InputHelper::post('lang');
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
                $announcement[] = 
                [
                    'graphURL' => $row->GraphURL,
                    'type' => $row->Type,
                    'title' => $row->Title,
                    'announceTime' => $row->CreateTime,
                    'content' => $row->Content
                ];
            }
        }
        $result->announcement = empty($announcement) ? null : $announcement;
        return $result;
    }
}