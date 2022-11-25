<?php

namespace Games\Users;

use Games\Consts\ItemValue;
use Games\Consts\PlayerValue;
use Games\Mails\MailsHandler;
use Games\Users\UserBagHandler;
use Generators\ConfigGenerator;
/**
 * Description of UserUtility
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class UserUtility {
        
    public static function IsNonUser(int $userID) : bool{
        return $userID <= PlayerValue::BotIDLimit;
    }
    
    public static function AddItems(int $userID, array $items, $cause = ItemValue::CauseDefault) : void {
        
        $userBagHandler = new UserBagHandler($userID);
        
        $mailItems = [];
        foreach ($items as $item){
            
            $addItemResult = $userBagHandler->AddItems($item, $cause);
            if($addItemResult === false ) $mailItems[] = $item;
        }
        
        if(count($mailItems) > 0){
            
            $config = ConfigGenerator::Instance();
            $mailsHandler = new MailsHandler();
            $userMailID = $mailsHandler->AddMail($userID, $config->ItemFullAddMailID, $config->ItemFullAddMailIDay);
            $mailsHandler->AddMailItems($userMailID, $mailItems);
        }
    }

    public static function AddMailItemsWithReceive(int $userID, array $mailItems, int $mailID, int $mailDay): void {
        $mailsHandler = new MailsHandler();
        $userMailID = $mailsHandler->AddMail($userID, $mailID, $mailDay, 1);
        $mailsHandler->AddMailItems($userMailID, $mailItems);
    }
    
    /**取得使用者所持NFT角色數量 */
    public static function GetUserNFTPlayerAmount(int $userID) : int
    {
        $playerInfo = (new UserHandler($userID))->GetInfo();
        $count = 0;
        foreach($playerInfo->players as $playerID)
        {
            if($playerID > PlayerValue::freePetaMaxPlayerID)
            $count++;
        }
        return $count;
    }
}
