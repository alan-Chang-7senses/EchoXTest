<?php

namespace Games\Consts;

/*
 * Description of StoreValue
 */

class StoreValue {

    // 交易基本設定
    const NoStoreInfoID = 0;
    const NoStoreGroup = -1;
    const NoTradeID = 0;
    const RefreshRemainEmpty = 0;
    // 商店類型
    const TypeNone = 0;
    const TypeCounters = 1;
    const TypeAppleIAP = 2;
    const TypeGoogleIAB = 3;
    const TypeMyCard = 4;
    // 裝置
    const DeviceNone = 0;
    const DeviceiOS = 1;
    const DeviceAndroid = 2;
    // 儲值平台
    const PlatNone = 0;
    const PlatApple = 1;
    const PlatGoogle = 2;
    const PlatMyCard = 3;
    // 介面類型(UIType_固定商品數量, 隨機數量=UIMaxFixItems - 固定商品數量)
    const UIType_12 = 1;
    const UIType_08 = 2;
    const UIType_04 = 3;
    const UIType_00 = 4;
    const UIUnset = -1;
    const UIMaxFixItems = 12;
    // 貨幣(Currency) 1：火星幣2：寶石3：PT幣4：火星幣賽入場券5：PT幣賽入場券6：群體賽入場券"
    const CurrencyFree = 0;
    const CurrencyMars = 1;
    const CurrencyDiamond = 2;
    const CurrencyPT = 3;
    const CurrencyMarsTicket = 4;
    const CurrencyPtTicket = 5;
//    const CurrencyGroupTicket = 6;
    // 免費金額
    const FreeCost = 0;
    const Currencies = [
        self::CurrencyMars,
        self::CurrencyDiamond,
        self::CurrencyPT,
        self::CurrencyMarsTicket,
        self::CurrencyPtTicket
//        self::CurrencyGroupTicket,
    ];
    //庫存(Inventory) -1:無限 0:展示用 >0:庫存數量
    const InventoryNoLimit = -1;
    const InventoryDisplay = 0;
    //商品資訊狀態, 0:閒置, 1:使用中 2:商品封存(儲值商品使用)
    const TradeStatusIdle = 0;
    const TradeStatusInUse = 1;
    const TradeStatusSeal = 2;
    //儲值流程
    const PurchaseVerifySuccess = 0;
    const PurchaseVerifyFailure = 1;
    const PurchaseVerifyRetry = 2;
    const PurchaseVerifyMyCardError = 3;
    //儲值狀態
    const PurchaseStatusCancel = 0;
    const PurchaseStatusProcessing = 1;
    const PurchaseStatusFinish = 2;
    const PurchaseStatusFailure = 3;
    const PurchaseStatusVerify = 4;
    const PurchaseStatusMyCardError = 5;
    //MyCard(他方)回應資訊
    const MyCardReturnSuccess = 1;
    const MyCardPaySuccess = 3;
    //Google(他方)回應資訊    
    const GooglePurchased = 0;
    const GoogleCanceled = 1;
    const GooglePending = 2;
    const GoogleAcknowledgeWait = 0;
    const GoogleAcknowledgeConfirm = 1;
    //固定或隨機商品
    const FixTrade = 1;
    const RandomTrade = 2;

}
