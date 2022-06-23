<?php

namespace Games\Pools;

use Accessors\PoolAccessor;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\UserAccessor;
use Games\Users\Holders\UserInfoHolder;
use stdClass;
use Games\Accessors\ItemAccessor;
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
        $holder->id = $id;
        $holder->nickname = $row->Nickname;
        $holder->level = $row->Level;
        $holder->exp = $row->Exp;
        $holder->vitality = $row->Vitality;
        $holder->money = $row->Money;
        $holder->ucg = $row->UCG;
        $holder->coin  = $row->Coin;
        $holder->power = $row->Power;
        $holder->diamond = $row->Daimond;
        $holder->player = $row->Player;
        $holder->scene = $row->Scene;
        $holder->race = $row->Race;
        
        $playerAccessor = new PlayerAccessor();
        $rows = $playerAccessor->rowsHolderByUserIDFetchAssoc($id);
        $holder->players = array_column($rows, 'PlayerID');
        
        $itemAccessor = new ItemAccessor();
        $rows = $itemAccessor->rowsUserItemByUserAssoc($id);
        $holder->items = array_column($rows, 'UserItemID');
        
        return $holder;
    }
    
    protected function SaveData(stdClass $data, array $values) : stdClass{
        
        $bind = [];
        foreach($values as $key => $value){
            $bind[ucfirst($key)] = $value;
            $data->$key = $value;
        }
        
        (new UserAccessor())->ModifyUserValuesByID($data->id, $bind);
        
        return $data;
    }
}
