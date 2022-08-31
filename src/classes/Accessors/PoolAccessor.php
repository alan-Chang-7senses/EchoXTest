<?php

namespace Accessors;

use Consts\ErrorCode;
use Exception;
use stdClass;
/**
 * Description of PoolAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class PoolAccessor {
    
    protected string $keyPrefix = '';

    abstract function FromDB(int|string $id) : stdClass|false;
    
    public function __get(string $id) {
        
        $key = $this->keyPrefix.$id;
        $mem = MemcacheAccessor::Instance();
        
        $data = $mem->get($key);
        if($data !== false) $data = json_decode ($data);
        else{
            $data = $this->FromDB($id);
            $mem->set($key, json_encode($data));
        }
        
        $this->$id = $data;
        return $data;
    }
    
    public function Set(string|int $id, string $property, mixed $value){
        
        $key = $this->keyPrefix.$id;
        $data = $this->$id;
        
        $data->$property = $value;
        MemcacheAccessor::Instance()->set($key, json_encode($data));
        
        $this->$id = $data;
    }
    
    public function Save(string|int $id, string $property, mixed $value){
        
        $key = $this->keyPrefix.$id;
        $data = $this->$id;
        
        $method = 'Save'.ucfirst($property);
        if(method_exists($this, $method)) $data = $this->$method($data, $value);
        else throw new Exception(get_called_class ().' no method '.$method, ErrorCode::SystemError);
        
        MemcacheAccessor::Instance()->set($key, json_encode($data));
        $this->$id = $data;
    }
    
    public function Delete(string $id) : bool{
        $key = $this->keyPrefix.$id;
        $res = MemcacheAccessor::Instance()->delete($key);
        if($res) unset ($this->$id);
        return $res;
    }
}
