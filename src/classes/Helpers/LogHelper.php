<?php

namespace Helpers;

use Consts\EnvVar;
use Consts\Folders;
use Consts\Globals;
use Consts\Predefined;
use Generators\DataGenerator;
use Holders\LogError;
use Throwable;
/**
 * Description of LogHelper
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class LogHelper {
    
    private static string $root;
    private static array|null $extra = null;
    
    public static function Save(Throwable $ex) : void{
        
        // if(empty(self::$root)) self::$root = PathHelper::getPath (Folders::Log);
        
        // $isLocal = getenv(EnvVar::SysEnv) == Predefined::SysLocal;
        
        // $log = new LogError();
        
        // if($isLocal){
            
        //     $log->datetime = date(Predefined::FormatDatetime);
        //     $log->timezone = date_default_timezone_get();
        // }
        
        // $headers = getallheaders();
        
        // $payload = DataGenerator::ReceivePayload();
        // $payloadContent = [
        //     'content' => $payload,
        //     'object' => json_decode($payload),
        // ];
        
        // $log->code = $ex->getCode();
        // $log->file = $ex->getFile();
        // $log->line = $ex->getLine();
        // $log->message = $ex->getMessage();
        // $log->authorization = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        // $log->httpQuery = ['_GET' => $GLOBALS['_GET'], '_POST' => $GLOBALS['_POST'], '_PAYLOAD' => $payloadContent];
        // $log->redirectURL = $GLOBALS[Globals::REDIRECT_URL];
        // $log->trace = $ex->getTrace();
        // $log->extra = self::$extra;
        // $log->beginTime = $GLOBALS[Globals::TIME_BEGIN];
        // $log->processTime = microtime(true) - $GLOBALS[Globals::TIME_BEGIN];
        // self::$extra = null;
        
        // if($isLocal) error_log(json_encode($log, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL.PHP_EOL, 3, self::$root.date('Ymd').'.log');
        
        // error_log(json_encode($log, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 0);
        
        // $GLOBALS[Globals::RESULT_PROCESS_MESSAGE] = $log->message;
    }
    
    public static function Extra(string $tag, array $extra) : void{
        
        self::$extra[$tag] = $extra;
    }
}
