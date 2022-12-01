<?php

namespace Games\Pools;

use stdClass;
use Accessors\PoolAccessor;
use Games\Accessors\QualifyingSeasonAccessor;

class QualifyingDataPool extends PoolAccessor
{

    protected string $keyPrefix = 'QualifyingData_';
    private static QualifyingDataPool $instance;

    public static function Instance() : QualifyingDataPool{
        if(empty(self::$instance)) self::$instance = new QualifyingDataPool ();
        return self::$instance;
    }
    
	function FromDB(int|string $id):stdClass|false  {
        $pdoAccessor = new QualifyingSeasonAccessor();
        return $pdoAccessor->GetOpenQualifyingDataByLobby($id);
	}
}