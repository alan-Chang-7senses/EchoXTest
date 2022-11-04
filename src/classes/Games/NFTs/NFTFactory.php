<?php

namespace Games\NFTs;

use Accessors\CurlAccessor;
use Consts\EnvVar;
use Games\Accessors\AccessorFactory;
use Games\Consts\AbilityFactor;
use Games\Consts\NFTDNA;
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
use Games\Pools\PlayerPool;
use Games\Pools\UserPool;
use Games\Users\UserHandler;

/**
 * Description of NFTFactory
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NFTFactory {
    
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
    private function SkillPurered(PlayerDnaHolder $dna) : string|false
    {
        $spieceCodes = [];
        $spieceCodes[] = substr($dna->head,NFTDNA::DominantOffset, NFTDNA::SpeciesLength);
        $spieceCodes[] = substr($dna->body,NFTDNA::DominantOffset, NFTDNA::SpeciesLength);
        $spieceCodes[] = substr($dna->hand,NFTDNA::DominantOffset, NFTDNA::SpeciesLength);
        $spieceCodes[] = substr($dna->leg,NFTDNA::DominantOffset, NFTDNA::SpeciesLength);
        $spieceCodes[] = substr($dna->back,NFTDNA::DominantOffset, NFTDNA::SpeciesLength);
        $spieceCodes[] = substr($dna->hat,NFTDNA::DominantOffset, NFTDNA::SpeciesLength);
        $needle = $spieceCodes[0];
        foreach($spieceCodes as $code)if($code != $needle)return false;
        $row = AccessorFactory::Static()->FromTable('SkillPurebred')->WhereEqual('SpeciesCode',$needle)->Fetch();
        return empty($row->AliasCode) ? false : $row->AliasCode;
    }
    
    public function CreatePlayer($playerID, $metadata) : void{
        
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
        
        $row = AccessorFactory::Static()->FromTable('MetadataActivity')
                               ->WhereEqual('ActivityName',$metadata->properties->activity_name)
                               ->Fetch();
        $isNative = $metadata->attributes->original == NFTQueryValue::NativeValue;
        $playerNFT = [
            'PlayerID' => $playerID,
            'TokenName' => $metadata->name,
            'Constitution' => $metadata->attributes->attr_1 * AbilityFactor::NFTDivisor,
            'Strength' => $metadata->attributes->attr_2 * AbilityFactor::NFTDivisor,
            'Dexterity' => $metadata->attributes->attr_3 * AbilityFactor::NFTDivisor,
            'Agility' => $metadata->attributes->attr_4 * AbilityFactor::NFTDivisor,
            'Attribute' => NFTQueryValue::AttributesClass[$metadata->attributes->class],
            'HeadDNA' => $playerDNAHolder->head,
            'BodyDNA' => $playerDNAHolder->body,
            'HandDNA' => $playerDNAHolder->hand,
            'LegDNA' => $playerDNAHolder->leg,
            'BackDNA' => $playerDNAHolder->back,
            'HatDNA' => $playerDNAHolder->hat,

            'Native' => $isNative ? ($row->Native ?? NFTDNA::NativeDefalut) : NFTDNA::NativeNone,
            'Source' => $row->Source ?? NFTDNA::SourcePromote,
            'SkeletonType' => $row->SkeletonType ?? NFTDNA::SkeletonTypePeta,
            'StrengthLevel' => $metadata->properties->attribute_model,
            'ExternalURL' => $metadata->external_url ?? '',
            'Image' => $metadata->image ?? '',
            'AnimationURL' => $metadata->animation_url ?? ''

            // 'Achievement' => '', 還沒有成就
            // 'TradeCount' => '',
        ];

        //部位技能
        $aliasCodes = [];
        $partSkillHolder = $this->PartSkill($playerDNAHolder);
        $aliasCodes = array_merge($aliasCodes, $partSkillHolder->aliasCodes);
        //詞綴技能
        $affixSkillAliasCodes = $this->AffixSkill($partSkillHolder);
        $aliasCodes = array_merge($aliasCodes, $affixSkillAliasCodes);

        //原生種技能
        if($isNative && !empty($row))
        {
            $nativeRow = AccessorFactory::Static()->FromTable('SkillNative')
                                    ->WhereEqual('NativeCode',$row->Native)
                                    ->Fetch();
            if($nativeRow !== false) $aliasCodes[] = $nativeRow->AliasCode;                                    
        }
        //純種技能
        $purebredSkillCode = $this->SkillPurered($playerDNAHolder);
        if($purebredSkillCode !== false) $aliasCodes[] = $purebredSkillCode;
        $aliasCodes = array_values(array_filter($aliasCodes));

        
        $skillRows = AccessorFactory::Static()->selectExpr('`SkillID`')->FromTable('SkillInfo')
                                        ->WhereIn('AliasCode',$aliasCodes)->FetchStyleAssoc()
                                        ->FetchAll();
        $skillBind = [];                                        
        foreach($skillRows as $skillRow)$skillBind[] = ['PlayerID' => $playerID,'SkillID' => $skillRow['SkillID']];
        $mainAccessor = AccessorFactory::Main();
        $mainAccessor->FromTable('PlayerNFT')->Add($playerNFT);
        $mainAccessor->ClearCondition()->FromTable('PlayerSkill')->Ignore(true)->AddAll($skillBind);
        $mainAccessor->ClearCondition()->FromTable('PlayerLevel')->Add($playerLevel);
        $mainAccessor->ClearCondition()->FromTable('PlayerHolder')->Add($playerHolder);
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
        //不存在DB，但在鏈上持有
        $notExistPlayerIDs =  array_diff($playerIDs,$existPlayerIDs);
        //存在DB、鏈上持有、DB不持有
        $changeholdPlayerIDs = array_values(array_diff($existPlayerIDs,$userHoldPlayers));
        //存在DB、鏈上不持有、DB持有
        $noLongerHolds = array_values(array_diff($userHoldPlayers,$playerIDs));
        
        
        foreach($notExistPlayerIDs as $playerID){
            
            $metadata = $this->QueryMetadata($metadataURLs[$playerID]);
            
            if($metadata === false)
                continue;
            if(!in_array($metadata->properties->universe,NFTQueryValue::ContractUniverse)
               || !in_array($metadata->properties->planet,NFTQueryValue::ContractPlanet))
                continue;

            $this->CreatePlayer($playerID, $metadata);
        }

        $userInfo = (new UserHandler($this->userHolder->userID))->GetInfo();
        $currentPlayer = $userInfo->player;
        if(in_array($currentPlayer,$noLongerHolds))$currentPlayerToClear = $currentPlayer;

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
                ->WhereEqual('UserID',$this->userHolder->userID)->Modify(['Player' => $userInfo->players[0]]);
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
            $accessor->ClearCondition()->PrepareName('RefreshOtherUserPlayer');            
            if($rows !== false)
            {
                foreach($rows as $row)
                {
                    $userInfo =(new UserHandler($row['UserID']))->GetInfo();
                    $accessor->ClearCondition()->FromTable('Users')
                    ->WhereEqual('UserID',$userInfo->id)->Modify(['Player' => $userInfo->players[0]]);
                    UserPool::Instance()->Delete($userInfo->id);
                }    
            }            
        }
    }
}
