<?php

namespace Games\Players\Adaptability;

/**
 * Description of BaseAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseAdaptability {
    
    abstract function Assign(string $code) : void;
}
