<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\UserAccessor;
use Games\Users\Holders\UserInfoHolder;
use stdClass;
use Games\Accessors\ItemAccessor;
use Games\Users\Holders\FreePetaProccessHolder;

/**
 * Description of UserPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FreePetaProccessPool extends PoolAccessor{
    
    private static FreePetaProccessPool $instance;
    
    public static function Instance() : FreePetaProccessPool {
        if(empty(self::$instance)) self::$instance = new FreePetaProccessPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'freePetaProccess_';

    public function FromDB(int|string $id): stdClass|false {        

        $pdo = new PDOAccessor(EnvVar::DBMain);
        $row = $pdo->FromTable("FreePetaProccess")
                   ->WhereEqual("UserID",$id)
                   ->Fetch();
        if(empty($row))return false;
        
        $holder = new FreePetaProccessHolder();
        $holder->id = $id;
        $holder->proccess = $row->Proccess;
        $holder->freePetaIDs = $row->FreePetaIDs;        
        
        return $holder;
    }
    
    protected function SaveData(stdClass $data, array $values) : stdClass{
        
        $bind = [];
        foreach($values as $key => $value){
            $bind[ucfirst($key)] = $value;
            $data->$key = $value;
        }
        
        (new UserAccessor())->ModifyUserValuesByID($data->id, $bind);
        
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $pdo->FromTable("FreePetaProccess")
                   ->WhereEqual("UserID",$data->id)
                   ->Modify($bind);
        return $data;
    }
}
