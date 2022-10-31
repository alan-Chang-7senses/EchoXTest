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
use Games\NFTs\Holders\MetadataDNAHolder;
use Games\Players\PlayerUtility;
use Games\NFTs\Holders\PartSkillHolder;
use Games\Consts\PlayerValue;
use Games\Players\Holders\PlayerDnaHolder;
/**
 * Description of NFTFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTFactory {
    
    private NFTUserHolder $userHolder;
    
    public function __construct(int $userID) {
        
        $row = AccessorFactory::Main()->FromTable('Users')->WhereEqual('UserID', $userID);
        $this->userHolder = new NFTUserHolder($row->UserID, $row->Email, $row->Player);
    }
    
    private function QueryNFTs(string $email) : stdClass|false{
        
        $curl = new CurlAccessor(getenv(EnvVar::NFTUri).'/queries/nfts/'.$email.'?'. http_build_query(['nftContractSymbols' => NFTQueryValue::ContractSymbols]));
        $curlReturn = $curl->ExecOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: '. self::Authorization()]
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
    
    private function QueryMetadata(string $metadataURL) : string|false{
        
        $curl = new CurlAccessor($metadataURL);
        $curlReturn = $curl->ExecOption(CURLOPT_RETURNTRANSFER, true);
        $metadata = json_decode($curlReturn);
        return $metadata ?? false;
    }
    
    private function PartSkill(PlayerDnaHolder $dnaHolder) : PartSkillHolder{
        
        $holder = new PartSkillHolder();
        
        $accessor = AccessorFactory::Static();
        $accessor->PrepareName('NFTPartSkill')->FromTable('SkillPart');
        
        $row = $accessor->WhereEqual('PartCode', PlayerUtility::PartCodeByDNA($dnaHolder->head))->WhereEqual('PartType', PlayerValue::Head)->Fetch();
        if($row !== false) $holder = NFTUtility::HoldPartSkill([$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], $row->SkillAffixID, $holder);
        
        $row = $accessor->ClearCondition()->WhereEqual('PartCode', PlayerUtility::PartCodeByDNA($dnaHolder->body))->WhereEqual('PartType', PlayerValue::Body)->Fetch();
        if($row !== false) $holder = NFTUtility::HoldPartSkill([$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], $row->SkillAffixID, $holder);
        
        $row = $accessor->ClearCondition()->WhereEqual('PartCode', PlayerUtility::PartCodeByDNA($dnaHolder->hand))->WhereEqual('PartType', PlayerValue::Hand)->Fetch();
        if($row !== false) $holder = NFTUtility::HoldPartSkill([$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], $row->SkillAffixID, $holder);
        
        $row = $accessor->ClearCondition()->WhereEqual('PartCode', PlayerUtility::PartCodeByDNA($dnaHolder->leg))->WhereEqual('PartType', PlayerValue::Leg)->Fetch();
        if($row !== false) $holder = NFTUtility::HoldPartSkill([$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], $row->SkillAffixID, $holder);
        
        $row = $accessor->ClearCondition()->WhereEqual('PartCode', PlayerUtility::PartCodeByDNA($dnaHolder->back))->WhereEqual('PartType', PlayerValue::Back)->Fetch();
        if($row !== false) $holder = NFTUtility::HoldPartSkill([$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], $row->SkillAffixID, $holder);
        
        $row = $accessor->ClearCondition()->WhereEqual('PartCode', PlayerUtility::PartCodeByDNA($dnaHolder->hat))->WhereEqual('PartType', PlayerValue::Hat)->Fetch();
        if($row !== false) $holder = NFTUtility::HoldPartSkill([$row->AliasCode1, $row->AliasCode2, $row->AliasCode3], $row->SkillAffixID, $holder);
        
        return $holder;
    }
    
    private function AffixSkill(PartSkillHolder $holder) : array{
        
        $affixID = $holder->SortFirstAffixID();
        
        $accessor = AccessorFactory::Static();
        $row = $accessor->PrepareName('NFTAffixSkill')->FromTable('SkillAffixAlias')->WhereEqual('SkillAffixID', $affixID)->Fetch();
        if($row === false) return '';
        
        $level = $holder->totalSkillffixID[$affixID];
        $aliasCodes[] = $row->{'Level'.$level};
        
        if($level == 3){
            
            $affixID = $holder->NextAffixID();
            $row = $accessor->ClearCondition()->WhereEqual('SkillAffixID', $affixID)->Fetch();
            if($row !== false && $holder->totalSkillffixID[$affixID] == 3) $aliasCodes[] = $row->Level3;
        }
        
        return $aliasCodes;
    }
    
    private function CreatePlayer($playerID, $metadata) : void{
        
        $playerHolder = [
            'PlayerID' => $playerID,
            'UserID' => $this->userHolder->userID,
        ];
        
        $playerLevel = ['PlayerID' => $playerID];
        
        $metadataDNAHolder = new MetadataDNAHolder($metadata->properties->dna->dPart, $metadata->properties->dna->rPart1, $metadata->properties->dna->rPart2);
        $playerDNAHolder = new PlayerDnaHolder();
        $playerDNAHolder->head = NFTUtility::MetadataDNAToPartDNA($metadataDNAHolder, NFTQueryValue::DNAPartHeadOffset, NFTQueryValue::DNAPartHeadLength);
        $playerDNAHolder->body = NFTUtility::MetadataDNAToPartDNA($metadataDNAHolder, NFTQueryValue::DNAPartBodyOffset, NFTQueryValue::DNAPartBodyLength);
        $playerDNAHolder->hand = NFTUtility::MetadataDNAToPartDNA($metadataDNAHolder, NFTQueryValue::DNAPartHandOffset, NFTQueryValue::DNAPartHandLength);
        $playerDNAHolder->leg = NFTUtility::MetadataDNAToPartDNA($metadataDNAHolder, NFTQueryValue::DNAPartLegOffset, NFTQueryValue::DNAPartLegLength);
        $playerDNAHolder->back = NFTUtility::MetadataDNAToPartDNA($metadataDNAHolder, NFTQueryValue::DNAPartBackOffset, NFTQueryValue::DNAPartBackLength);
        $playerDNAHolder->hat = NFTUtility::MetadataDNAToPartDNA($metadataDNAHolder, NFTQueryValue::DNAPartHatOffset, NFTQueryValue::DNAPartHatLength);
        
        $playerNFT = [
            'PlayerID' => $playerID,
            'TokenName' => $metadata->name,
            'Constitution' => $metadata->attributes->attr_1,
            'Strength' => $metadata->attributes->attr_2,
            'Dexterity' => $metadata->attributes->attr_3,
            'Agility' => $metadata->attributes->attr_4,
            'Attribute' => NFTQueryValue::AttributesClass[$metadata->attributes->class],
            'HeadDNA' => $playerDNAHolder->hand,
            'BodyDNA' => $playerDNAHolder->body,
            'HandDNA' => $playerDNAHolder->hand,
            'LegDNA' => $playerDNAHolder->leg,
            'BackDNA' => $playerDNAHolder->back,
            'HatDNA' => $playerDNAHolder->hat,
            
            'StrengthLevel' => $metadata->properties->attribute_model,
        ];
        
        $aliasCodes = [];
        $partSkillHolder = $this->PartSkill($playerDNAHolder);
        $aliasCodes = array_merge($aliasCodes, $partSkillHolder->aliasCodes);
        
        $affixSkillAliasCodes = $this->AffixSkill($partSkillHolder);
        $aliasCodes = array_merge($aliasCodes, $affixSkillAliasCodes);
        
    }
    
    public function RefreshPlayers() : void{
        
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
        
        $rowsExistPlayer = $accessor->FromTable('PlayerHolder')->WhereIn('PlayerID', $playerIDs)->FetchStyleAssoc()->FetchAll();
        $existPlayerIDs = array_column($rowsExistPlayer, 'PlayerID');
        $notExistPlayerIDs = array_column($playerIDs, $existPlayerIDs);
        
        foreach($notExistPlayerIDs as $playerIDs){
            
            $metadata = $this->QueryMetadata($metadataURLs[$playerID]);
            
            if($metadata === false)
                continue;
            
            $this->CreatePlayer($playerID, $metadata);
        }
    }
}
