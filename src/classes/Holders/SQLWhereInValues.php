<?php

namespace Holders;

use Generators\DataGenerator;
/**
 * Description of SQLWhereInValues
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SQLWhereInValues {
    
    public string $values;
    public array $bind;
    
    public function __construct(array $values, array $bind) {
        
        if(empty($values) || empty($bind)){
            
            $key = DataGenerator::RandomString(6).'_0';
            $values = [':'.$key];
            $bind = [$key => ''];
        }
        
        $this->values = '('.implode(',', $values).')';
        $this->bind = $bind;
    }
}
