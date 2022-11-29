<?php

namespace Games\Consts;

/*
 * Description of MailValues
 */

class MailValues {

    // 後端參數紀錄
    const ArgumentNone = 0;
    const ArgumentText = 1;
    const ArgumentTime = 2;
    const ArgumentAreaID = 3;
    const ArgumentAmount = 4;
    // 前端判斷取代用
    const ClientTimeStamp = 1;
    const ClientAreaID = 2;
    // 後端取代文字
    const ReplaceText = '/{test}/';
    const ReplaceAmount = '/{N}/';

}
