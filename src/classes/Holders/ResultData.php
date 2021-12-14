<?php
namespace Holders;

use stdClass;
/**
 * Description of ResultData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ResultData {
    
    public stdClass $error;
    
    public function __construct(int|string $code, string $message = '') {
    
        $this->error = new stdClass();
        $this->error->code = $code;
        $this->error->message = $message;
    }
}
