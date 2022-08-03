<?php

namespace Games\Pools;

use stdClass;
use Accessors\PoolAccessor;
use Games\Accessors\QualifyingSeasonAccessor;

class QualifyingSeasonPool extends PoolAccessor
{

    protected string $keyPrefix = 'QualifyingSession_';
    private static QualifyingSeasonPool $instance;

    public static function Instance() : QualifyingSeasonPool{
        if(empty(self::$instance)) self::$instance = new QualifyingSeasonPool ();
        return self::$instance;
    }
    
	function FromDB(int|string $id):stdClass|false  {
        $pdoAccessor = new QualifyingSeasonAccessor();
        return $pdoAccessor->GetNowSeason();      
	}
}