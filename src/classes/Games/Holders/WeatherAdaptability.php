<?php

namespace Games\Holders;

use Games\Consts\DNAWeather;
/**
 * Description of WeatherAdaptability
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class WeatherAdaptability extends BaseAdaptability {
    
    public int $sunny = 0;
    public int $aurora = 0;
    public int $sandDust = 0;
    
    public function Assign(string $code) : void{
        
        if($code == DNAWeather::Sunny) ++$this->sunny;
        else if($code == DNAWeather::Aurora) ++$this->aurora;
        else if($code == DNAWeather::SandDust) ++$this->sandDust;
    }
}
