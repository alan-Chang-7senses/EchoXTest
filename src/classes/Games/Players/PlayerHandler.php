<?php

namespace Games\Players;

use Games\Exceptions\PlayerException;
use Games\Players\Holders\PlayerInfoHolder;
use Games\Pools\PlayerPool;
use Generators\DataGenerator;
/**
 * Description of PlayerHandler
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerHandler {
    
    private PlayerPool $pool;
    private PlayerInfoHolder $info;

    public function __construct(int|string $id) {
        $this->pool = PlayerPool::Instance();
        $info = $this->pool->$id;
        if($info === false) throw new PlayerException(PlayerException::PlayerNotExist, ['[player]' => $id]);
        $this->info = DataGenerator::ConventType($info, 'Games\Players\Holders\PlayerInfoHolder');
    }
    
    public function GetInfo() : PlayerInfoHolder{
        return $this->info;
    }
}
