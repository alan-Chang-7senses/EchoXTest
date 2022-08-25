<?php

namespace Games\Users;

use Games\Consts\ItemValue;
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
        return $userID <= 0;
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
}
