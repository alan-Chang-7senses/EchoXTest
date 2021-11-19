<?php
namespace Holders;

/**
 * Description of ResultData
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ResultData {
    
    public int|string $code;
    public string|null $message;
    public string $time;

    public function __construct(int|string $code, string|null $message = '') {
        
        $this->code = $code;
        $this->message = $message;
        $this->time = time();
    }
}
