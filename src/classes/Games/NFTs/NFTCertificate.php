<?php

namespace Games\NFTs;

use Games\Accessors\AccessorFactory;
use stdClass;

/**
 * Description of NFTCertificate
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class NFTCertificate {
    
    public static function UserExist(string $email) : stdClass|false {
        $accessor = AccessorFactory::Main();
        $row = $accessor->FromTable('Users')->WhereEqual('Email', $email)->Fetch();
        if($row === false) return false;
        return $row;
    }
}
