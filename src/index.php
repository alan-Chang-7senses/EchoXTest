<?php
$t = microtime(true);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PROCESSOR', 'Processors');

spl_autoload_register(function($className){
    $file = __DIR__.DS.'classes'.DS.str_replace('\\', DS, $className.'.php');
    if(file_exists($file)) require $file;
});

use Consts\ErrorCode;
use Consts\Globals;
use Consts\HTTPCode;
use Exceptions\NormalException;
use Generators\ConfigGenerator;
use Handlers\SessionToDBHandler;
use Helpers\LogHelper;
use Holders\ResultData;

session_set_save_handler(new SessionToDBHandler());

$redirectURL = filter_input(INPUT_SERVER, 'REDIRECT_URL');
$path = substr($redirectURL, -1) == '/' ? substr($redirectURL, 0 , -1) : $redirectURL;
$class = ROOT_PROCESSOR.str_replace('/', '\\', $path);

$GLOBALS[Globals::REDIRECT_URL] = $redirectURL;
$GLOBALS[Globals::ROOT] = __DIR__.DS;
$GLOBALS[Globals::TIME_BEGIN] = $t;
$GLOBALS[Globals::RESULT_PROCESS] = false;
$GLOBALS[Globals::RESULT_PROCESS_MESSAGE] = null;

session_start();
//session_destroy();

try{
    
    $reflectionClass = new ReflectionClass($class);
    if(!$reflectionClass->isInstantiable()) throw new ReflectionException ('Class '.$class.' does not instantiable.');

    $obj = new $class;
    $result = $obj->Process();
    $GLOBALS[Globals::RESULT_PROCESS] = true;
    
} catch (ReflectionException $ex){
    
    http_response_code(HTTPCode::NotFound);
    $result = new ResultData(ErrorCode::Unknown, 'Unknown request or it does not exist');
    LogHelper::Save($ex);
    
} catch (PDOException $ex){
    
    http_response_code(HTTPCode::BadRequest);
    $result = new ResultData(ErrorCode::SQLError, 'Data access error');
    LogHelper::Save($ex);
    
}catch (NormalException $ex){
    
    if($ex->getCode() == ErrorCode::Maintain) http_response_code (HTTPCode::ServiceUnavailable);
    $result = new ResultData($ex->getCode(), $ex->getMessage());
    LogHelper::Save ($ex);
    
}catch (Exception $ex) {
    
    http_response_code(HTTPCode::BadRequest);
    $result = new ResultData($ex->getCode(), $ex->getMessage());
    LogHelper::Save($ex);
    
} catch (Throwable $ex){
    
    http_response_code(HTTPCode::InternalServerError);
    $result = new ResultData(ErrorCode::Unknown, 'The system has encountered a situation it doesn\'t know how to handle.');
    LogHelper::Save($ex);
}

header('Content-Type: application/json');

if(ConfigGenerator::Instance()->EnabledProcessTime == 1) $result->processTime = microtime(true) - $t;
$resultData = json_encode ($result, JSON_UNESCAPED_UNICODE);
$GLOBALS[Globals::RESULT_PROCESS_DATA] = $resultData;
echo $resultData;
