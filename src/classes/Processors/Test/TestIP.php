<?php

namespace Processors\Test;

use Processors\BaseProcessor;
use Holders\ResultData;
use Consts\ErrorCode;
/**
 * Description of TestIP
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class TestIP extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $url = "http://curlmyip.org/";
 
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);

        curl_close($ch);
        
        $result = new ResultData(ErrorCode::Success);
        $result->ip = $output;
        
        return $result;
    }
}
