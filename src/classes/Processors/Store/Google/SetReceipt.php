<?php

namespace Processors\Store\Google;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Sessions;
use Games\Consts\StoreValue;
use Games\Store\GoogleUtility;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\Store\Purchase\BaseRefresh;
use stdClass;

/*
 * Description of Google Cancel
 * Google 儲值設定收據並更新
 */

class SetReceipt extends BaseRefresh {

    protected int $nowPlat = StoreValue::PlatGoogle;

    public function PurchaseVerify(stdClass $purchaseOrders): stdClass {        
        
        return GoogleUtility::Verify($purchaseOrders->ProductID, $purchaseOrders->Receipt);
    }

    public function Process(): ResultData {
        $this->userID = $_SESSION[Sessions::UserID];        
        $this->orderID = InputHelper::post('orderID');                
        $receipt = InputHelper::post('Receipt');
        
        //$data = new GooglePurchaseData($receipt);
        
                                       
        $accessor = new PDOAccessor(EnvVar::DBMain);        
        $row = $accessor->FromTable('StorePurchaseOrders')->
                        WhereEqual("OrderID", $this->orderID)->WhereEqual("UserID", $this->userID)->
                        WhereEqual("Plat", $this->nowPlat)->fetch();

                
        $info = GoogleUtility::GetInfo('com.oteygaming.peta_01_001', 'jhmjhkommjggcednagcpidkg.AO-J1Ox3NkGIWo65OsygS81tIZ83-u2nWK9RUNrlEPzdhq4SXG6_DO5Y4lW8ZcSSHwN8lcJD5NnBThn7md_AMAqsnJxOMMsF-g');
 
//        GoogleUtility::GetInfo($row->ProductID, 'sss'); 
        
                      
        //$info = GoogleUtility::GetInfo($row->ProductID, $token);
        //$this->UpdateReceipt($receipt);                
        $result = $this->HandleRefresh();      
        

        return $result;
    }
}
