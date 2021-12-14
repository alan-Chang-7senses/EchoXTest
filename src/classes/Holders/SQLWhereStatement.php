<?php

namespace Holders;

/**
 * Description of SQLWhereStatement
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SQLWhereStatement {
    
    public string $statement;
    public array|null $bind;
    
    public function __construct(array $conditions) {
        
        if(empty($conditions)){
            
            $where = ' WHERE 1 ';
            $bind = null;
            
        }else{
            
            $where = [];
            $bind = [];
            foreach($conditions as $condition){
                $where[] = $condition['where'];
                $bind = array_merge($bind, $condition['bind']);
            }
            
            $where = ' WHERE '.implode(' AND ', $where);
        }
        
        $this->statement = $where;
        $this->bind = $bind;
    }
}
