<?php

namespace Games\Players\Holders;

/**
 * Description of PlayerInfoHolder
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PlayerInfoHolder {
    
    /** @var int PlayerID */
    public int $id = 0;
    /** @var string 角色暱稱 */
    public string $name;
    /** @var int 生物屬性 */
    public int $ele;
    /** @var float 同步率 */
    public float $sync;
    /** @var int 角色等級 */
    public int $level;
    /** @var int 當見經驗值 */
    public int $exp;
    /** @var int 升級所需的最大經驗值 */
    public int|null $maxExp; //暫時型別
    /** @var int 生物階級 */
    public int $rank;
    /** @var float 速度 SPD */
    public float $velocity;
    /** @var float 耐力 VIT STA*/
    public float $stamina;
    /** @var float 剩餘體力（耐力） HP */
    public float $hp;
    /** @var float 聰慧 INT */
    public float $intelligent;
    /** @var float 爆發 AGL POW */
    public float $breakOut;
    /** @var float 鬥志 CP FIG */
    public float $will;
    /** @var PlayerDnaHolder 角色 DNA */
    public PlayerDnaHolder $dna;
    /** @var int 環境適性 沙丘 */
    public int $dune;
    /** @var int 環境適性 亞湖 */
    public int $craterLake;
    /** @var int 環境適性 火山 */
    public int $volcano;
    /** @var int 風向適性 順風 */
    public int $tailwind;
    /** @var int 風向適性 側風 */
    public int $crosswind;
    /** @var int 風向適性 逆風 */
    public int $headwind;
    /** @var int 天氣適性 晴天 */
    public int $sunny;
    /** @var int 天氣適性 極光 */
    public int $aurora;
    /** @var int 天氣適性 沙塵 */
    public int $sandDust;
    /** @var int 地形適性 平地 */
    public int $flat;
    /** @var int 地形適性 上坡 */
    public int $upslope;
    /** @var int 地形適性 下坡 */
    public int $downslope;
    /** @var int 太陽適性 */
    public int $sun;
    /** @var int 比賽習慣 */
    public int $habit;
    /** @var int 耐久適性 中距離 */
    public int $mid;
    /** @var int 耐久適性 長距離 */
    public int $long;
    /** @var int 耐久適性 短距離 */
    public int $short;
    /** @var int 插槽數量 */
    public int $slotNumber;
    /** @var array PlayerSkillHolder 角色技能資料 */
    public array $skills;
    /** @var array 技能插槽 位置=>SkillID */
    public array $skillHole;
}
