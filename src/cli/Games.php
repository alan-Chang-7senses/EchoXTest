<?php
$t = microtime(true);
if($argc <= 1) exit('No argv, must use command format: php Games.php commandName key1=value1 key2=value2 ....'.PHP_EOL.PHP_EOL);

require str_replace('/', DIRECTORY_SEPARATOR, __DIR__. '/../autoload.php');

use Consts\Globals;

$class = 'Games\\CommandWorkers\\'.$argv[1];

$GLOBALS[Globals::TIME_BEGIN] = $t;
try{
    
    $reflectionClass = new ReflectionClass($class);
    if(!$reflectionClass->isInstantiable()) throw new ReflectionException ('Class '.$class.' does not instantiable.');

    $obj = new $class($argc, $argv);
    $result = $obj->Process();
    
} catch (ReflectionException $ex){
    
    $result = [
        'code' => $ex->getCode(),
        'file' => $ex->getFile(),
        'line' => $ex->getLine(),
        'messqge' => $ex->getMessage(),
        'trace' => $ex->getTrace(),
    ];
}

$result = ['Result' => $result, 'ProcessTime' => microtime(true) - $t];

echo json_encode ($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).PHP_EOL.PHP_EOL;