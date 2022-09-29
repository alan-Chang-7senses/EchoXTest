<?php
namespace Games\Users\VIP;

use Accessors\MemcacheAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Users\Holders\UserInfoHolder;
use stdClass;

class VIPPrivilegeData
{
    private static string $key = 'vipPrivilegeData';
    
    public static function GetPowerLimitAndRecoverRate(int $rank) : stdClass
    {
        $row = self::GetVIPInfoByRank($rank);
        $rt = new stdClass();
        $rt->maxAP = $row->PowerLimit;
        $rt->rate = $row->PowerRate;
        return $rt;
    }
    /**
     * @return stdClass 取得該VIP等級整列的資料
     */
    public static function GetVIPInfoByRank(int $rank) : stdClass
    {
        $data = self::GetData();
        return $data[$rank];
    }

    /** 
     * @return stdClass 取得該VIP等級整列的資料
     */
    public static function GetVIPInfoByUserInfoHolder(UserInfoHolder $holder) :stdClass
    {
        return self::GetVIPInfoByDiamond($holder->accumulateDiamond);
    }
    
    /** 
     * @return stdClass 取得該VIP等級整列的資料
     */
    public static function GetVIPInfoByDiamond(int $diamont) : stdClass
    {
        $data = self::GetData();
        $rank = 0;
        foreach((array)$data as $row)
        {
            if($row->Diamond <= $diamont)$rank = $row->VIPRank;            
        }
        return $data[$rank];
    }

    private static function GetData()
    {        
        $mem = MemcacheAccessor::Instance();
        $data = $mem->get(self::$key);
        if($data === false)
        {
            self::SetData();
            $data = $mem->get(self::$key);
        }        
        return (array)json_decode($data);
    }
    private static function SetData()
    {
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $vipTable = $accessor->FromTable('VIPPrivilege')->FetchAll();
        MemcacheAccessor::Instance()->set(self::$key,json_encode($vipTable));
    }
}