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
        
        $result = new ResultData(ErrorCode::Success);
        $result->parts = [
            'id' => $info->id,
            'head' => PlayerUtility::PartCodeByDNA($info->dna->head),
            'body' => PlayerUtility::PartCodeByDNA($info->dna->body),
            'hand' => PlayerUtility::PartCodeByDNA($info->dna->hand),
            'leg' => PlayerUtility::PartCodeByDNA($info->dna->leg),
            'back' => PlayerUtility::PartCodeByDNA($info->dna->back),
            'hat' => PlayerUtility::PartCodeByDNA($info->dna->hat),
        ];
        
        return $result;
    }
}
