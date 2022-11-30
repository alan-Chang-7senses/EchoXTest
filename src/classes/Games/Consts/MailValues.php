<?php

namespace Games\Consts;

/*
 * Description of MailValues
 */

class MailValues {

    // 後端參數紀錄
    const ArgumentNone = 0;   // 企劃信件內取代文字為
    const ArgumentText = 1;   // {text}
    const ArgumentTime = 2;   // {time1} {time2}
    const ArgumentAreaID = 3; // {areaID}
    const ArgumentAmount = 4; // {N}
    // 前端判斷取代用
    const ClientTimeStamp = 1;
    const ClientAreaID = 2;
    // 後端取代文字
    const ReplaceText = '/{text}/';
    const ReplaceAmount = '/{N}/';
    //收取物品狀態
    const ReceiveStatusNone = 0; // 未收取
    const ReceiveStatusDone = 1; // 已收取

}
