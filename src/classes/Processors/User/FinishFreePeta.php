<?php
namespace Processors\User;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Error;
use Games\Accessors\UserAccessor;
use Games\Exceptions\UserException;
use Games\Users\NamingUtility;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;
use Processors\EliteTest\UserInfo;

class FinishFreePeta extends BaseProcessor{
    
    public function Process(): ResultData
    {
        // 傳入暱稱字串
        // 不回傳參數
        
        // $bandedWords = NamingUtility::GetBandedWordEnglish();
        // if(NamingUtility::HasBandedWord($nickname,$bandedWords))throw new UserException(UserException::UsernameDirty,['username' => $nickname]);
        $petaNumber = InputHelper::post("number");
        $nickname = InputHelper::post("nickname");
        if($petaNumber  < 1 || $petaNumber > 3)return new ResultData(ErrorCode::ParamError);
        
        if(!NamingUtility::IsOnlyEnglishAndNumber($nickname))throw new UserException(UserException::UsernameNotEnglishOrNumber);        
        if(NamingUtility::ValidateLength($nickname,SetUserNicknameValue::MaxLenght)) throw new UserException(UserException::UsernameTooLong);
        $pdo = new PDOAccessor(EnvVar::DBMain);
        $userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $userInfo = $userHandler->GetInfo();
        

        $isAlreayExist = NamingUtility::IsNameAlreadyExist($nickname,EnvVar::DBMain,"Users","Nickname");
        if($isAlreayExist)throw new UserException(UserException::UsernameAlreadyExist,['username' => $nickname]);                 
        if(!empty($userInfo->nickname))throw new UserException(UserException::AlreadyHadFreePeta);
        
        $row = $pdo->FromTable("UserFreePeta")
            ->WhereEqual("UserID",$userInfo->id)
            ->Fetch();
        if($row === false)return new ResultData(ErrorCode::Unknown);
        $freePetas = json_decode($row->FreePetaInfo);
        $chosenPeta = "";
        foreach($freePetas as $peta)
        {
            if($peta->number == $petaNumber) $chosenPeta = $peta; 
        }        
        if(empty($chosenPeta))return new ResultData(ErrorCode::Unknown);
        $pdo->ClearAll();
        $freePetaRows = $pdo->FromTable("PlayerHolder")
            ->WhereEqual("UserID",$userInfo->id)
            ->WhereLess("PlayerID",999999999999999) // 暫定。小於16位為免費peta編號
            ->FetchAll();
        $playerID = $userInfo->id * 100 + 1; // 暫定。需常數化 
        if($freePetaRows !== false && count($freePetaRows) > 0)
        $playerID += count($freePetaRows);

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
        $userHandler->SaveData(["Nickname" => $nickname]);
        $pdo->ClearAll();
        $pdo->FromTable("PlayerNFT")
            ->Add($bind,true);
        $pdo->ClearAll();
        $pdo->FromTable("PlayerHolder")
            ->Add(["PlayerID" => $playerID, "UserID" => $userInfo->id],true);
                      
        $results = new ResultData(ErrorCode::Success);
        return $results;
    }
}
