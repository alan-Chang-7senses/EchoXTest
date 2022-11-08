<?php

namespace Games\Consts;

/*
 * Description of StoreValue
 */

class StoreValue {

    const NoStoreInfoID = 0; //沒有交易物品    
    const NoTradeID = 0; //沒有交易物品

    // 商店類型
    const None = 0;
    const Counters = 1;
    const AppleIAP = 2;
    const GoogleIAB = 3;
    const MyCard = 4;

    // 介面類型(UIType_固定商品數量)
    const UIType_12 = 1;
    const UIType_08 = 2;
    const UIType_04 = 3;
    const UIType_00 = 4;
    const UIUnset = -1;
    const UIMaxFixItems = 12;
    
    //貨幣(Currency) 1：火星幣2：寶石3：PT幣4：火星幣賽入場券5：PT幣賽入場券6：群體賽入場券"
    const CurrencyFree = 0;
    const CurrencyMars = 1;
    const CurrencyDiamond = 2;
    const CurrencyPT = 3;
    const CurrencyMarsTicket = 4;
    const CurrencyPtTicket = 5;
    const CurrencyGroupTicket = 6;
    const Currencies = [
        self::CurrencyMars,
        self::CurrencyDiamond,
        self::CurrencyPT,
        self::CurrencyMarsTicket,
        self::CurrencyPtTicket,
        self::CurrencyGroupTicket,
    ];
    
    //
    const RefreshRemainEmpty = 0;
    
    //庫存(Inventory) -1:無限 0:展示用 >0:庫存數量
    const InventoryNoLimit = -1;
    const InventoryDisplay = 0;
    
    //商品資訊狀態, 0:閒置, 1:使用中
    const TradeStatusIdle = 0;
    const TradeStatusInUse = 1;
    
    //儲值狀態
    const PurchaseStatusCancel = 0;
    const PurchaseStatusProcessing = 1; //儲值處理中
    const PurchaseStatusFinish = 2; //完成
    const PurchaseQuickSDKFailure = 3; //QuickSDK 付款失敗    
    
    //付費狀態(Quick sdk)
    const PaymentSuccess = 0;
    const PaymentFailure = 1;
    
    //裝置平台
    const NoDevice = 0;
    const Android = 1;
    const iOS = 2;
    
    //儲值平台
    const PlatApple = 1;
    const PlatGoogle = 2;
    const PlatMyCard = 3;
}