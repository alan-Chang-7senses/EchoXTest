<?php

namespace Games\NFTs;
use Accessors\CurlAccessor;
use Consts\EnvVar;
use Games\Accessors\AccessorFactory;
use Games\Consts\NFTQueryValue;
use Games\Exceptions\NFTException;
use Games\NFTs\Holders\NFTUserHolder;
use Helpers\LogHelper;
use stdClass;
use Games\Consts\PlayerValue;
use Games\Pools\PlayerPool;
use Games\Pools\UserPool;
use Games\Users\UserHandler;

class NFTRefresher
{
    private NFTUserHolder $userHolder;
    
    public function __construct(int $userID) {
        
        $row = AccessorFactory::Main()->FromTable('Users')->WhereEqual('UserID', $userID)->Fetch();
        $this->userHolder = new NFTUserHolder($row->UserID, $row->Email, $row->Player);
    }

    private function QueryNFTs(string $email) : stdClass|false{
        $curl = new CurlAccessor(getenv(EnvVar::NFTUri).'/queries/nfts/'.$email.'?'. http_build_query(['nftContractSymbols' => NFTQueryValue::ContractSymbols]));
        $curlReturn = $curl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_HTTPHEADER => ['Authorization: '. self::Authorization()]
            CURLOPT_HTTPHEADER => ['Authorization: '. NFTUtility::Authorization()]
        ]);
        
        $nfts = json_decode($curlReturn);
        
        $status = NFTQueryValue::StatusSuccess;
        if($nfts === null) $status = NFTQueryValue::StatusFailure;
        else if(isset($nfts->errors[0])){
            if($nfts->errors[0]->message == 'IN MAINTENANCE') $status = NFTQueryValue::StatusMaintenance;
            else if($nfts->errors[0]->message == 'invalidCredentials') $status = NFTQueryValue::StatusInvalidCredentials;
            else $status = NFTQueryValue::StatusUnknow;
        }else if(!empty($nfts->error)){
            if($nfts->error == 'emailNotFound') $status = NFTQueryValue::StatusEmailNotFound;
            else $status = NFTQueryValue::StatusUnknow;
        }
        
        LogHelper::Extra('NFTQuery', [
            'status' => $status,
            'nfts' => $nfts
        ]);
        
        if($status != NFTQueryValue::StatusSuccess){
            LogHelper::Save(new NFTException(NFTException::QueryNFTFailure));
            return false;
        }
        
        return $nfts->result;
    }
    
    private function QueryMetadata(string $metadataURL) : mixed{
        
        $curl = new CurlAccessor($metadataURL);
        $curlReturn = $curl->ExecOption(CURLOPT_RETURNTRANSFER, true);
        $metadata = json_decode($curlReturn);
        return $metadata ?? false;
    }

    public function RefreshAll()
    {     
        $nftsResult = $this->QueryNFTs($this->userHolder->email);
        if($nftsResult === false) return;
        
        $accessor = AccessorFactory::Main();
        
        $metadataURLs = [];
        $playerIDs = [];
        foreach($nftsResult as $sympol => $nfts){
            
            if(!in_array($sympol, NFTQueryValue::ContractSymbols))
                continue;
            
            foreach($nfts as $nft){
                
                if($nft->stage != NFTQueryValue::StageAdult)
                    continue;
                
                $playerID = NFTUtility::ToPlayerID($nft->nftId);
                $playerIDs[] = $playerID;
                $metadataURLs[$playerID] = $nft->metadataUrl;
            }
        }
        
        $rowsExistPlayer = $accessor->FromTable('PlayerHolder')
                            ->WhereIn('PlayerID', $playerIDs)
                            ->FetchStyleAssoc()->FetchAll();
        $existPlayerIDs = array_column($rowsExistPlayer, 'PlayerID');
        $rowsUserHoldPlayers = $accessor->ClearCondition()->FromTable('PlayerHolder')
                                        ->WhereEqual('UserID', $this->userHolder->userID)
                                        ->WhereGreater('PlayerID',PlayerValue::freePetaMaxPlayerID)
                                        ->FetchStyleAssoc()
                                        ->FetchAll();

                                                
        $userHoldPlayers = array_column($rowsUserHoldPlayers, 'PlayerID');
        
        $notExistPlayerIDs = []; // 不存在DB的角色
        $changeholdPlayerIDs = []; // 存在DB，但還不屬於該用戶的角色
        foreach($playerIDs as $playerID)
        {
            if(!in_array($playerID,$existPlayerIDs))
            {
                $notExistPlayerIDs[] = $playerID;
                continue;
            }
            if(!in_array($playerID,$userHoldPlayers))$changeholdPlayerIDs[] = $playerID;
        }
        $nftFactory = new NFTFactory($this->userHolder->userID);
        foreach($notExistPlayerIDs as $playerID){
            
            $metadata = $this->QueryMetadata($metadataURLs[$playerID]);
            
            if($metadata === false)
                continue;
            if(!in_array($metadata->properties->universe,NFTQueryValue::ContractUniverse)
               || !in_array($metadata->properties->planet,NFTQueryValue::ContractPlanet))
                continue;

            $nftFactory->CreatePlayer($playerID, $metadata);
        }

        
        $noLongerHolds = [];//即將不屬於用戶的角色。
        $currentPlayer = (new UserHandler($this->userHolder->userID))->GetInfo()->player;
        foreach($userHoldPlayers as $userHoldPlayer)
        {
            if(!in_array($userHoldPlayer,$playerIDs))
            {
                $noLongerHolds[] = $userHoldPlayer;
                if($userHoldPlayer == $currentPlayer) $currentPlayerToClear = $userHoldPlayer;
            }
        }

        if(!empty($noLongerHolds))
        {
            //將不持有的角色擁有者先暫時改為0
            $whereValues = $accessor->ClearCondition()->valuesForWhereIn($noLongerHolds);
            $whereValues->bind['UserID'] = $this->userHolder->userID;
            $accessor->ClearCondition()->executeBind('UPDATE PlayerHolder SET UserID = 0 
                                                    WHERE PlayerID IN '. $whereValues->values.
                                                    'AND UserID = :UserID',$whereValues->bind);
            foreach($noLongerHolds as $noLongerHold) PlayerPool::Instance()->Delete($noLongerHold);
        }
        
        
        if(!empty($currentPlayerToClear))
        {
            $accessor->ClearCondition()->FromTable('Users')
                ->WhereEqual('UserID',$this->userHolder->userID)->Modify(['Player' => 0]);
            UserPool::Instance()->Delete($this->userHolder->userID);    
        }
        if(!empty($changeholdPlayerIDs))
        {
            $accessor->ClearCondition()->FromTable('PlayerHolder')
            ->WhereIn('PlayerID',$changeholdPlayerIDs)
            ->Modify(['UserID' => $this->userHolder->userID,'Nickname' => null, 'SyncRate' => 0]);
            foreach($changeholdPlayerIDs as $changeholdPlayerID) PlayerPool::Instance()->Delete($changeholdPlayerID);                        
            
            $rows = $accessor->ClearCondition()->FromTable('Users')
                        ->WhereIn('Player',$changeholdPlayerIDs)->FetchAll();
            if($rows !== false)
            {    
                $accessor->ClearCondition()->FromTable('Users')
                ->WhereIn('Player',$changeholdPlayerIDs)->Modify(['Player' => 0]);
                foreach($rows as $row)UserPool::Instance()->Delete($row['UserID']);
            }            
        }
    }

}