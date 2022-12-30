<?php
$t = microtime(true);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PROCESSOR', 'Processors');

spl_autoload_register(function($className){
    $file = __DIR__.DS.'classes'.DS.str_replace('\\', DS, $className.'.php');
    if(file_exists($file)) require $file;
});

set_error_handler(function($errno, $message){
    
    if($errno == E_NOTICE || $errno == E_WARNING){
//        throw new ErrorException($message, $errno, $errno, $file, $line);
        throw new Error($message, $errno);
    }
    
    return false;
});

use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Globals;
use Consts\HTTPCode;
use Consts\Predefined;
use Consts\ResposeType;
use Exceptions\NormalException;
use Games\Accessors\GameLogAccessor;
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
$GLOBALS[Globals::RESULT_RESPOSE_TYPE] = ResposeType::JSON;

if($redirectURL == '/User/SSOAuthURL')    sleep(1);

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


if(getenv(EnvVar::ProcessTiming) == Predefined::ProcessTiming) $result->processTime = microtime(true) - $t;

$resultData = json_encode ($result, JSON_UNESCAPED_UNICODE);
$GLOBALS[Globals::RESULT_PROCESS_DATA] = $resultData;

if($GLOBALS[Globals::RESULT_RESPOSE_TYPE] == ResposeType::JSON){

    header('Content-Type: application/json');
    echo $resultData;
}else if($GLOBALS[Globals::RESULT_RESPOSE_TYPE] == ResposeType::QuickSDKCallback){
    if ($result->error->code == ErrorCode::Success){   
        echo "SUCCESS";
    }else    {
        echo "FAILURE";
    }
} else if ($GLOBALS[Globals::RESULT_RESPOSE_TYPE] == ResposeType::MyCardSDKCallback) {
    if ($result->error->code == ErrorCode::Success) {
        echo $result->response;
    } else {
        echo $resultData;
    }
}else if($GLOBALS[Globals::RESULT_RESPOSE_TYPE] == ResposeType::UniWebView){
    
    $script = empty($result->script) ? '' : '';
    
    $script = '';
    
    if(!empty($result->script)){
        $script = $result->script;
    }else if($result->error->code != ErrorCode::Success){
        $script = 'location.href = "uniwebview://Error?code='.$result->error->code.'&message='. urlencode($result->error->message).'";';
    }
    
    $content = $result->content ?? 'Code: '.$result->error->code.'<br>Message: '.$result->error->message;
    
    echo <<<content
<!DOCTYPE html>
<html>
    <head>
        <title> - {$result->error->message} - </title>
        <meta charset="UTF-8">
        <script>
        window.onload = function(){
            {$script}
        }
        </script>
    </head>
    <body>
        <div>
        {$content}
        </div>
    </body>
</html>
content;
}

if(!isset($obj))  (new GameLogAccessor())->AddBaseProcess();