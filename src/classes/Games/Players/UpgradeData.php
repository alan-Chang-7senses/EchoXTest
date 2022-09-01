<?php

namespace Games\Players;

use Accessors\MemcacheAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use PDO;
use stdClass;

class UpgradeData
{
    private static string $keyPrefix = 'upgradeData_';
    

    public static function GetData()
    {
        $mem = MemcacheAccessor::Instance();
        $data = $mem->get(self::$keyPrefix);
        if($data === false)
        {
            self::Set();
            $data = $mem->get(self::$keyPrefix);
        }        
        return json_decode($data);
    }
    
    private static function Set()
    { 
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $rowsUpgradeBonusTable = $accessor->FromTable('UpgradeBonus')
                                            ->FetchStyle(PDO::FETCH_ASSOC)
                                            ->FetchAll();
        $rowsUpgradeModeTable = $accessor->FromTable('UpgradeMode')
                                            ->FetchStyle(PDO::FETCH_ASSOC)
                                            ->FetchAll();
        $rowsItemCharge = $accessor->FromTableJoinUsing('ItemCharge','ItemInfo','INNER','ItemID')
                                            ->FetchStyle(PDO::FETCH_ASSOC)
                                            ->FetchAll();
        $itemChargeTable = [];
        foreach($rowsItemCharge as $row)
        {
            $itemChargeTable[$row['ItemID']] = $row;
        }
        $upgradeBonusTable = [];
        foreach($rowsUpgradeBonusTable as $row)
        {
            $upgradeBonusTable[$row['BonusID']] = $row;
        }
        $upgradeModeTable = [];
        foreach($rowsUpgradeModeTable as $row)
        {
            $upgradeModeTable[$row['Mode']] = $row;
        }
        $data = new stdClass();
        $data->upgradeBonusTable = $upgradeBonusTable;
        $data->upgradeModeTable = $upgradeModeTable;
        $data->itemChargeTable = $itemChargeTable;
        MemcacheAccessor::Instance()->set(self::$keyPrefix, json_encode($data));        
    }
}