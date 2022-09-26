<?php
namespace Processors\Player;

use Consts\ErrorCode;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of AvatarParts
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class AvatarParts extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $id = InputHelper::post('id');
        
        $info = (new PlayerHandler($id))->GetInfo();
        $parts = PlayerUtility::PartCodes($info);
        
        $result = new ResultData(ErrorCode::Success);
        $result->parts = [
            'id' => $info->id,
            'head' => $parts->head,
            'body' => $parts->body,
            'hand' => $parts->hand,
            'leg' => $parts->leg,
            'back' => $parts->back,
            'hat' => $parts->hat,
        ];
        
        return $result;
    }
}
