<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\UserAccessor;
use Games\Users\Holders\UserInfoHolder;
use Generators\DataGenerator;
use stdClass;
/**
 * Description of UserPool
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserPool extends PoolAccessor{
    
    private static UserPool $instance;
    
    public static function Instance() : UserPool {
        if(empty(self::$instance)) self::$instance = new UserPool();
        return self::$instance;
    }
    
    protected string $keyPrefix = 'user_';

    public function FromDB(int|string $id): stdClass|false {
        
        $userAccessor = new UserAccessor();
        $row = $userAccessor->rowUserByID($id);
        if(empty($row)) return false;
        
        $holder = new UserInfoHolder();
        $holder->nickname = $row->Nickname;
        $holder->level = $row->Level;
        $holder->exp = $row->Exp;
        $holder->vitality = $row->Vitality;
        $holder->money = $row->Money;
        $holder->scene = 1;
        $holder->player = $row->Player;
        
        $playerAccessor = new PlayerAccessor();
        $rows = $playerAccessor->rowsHolderByUserIDFetchAssoc($id);
        $holder->players = array_column($rows, 'PlayerID');
        
        return DataGenerator::ConventType($holder, 'stdClass');
    }
}
