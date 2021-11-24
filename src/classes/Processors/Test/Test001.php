<?php
namespace Processors\Test;

use Consts\ErrorCode;
use Generators\ConfigGenerator;
use Generators\DBInfoGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
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
        $result->GetTest = InputHelper::get('test');
        $result->PostTest = InputHelper::post('test2');
        
        return $result;
    }

}
