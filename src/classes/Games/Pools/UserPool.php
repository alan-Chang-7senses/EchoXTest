<?php

namespace Games\Pools;

use Accessors\PDOAccessor;
use Accessors\PoolAccessor;
use Consts\EnvVar;
use Consts\Globals;
use Games\Accessors\ItemAccessor;
use Games\Accessors\PlayerAccessor;
use Games\Accessors\UserAccessor;
use Games\Users\Holders\UserInfoHolder;
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
        $holder->id = $id;
        $holder->nickname = $row->Nickname;
        $holder->level = $row->Level;
        $holder->exp = $row->Exp;
        $holder->petaToken = $row->PetaToken;
        $holder->coin  = $row->Coin;
        $holder->power = $row->Power;
        $holder->diamond = $row->Diamond;
        $holder->player = $row->Player;
        $holder->scene = $row->Scene;
        $holder->race = $row->Race;
        $holder->lobby = $row->Lobby;
        $holder->room = $row->Room;
        
        $playerAccessor = new PlayerAccessor();
        $rows = $playerAccessor->rowsHolderByUserIDFetchAssoc($id);
        $holder->players = array_column($rows, 'PlayerID');

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $row = $accessor->FromTable('UserDiamond')->WhereEqual('UserID',$id)->Fetch();
        $holder->accumulateDiamond = $row === false ? 0 : $row->AccumulateDiamond;

        return $holder;
    }
    
    protected function SaveData(stdClass $data, array $values) : stdClass{
        
        $bind = [];
        foreach($values as $key => $value){
            $bind[ucfirst($key)] = $value;
            $data->$key = $value;
        }
        
        if(!isset($bind['updateTime'])) $bind['updateTime'] = $GLOBALS[Globals::TIME_BEGIN];
        
        (new UserAccessor())->ModifyUserValuesByID($data->id, $bind);
        
        return $data;
    }

    protected function SaveAccumulateDiamond(stdClass $data, int $amount)
    {
        $bind = 
        [
            'UserID' => $data->id,
            'AccumulateDiamond' => $amount,
            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
        $data->accumulateDiamond = $amount;        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $accessor->FromTable('UserDiamond')->Add($bind,true);
        return $data;
    }

}
