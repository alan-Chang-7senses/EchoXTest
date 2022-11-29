<?php
namespace Processors\User\FreePlayer;

use Consts\ErrorCode;
use Consts\Sessions;
use Consts\SetUserNicknameValue;
use Games\Accessors\AccessorFactory;
use Games\Consts\DirtyWordValue;
use Games\Consts\FreePlayerValue;
use Games\Consts\PlayerValue;
use Games\Exceptions\UserException;
use Games\Users\FreePeta\FreePetaUtility;
use Games\Users\NamingUtility;
use Games\Users\UserHandler;
use Helpers\InputHelper;
use Holders\ResultData;
use PDOException;
use Processors\BaseProcessor;

/**
 * Description of SaveFreePlayer
 * 
 * @author Liu Shu Ming <mingoliu@7senses.com>
 */
class SaveFreePlayer extends BaseProcessor {

    public function Process(): ResultData {

        $nickname = InputHelper::post("nickname");

        $userID = $_SESSION[Sessions::UserID];
        $userHandler = new UserHandler($userID);
        $userInfo = $userHandler->GetInfo();
        
        $accessor = AccessorFactory::Main();


        // 判斷是否為 尚未改名的新玩家 (Nickname = Username)
        $rows = $accessor->FromTable('Users')->WhereEqual('UserID', $userInfo->id)->Fetch();
        $isNewPlayer = ($rows->Nickname == $rows->Username);


        // 玩家改名 + 寫入資料庫
        if(!NamingUtility::IsOnlyEnglishAndNumber($nickname)) {
            throw new UserException(UserException::UsernameNotEnglishOrNumber);        
        }

        if(NamingUtility::ValidateLength($nickname,SetUserNicknameValue::MaxLenght)) {
            throw new UserException(UserException::UsernameTooLong);
        }

        if(NamingUtility::HasDirtyWords($nickname,DirtyWordValue::BandWordName)) {
            throw new UserException(UserException::UsernameDirty);
        }

        try {
            $accessor->ClearCondition();
            $accessor->FromTable('Users')->WhereEqual('UserID', $userInfo->id)->Modify(["Nickname" => $nickname]);
        }
        catch(PDOException $ex) {
            $info = $ex->errorInfo;   
            if(!empty($info) && $info[0] == ErrorCode::PDODuplicate)
                throw new UserException(UserException::UsernameAlreadyExist, ['username' => $nickname]);
            else
                throw $ex;
        }


        // 尚未改名的新玩家，才能收到 3 隻免費 Peta
        if ($isNewPlayer == true) {
            $accessor->ClearCondition();
            $rows = $accessor->FromTable("UserFreePeta")->WhereEqual('UserID', $userInfo->id)->Fetch();
            $freePetas = json_decode($rows->FreePetaInfo);
    
            foreach($freePetas as $peta) {
                FreePetaUtility::AddNewFreePlayer($peta, $_SESSION[Sessions::UserID]);
            }
        }


        // 預設選角為擁有的第一隻 Peta
        $playerID = $userInfo->id * PlayerValue::freePetaPlayerIDMultiplier + FreePlayerValue::FreePlayerDefaultID;
        
        $accessor->ClearCondition();
        $accessor->FromTable('Users')->WhereEqual('UserID', $userInfo->id)->Modify(["Player" => $playerID]);


        $result = new ResultData(ErrorCode::Success);
        return $result;
    }
}