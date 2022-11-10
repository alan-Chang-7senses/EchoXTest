<?php

namespace Games\Accessors;

use Consts\Globals;
use Consts\Sessions;

class NFTLogAccessor extends BaseAccessor
{
    private array $createBinds = [];
    private array $transferBinds = [];
    const AddLimitOneTime = 10;
    

    public function AddCreatePlayerBind(int $playerID, string $metadataURL)
    {
        $this->createBinds[] = 
        [
            'PlayerID' => $playerID,
            'UserID' => $_SESSION[Sessions::UserID],
            'MetadataURL' => $metadataURL,
            'LogTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
    }


    public function AddTransferBind(int $newOnwer, int $oldOwner, int $playerID)
    {
        $this->transferBinds[] = 
        [
            'NewOnwerUserID' => $newOnwer,
            'OldOnwerUserID' => $oldOwner,
            'PlayerID' => $playerID,
            'LogTime' => $GLOBALS[Globals::TIME_BEGIN],
        ];
    }
    

    public function AddAll()
    {
        if(!empty($this->transferBinds))
        {
            // $this->LogAccessor()->FromTable('NFTOwnershipTransfer')->AddAll($this->transferBinds);
            $accessor = AccessorFactory::Log();
            $accessor->prepareName('PrepareNFTOwnershipTransfer');
            $accessor->FromTable('NFTOwnershipTransfer');
            $this->AddLogsByPart($accessor,$this->transferBinds);
        }
        
        if(!empty($this->createBinds))
        {
            $accessor = AccessorFactory::Log();
            $accessor->prepareName('PrepareNFTCreatePlayer');
            $accessor->FromTable('NFTCreatePlayer');   
            $this->AddLogsByPart($accessor,$this->createBinds);
        }
    }
    private function AddLogsByPart($accessor, array $binds, int $index = 0)
    {
        if($index >= count($binds))return;
        $max = count($binds) - $index >= self::AddLimitOneTime ? 
                        self::AddLimitOneTime : count($binds) - $index;
        $bindTemps = [];
        $indexTemp = $index;
        for($i = 0; $i < $max; $i++)
        {
            $bindTemps[] = $binds[$indexTemp + $i];
            $index++;
        }
        // $this->LogAccessor()->FromTable($tableName)->AddAll($bindTemps);
        $accessor->AddAll($bindTemps);
        $this->AddLogsByPart($accessor,$binds,$index);
    }
}