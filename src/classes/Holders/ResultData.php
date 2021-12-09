<?php
namespace Holders;

/**
 * Description of ResultData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ResultData {
    
    public int|string $code;
    public string $message;
    public int $time;

    public function __construct(int|string $code, string $message = '') {
        
        $this->code = $code;
        $this->message = $message;
        $this->time = time();
    }
}
