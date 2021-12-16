<?php

namespace Games\Holders;

use Games\Consts\WeatherType;
/**
 * Description of WeatherAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class WeatherAdaptability extends BaseAdaptability {
    
    public int $sunny = 0;
    public int $aurora = 0;
    public int $sandDust = 0;
    
    public function Assign(string $code) {
        
        if($code == WeatherType::Sunny) ++$this->sunny;
        else if($code == WeatherType::Aurora) ++$this->aurora;
        else if($code == WeatherType::SandDust) ++$this->sandDust;
    }
}
