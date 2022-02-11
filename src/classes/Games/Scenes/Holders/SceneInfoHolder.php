<?php
namespace Games\Scenes\Holders;
/**
 * Description of SceneInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SceneInfoHolder {
    public int $id;
    public string $name;
    public int $readySec;
    public int $env;
    public array $tracks;
    public array $climates;
}
