<?php
namespace Processors\Test;

use Consts\ErrorCode;
use Holders\ResultData;
use Processors\BaseProcessor;
use Generators\ConfigGenerator;
use Generators\DBInfoGenerator;
/**
 * Description of Test001
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Test001 extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $result = new ResultData(ErrorCode::Success, 'OK');
        $result->test = ConfigGenerator::Instance()->Test001;
        $result->DBInfoMain = DBInfoGenerator::Instance()->KoaMain;
        $result->DBInfoTest = DBInfoGenerator::Instance()->Test;
        return $result;
    }

}
