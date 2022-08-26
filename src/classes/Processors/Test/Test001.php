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
        $result->call = $this->CallB();
        
        return $result;
    }

    private function CallA(array $callback){
        return call_user_func($callback, 123);
    }
    
    private Function CallB(){
        return $this->CallA([$this, 'CallC']);
    }
    
    private function CallC($num) {
        return 'CallC '.$num;
    }
}
