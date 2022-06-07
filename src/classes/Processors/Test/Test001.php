<?php
namespace Processors\Test;

use Consts\ErrorCode;
use Generators\ConfigGenerator;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Test001
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Test001 extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $config = ConfigGenerator::Instance();
        
        $result = new ResultData(ErrorCode::Success, 'OK');
        $result->AmountRacePlayerMax = $config->AmountRacePlayerMax;
        $result->TimelimitElitetestRace = $config->TimelimitElitetestRace;
        
        return $result;
    }

}
