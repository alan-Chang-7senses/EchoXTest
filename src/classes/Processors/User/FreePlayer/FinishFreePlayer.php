<?php
namespace Processors\User\FreePlayer;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Games\Accessors\UserAccessor;
use Games\Consts\PlayerValue;
use Games\Exceptions\UserException;
use Games\Pools\UserPool;
use Games\Users\NamingUtility;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

class FinishFreePlayer extends BaseProcessor{
    
    public function Process(): ResultData
    {
        
        $petaNumber = InputHelper::post("number");
        $nickname = InputHelper::post("nickname");
        if($petaNumber  < 1 || $petaNumber > 3)return new ResultData(ErrorCode::ParamError);
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        

        
        if(!NamingUtility::IsOnlyEnglishAndNumber($nickname))throw new UserException(UserException::UsernameNotEnglishOrNumber);        
        if(NamingUtility::ValidateLength($nickname,SetUserNicknameValue::MaxLenght)) throw new UserException(UserException::UsernameTooLong);
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $isAlreayExist = NamingUtility::IsNameAlreadyExist($nickname,EnvVar::DBMain,"Users","Nickname");
        if($isAlreayExist)throw new UserException(UserException::UsernameAlreadyExist,['username' => $nickname]);                 
        
        $row = $pdo->FromTable("UserFreePeta")
            ->WhereEqual("UserID",$userInfo->id)
            ->Fetch();
        if($row === false)throw new UserException(UserException::UserFreePlayerListEmpty,['userID' => $_SESSION[Sessions::UserID]]);
        $freePetas = json_decode($row->FreePetaInfo);
        $chosenPeta = "";
        foreach($freePetas as $peta)
        {
            if($peta->number == $petaNumber) $chosenPeta = $peta; 
        }        
        if(empty($chosenPeta))throw new UserException(UserException::UserFreePlayerListEmpty,['userID' => $_SESSION[Sessions::UserID]]);
        $pdo->ClearAll();
        $freePetaRows = $pdo->FromTable("PlayerHolder")
            ->WhereEqual("UserID",$userInfo->id)
            ->WhereLess("PlayerID",PlayerValue::freePetaMaxPlayerID) // 暫定。小於16位為免費peta編號
            ->FetchAll();
        $playerID = $userInfo->id * PlayerValue::freePetaPlayerIDMultiplier + 1; // 暫定。需常數化 
        if($freePetaRows !== false && count($freePetaRows) > 0)
        {
            //免費Peta超過免費peta的持有限制
            if(count($freePetaRows) > PlayerValue::freePetaPlayerIDMultiplier - 1)
            throw new UserException(UserException::UserFreePlayerOverLimit,['userID' => $_SESSION[Sessions::UserID]]);
            $playerID += count($freePetaRows);
        }

        $bind = 
        [
            "PlayerID" => $playerID,
            "Constitution" => $chosenPeta->Constitution,
            "Strength" => $chosenPeta->Strength,
            "Dexterity" => $chosenPeta->Dexterity,
            "Agility" => $chosenPeta->Agility,
            "Attribute" => $chosenPeta->Attribute,
            "HeadDNA" => $chosenPeta->dna->head,
            "BodyDNA" => $chosenPeta->dna->body,
            "HandDNA" => $chosenPeta->dna->hand,
            "LegDNA" => $chosenPeta->dna->leg,
            "BackDNA" => $chosenPeta->dna->back,
            "HatDNA" => $chosenPeta->dna->hat,
            "Native" => $chosenPeta->native,
            "Source" => $chosenPeta->source,
            "StrengthLevel" => $chosenPeta->StrengthLevel,
            "SkeletonType" => $chosenPeta->SkeletonType,
        ];
        //開始存檔
        $pdo->ClearAll();
        $pdo->FromTable("PlayerNFT")
            ->Add($bind,true);
            $pdo->ClearAll();
            $pdo->FromTable("PlayerLevel")
            ->Add(["PlayerID" => $playerID],true);    
            $pdo->ClearAll();
            $pdo->FromTable("PlayerHolder")
            ->Add(["PlayerID" => $playerID, "UserID" => $userInfo->id],true);
            
            foreach($chosenPeta->skills as $skillID){
                $ids[] = ["PlayerID" => $playerID, "SkillID" => $skillID->id];
            }
            $pdo->ClearAll();
        if(count($ids) > 0)$pdo->FromTable("PlayerSkill")->AddAll($ids);
        $ua = new UserAccessor();
        $ua->ModifyUserValuesByID($userInfo->id,["Nickname" => $nickname, "Player" => $playerID]);    
        UserPool::Instance()->Delete($userInfo->id);           
        $results = new ResultData(ErrorCode::Success);
        return $results;
    }
}
