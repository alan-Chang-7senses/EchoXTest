<?php
namespace Processors\Test;

use Consts\ErrorCode;
use Consts\Sessions;
use Generators\ConfigGenerator;
use Generators\DBInfoGenerator;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of Test001
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Test001 extends BaseProcessor{
    
    protected bool $mustSigned = false;
    
    public function Process(): ResultData {
        
        $obj = new stdClass();
        $obj->test = 123;
        
        $_SESSION[Sessions::UserID] = 1;
        $_SESSION[Sessions::UserInfo] = $obj;
        
        $result = new ResultData(ErrorCode::Success, 'OK');
        $result->test = ConfigGenerator::Instance()->Test001;
        $result->DBInfoMain = DBInfoGenerator::Instance()->KoaMain;
        $result->DBInfoTest = DBInfoGenerator::Instance()->Test;
        $result->GetTest = InputHelper::get('test');
        $result->PostTest = InputHelper::post('test2');
        $result->Session = $_SESSION;
        
        return $result;
    }

}
