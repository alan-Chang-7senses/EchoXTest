<?php

namespace Games\NFTs;

use Consts\EnvVar;
/**
 * Description of NFTUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTUtility {
    
    public static function Authorization() {
        return 'Basic '.base64_encode(getenv(EnvVar::NFTClientID).':'.getenv(EnvVar::NFTAPISecret));
    }
    
    public static function EventPayload(){
        return json_decode(file_get_contents('php://input'));
    }
}
