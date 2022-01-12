<?php

namespace Games\Players\Adaptability;

use Games\Consts\DNASun;
/**
 * 太陽適性（棄用）
 * 太陽適性改由角色屬性決定
 * 刪除「角色屬性與部位屬性相同時的加成規則」；且太陽適性沒有評比和其他適性不同，用部位積分去計算不適合。所以改成「角色屬性=太陽適性」
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SunAdaptability extends BaseAdaptability{
    
    public int $normal = 0;
    public int $day = 0;
    public int $night = 0;
    
    public function Assign(string $code) : void {
        
        if($code == DNASun::Normal) ++$this->normal;
        else if($code == DNASun::Day) ++$this->day;
        else if($code == DNASun::Night) ++$this->night;
    }
    
    public function Value() : int {
        
        $value = [DNASun::Normal => $this->normal, DNASun::Day => $this->day, DNASun::Night => $this->night];
        arsort($value);
        reset($value);
        return key($value);
    }
}
