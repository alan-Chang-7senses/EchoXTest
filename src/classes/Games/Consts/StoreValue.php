<?php

namespace Games\Consts;

/*
 * Description of StoreValue
 */

class StoreValue {
    
    // 
    const NoStoreInfoID = 0; //沒有交易物品    
    const NoTradeID = 0; //沒有交易物品

    // 商店類型()
    const None = 0;
    const Purchase = 1;
    const Counters = 2;
    // 介面類型(UIType_固定商品數量)
    const UIType_12 = 1;
    const UIType_08 = 2;
    const UIType_04 = 3;
    const UIType_00 = 4;
    const UINoItems = -1;
    const UIMaxFixItems = 12;
    //貨幣(Currency)
    //1：火星幣2：寶石3：PT幣4：火星幣賽入場券5：PT幣賽入場券6：群體賽入場券"
    const CurrencyNone = 0; //free
    const CurrencyMars = 1;
    const CurrencyGold = 2;
    //庫存(Inventory) -1:無限 0:展示用 >0:庫存數量
    const InventoryNoLimit = -1;
    const InventoryDisplay = 0;
        //交易資訊狀態, 0:閒置, 1:使用中
    const TradeIdle = 0;
    const TradeInUse = 1;

}
