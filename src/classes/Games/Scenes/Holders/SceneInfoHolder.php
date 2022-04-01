<?php
namespace Games\Scenes\Holders;

use stdClass;
/**
 * Description of SceneInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SceneInfoHolder extends stdClass {
    public int $id;
    public string $name;
    public int $readySec;
    public int $env;
    public array $climates;
}
