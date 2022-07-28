<?php

namespace Accessors;

use Consts\ErrorCode;
use CurlHandle;
use Exception;
/**
 * Description of CurlAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class CurlAccessor {
    
    private CurlHandle|false $ch;


    public function __construct(string $url, int|null $port = null) {
        
        $this->ch = curl_init();
        if($this->ch === false) throw new Exception ('Initialize a cURL session on errors', ErrorCode::SystemError);
        
        $options = [CURLOPT_URL => $url];
        if($port !== null) $options[CURLOPT_PORT] = $port;
        
        if(!$this->SetOptions($options)) throw new Exception ('Set a cURL init options on errors', ErrorCode::SystemError);
    }
    
    public function SetOption(int $option, mixed $value) : bool{
        return curl_setopt($this->ch, $option, $value);
    }
    
    public function SetOptions(array $options) : bool{
        return curl_setopt_array($this->ch, $options);
    }
    
    public function ExecOption(int $option, mixed $value) : string|bool{
        if(!$this->SetOption($option, $value)) return false;
        return curl_exec($this->ch);
    }
    
    public function ExecOptions(array $options) : string|bool{
        if(!$this->SetOptions($options)) return false;
        return curl_exec($this->ch);
    }
    
    public function GetInfo(?int $option) : mixed{
        return curl_getinfo($this->ch, $option);
    }
    
    public function GetCode(){
        return curl_errno($this->ch);
    }
    
    public function GetMessage(){
        return curl_error($this->ch);
    }
    
    public function __destruct() {
        curl_close($this->ch);
    }
}
