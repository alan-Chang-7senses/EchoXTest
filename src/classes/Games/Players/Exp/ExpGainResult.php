<?php
namespace Games\Players\Exp;

use stdClass;

class ExpGainResult extends stdClass
{
    public int $gainAmount;
    public int $resultLevel;
    public array $bonus;
}
