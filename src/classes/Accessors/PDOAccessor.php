<?php

namespace Accessors;

use Generators\DataGenerator;
use Generators\PDOHGenerator;
use Helpers\LogHelper;
use Helpers\PDOHelper;
use Holders\SQLWhereInValues;
use Holders\SQLWhereStatement;
use Exception;
use PDO;
/**
 * Description of BaseAccessors
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PDOAccessor {
    
    private PDOHelper $ph;
    private string $table;
    
    private array $conditions = [];

    private string $selectExpr = '*';
    private string|null $groupBy = null;
    private string|null $orderBy = null;
    private string|null $limit = null;
    private int $fetchStyle = PDO::FETCH_OBJ;
    private string $prepareName = PDOHelper::PREPARE_DEFAULT;
    private bool $ignore = false;
    private bool $forUpdate = false;
    
    public function __construct(string $label) {
        $this->ph = PDOHGenerator::Instance()->$label;
    }
    
    public function PrepareName(string $name) : PDOAccessor{
        
        $this->prepareName = $name;
        return $this;
    }

    public function Fetch() : mixed{
        
        return $this->_Fetch('fetch');
    }
    
    public function FetchAll() : array|false{
        
        return $this->_Fetch('fetchAll');
    }
    
    private function _Fetch(string $func) {
        
        $where = new SQLWhereStatement($this->conditions);
        
        $statement = 'SELECT '. $this->selectExpr.' FROM '. $this->table.$where->statement;
        if($this->groupBy !== null) $statement .= $this->groupBy;
        if($this->orderBy !== null) $statement .= $this->orderBy;
        if($this->limit !== null) $statement .= $this->limit;
        if($this->forUpdate) {
            $statement .= ' FOR UPDATE';
            $this->forUpdate = false;
        }
        
        $this->executeBind($statement, $where->bind);
        return $this->ph->$func($this->fetchStyle);
    }
    
    public function Add(array $bind, bool $replace = false) : bool{
        $columns = array_keys($bind);
        $values = array_map(function($val){ return ':'.$val; }, $columns);
        $statement = (!$replace ? 'INSERT' : 'REPLACE').' INTO '.$this->table.' ('. implode(',', $columns).') VALUES ('. implode(',', $values).')';
        return $this->executeBind($statement, $bind);
    }
    

    public function AddAll(array $rows, bool $replace = false) : bool{
        
        $values = [];
        $bind = [];
        $count = count($rows);
        
        for($i = 0 ; $i < $count ; ++$i){
            
            $names = [];
            foreach($rows[$i] as $key => $value){
                
                $name = $key.'_'.$i;
                $names[] = ':'.$name;
                $bind[$name] = $value;
            }
            
            $values[] = '('. implode(',', $names).')';
        }
        $statement = (!$replace ? 'INSERT'.($this->ignore ? ' IGNORE' : '') : 'REPLACE').' INTO '.$this->table.' ('.implode(',', array_keys($rows[0])).') VALUES '. implode(',', $values);
        return $this->executeBind($statement, $bind);
    }
    
    public function Modify(array $bind) : bool {
        
        $set = array_map(function($key){ return $key.' = :'.$key; }, array_keys($bind));
        $where = new SQLWhereStatement($this->conditions);
        $statement = 'UPDATE '.$this->table.' SET '.implode(',', $set).' '.$where->statement;
        $bind = array_merge($bind, $where->bind);
        return $this->executeBind($statement, $bind);
    }
    
    public function Delete() : bool {
        
        $where = new SQLWhereStatement($this->conditions);
        
        $statement = 'DELETE FROM '. $this->table.$where->statement;
        if($this->limit !== null) $statement .= $this->limit;
        
        return $this->executeBind($statement, $where->bind);
    }
    
    public function Truncate() : bool{
        
        $statement = 'TRUNCATE '. $this->table;
        return $this->executeBind($statement, []);
    }
    
    public function CallProcedure(string $name, array $bind) : array{
        
        $params = array_map(function($val){ return ':'.$val; }, array_keys($bind));
        $statement = 'CALL '.$name.'('. implode(', ', $params).')';
        
        $this->LogExtra($statement, $bind);
        $this->ph->prepare($statement, $name);
        $this->ph->execute($bind);
        $res = $this->ph->fetchAll($this->fetchStyle);
        $this->ph->closeCursor($name);
        return $res;
    }

    public function SelectExpr(string $expr): PDOAccessor{
        $this->selectExpr = $expr;
        return $this;
    }
    
    public function FromTable(string $value) : PDOAccessor{
        $this->table = $value;
        return $this;
    }
    
    public function FromTableJoinUsing(string $tableA, string $tableB, string $joinType, string $usingColumn) : PDOAccessor{
        $this->table = $tableA.' '.$joinType.' JOIN '.$tableB.' USING(`'.$usingColumn.'`)';
        return $this;
    }
    
    public function FromTableJoinUsingNext(string $table, string $type, string $column) : PDOAccessor{
        $this->table .= ' '.$type.' JOIN '.$table.' USING('.$column.')';
        return $this;
    }

    public function FromTableJoinOn(string $tableA, string $tableB, string $joinType, string $columnA, string $columnB) : PDOAccessor{
        $this->table = $tableA.' AS a '.$joinType.' JOIN '.$tableB.' AS b ON a.`'.$columnA.'` = b.`'.$columnB.'`';
        return $this;
    }

    private function bindCondition(string $condition, array $bind) : PDOAccessor{
        $this->conditions[] = ['where' => $condition, 'bind' => $bind];
        return $this;
    }
    
    public function WhereEqual(string $column, mixed $value, string|null $bindName = null) : PDOAccessor{
        return $this->WhereCondition($column, '=', $value, $bindName);
    }
    
    public function WhereGreater(string $column, mixed $value, string|null $bindName = null) : PDOAccessor{
        return $this->WhereCondition($column, '>', $value, $bindName);
    }
    
    public function WhereLess(string $column, mixed $value, string|null $bindName = null) : PDOAccessor{
        return $this->WhereCondition($column, '<', $value, $bindName);
    }
    
    public function WhereCondition(string $column, string $operator , int|string|float $value, string|null $bindName = null) : PDOAccessor{
        $bindName = $bindName ?? $column;
        return $this->bindCondition($column.' '.$operator.' :'.$bindName, [$bindName => $value]);
    }

    public function WhereIn(string $column, array $values, string|null $bindName = null) : PDOAccessor{
        $values = $this->valuesForWhereIn($values, ($bindName ?? $column).'_');
        return $this->bindCondition($column.' IN '.$values->values, $values->bind);
    }
    
    public function WhereLikeAfter(string $column, string $value, string|null $bindName = null) : PDOAccessor{
        $bindName = $bindName ?? $column;
        return $this->bindCondition($column.' LIKE :'.$bindName, [$bindName => $value.'%']);
    }
    
    public function ClearCondition() : PDOAccessor{
        $this->conditions = [];
        return $this;
    }
    
    public function GroupBy(array|string $columns) : PDOAccessor {
        $this->groupBy = ' GROUP BY '.(is_array($columns) ? implode(',', $columns) : $columns);
        return $this;
    }
    
    public function ClearGroupBy() : PDOAccessor{
        $this->groupBy = null;
        return $this;
    }
    
    public function OrderBy(array|string $columns, string $sort = 'ASC') : PDOAccessor{
        $orderBy = (is_array($columns) ? implode(',', $columns) : $columns).' '.$sort;
        $this->orderBy .= $this->orderBy === null ? ' ORDER BY '.$orderBy.' ' : ', '.$orderBy.' ';
        return $this;
    }
    
    public function ClearOrderBy() : PDOAccessor{
        $this->orderBy = null;
        return $this;
    }
    
    public function Limit(int $count, int|null $offset = null) : PDOAccessor{
        $this->limit = ' LIMIT '.($offset === null ? $count : $offset.', '.$count);
        return $this;
    }
    
    public function ClearLimt() : PDOAccessor{
        $this->limit = null;
        return $this;
    }
    
    public function ClearAll() : PDOAccessor{
        $this->conditions = [];
        $this->groupBy = null;
        $this->orderBy = null;
        $this->limit = null;
        $this->prepareName = PDOHelper::PREPARE_DEFAULT;
        $this->selectExpr = '*';
        $this->fetchStyle = PDO::FETCH_OBJ;
        return $this;
    }

    public function FetchStyle(int $style) : PDOAccessor{
        $this->fetchStyle = $style;
        return $this;
    }
    
    public function FetchStyleAssoc() : PDOAccessor{
        $this->fetchStyle = PDO::FETCH_ASSOC;
        return $this;
    }

    public function LastInsertID() : string{
        return $this->ph->getLastInsertId();
    }
    
    public function Ignore(bool $ignore) : PDOAccessor{
        $this->ignore = $ignore;
        return $this;
    }
    
    public function ForUpdate() : PDOAccessor {
        $this->forUpdate = true;
        return $this;
    }

    public function executeBind(string $statement, array|null $bind) : bool{
        $this->LogExtra($statement, $bind);
        $this->ph->prepare($statement, $this->prepareName);
        return $this->ph->execute($bind);
    }
    
    public function executeBindFetch(string $statement, array $bind) : mixed{
        $this->executeBind($statement, $bind);
        return $this->ph->fetch($this->fetchStyle);
    }
    
    public function executeBindFetchAll(string $statement, array $bind) : array|false{
        $this->executeBind($statement, $bind);
        return $this->ph->fetchAll($this->fetchStyle);
    }
    
    public function valuesForWhereIn(array $items , string $label = 'Value_') : SQLWhereInValues{
        
        $values = [];
        $bind = [];
        $count = count($items);
        for($i = 0 ; $i < $count ; ++$i){
            
            $key = $label.$i;
            $values[] = ':'.$key;
            $bind[$key] = $items[$i];
        }
        
        return new SQLWhereInValues($values, $bind);
    }
    
    public function Transaction($func){
        
        try{
            
            $this->ph->beginTransaction();
            $result = $func();
            $this->ph->commitTransaction();
            
        } catch (Exception $ex) {

            $this->ph->rollbackTransaction();
            
            if(is_subclass_of($ex, 'Exceptions\NormalException')){
                $normalException = get_class($ex);
                throw new $normalException($ex->getCode(), $ex->GetBind(), $ex);
            }else throw new Exception($ex->getMessage(), $ex->getCode(), $ex);
        }
        
        return $result;
    }
    
    private function LogExtra(string $statement, array|null $bind){
        LogHelper::Extra('SQL_'.DataGenerator::RandomString(3), ['statement' => $statement, 'bind' => $bind]);
    }
}
