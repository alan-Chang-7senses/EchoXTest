<?php

namespace Games\Players\Holders;

use stdClass;

class PlayerBaseValueInfoHolder extends stdClass {
    

    /**角色力量 */
    public int $strength;
    /**角色敏捷 */
    public int $agility;
    /**角色耐力 */
    public int $constitution;
    /**角色靈巧 */
    public  int $dexterity;    
}
