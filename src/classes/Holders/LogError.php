<?php

namespace Holders;

/**
 * Description of LogError
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LogError {
    
    public string $datetime;
    public string $timezone;
    public string $message;
    public array $httpQuery;
    public string $redirectURL;
    public array $trace;
    public array|null $extra;
}
