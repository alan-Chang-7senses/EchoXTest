<?php

namespace Games\Players\Holders;

use stdClass;

class PlayerBaseInfoHolder extends stdClass
{
    public function __construct(int $level, int $strengthLevel, int $strength, int $agility, int $constitution, int $dexterity)
    {
        $this->level = $level;
        $this->strengthLevel = $strengthLevel;
        $this->strength = $strength;
        $this->agility = $agility;
        $this->constitution = $constitution;
        $this->dexterity = $dexterity;
    }
    /**角色等級 */
    public int $level;
    /**角色數值標記 */
    public int $strengthLevel;
    /**角色力量 */
    public int $strength;
    /**角色敏捷 */
    public int $agility;
    /**角色耐力 */
    public int $constitution;
    /**角色靈巧 */
    public  int $dexterity;
}