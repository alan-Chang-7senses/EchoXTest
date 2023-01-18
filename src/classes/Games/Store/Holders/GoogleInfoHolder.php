<?php

namespace Games\Store\Holders;

use stdClass;

/*
 * Description of GoogleHolder
 * 取得儲值商品回應
 * https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.products
 */

class GoogleInfoHolder extends stdClass {

    public string $kind;

    /** @var string  產品的購買時間，以自 Epoch 紀元時間（1970 年 1 月 1 日）起的毫秒為單位。 */
    public string $purchaseTimeMillis;

    /** @var int 訂購單的購買狀態。可能的值為：0。1. 已取消 2. 待處理 */
    public int $purchaseState;
    public int $consumptionState;
    public string $developerPayload;

    /** @var string 與購買應用程式內產品相關聯的訂單 ID。 */
    public string $orderId;

    /** @var int 應用程式內產品的購買類型。 0 1 2 */
    public int $purchaseType;

    /** @var int 應用程式內產品的確認狀態。可能的值為：0。待確認 1. 已確認 */
    public int $acknowledgementState;
    public string|null $purchaseToken;
    public string|null $productId;
    public int|null $quantity;
    public string|null $obfuscatedExternalAccountId;
    public string|null $obfuscatedExternalProfileId;

    /** @var string 產品出貨時的 ISO 3166-1 alpha-2 帳單區碼。 */
    public string $regionCode;

}
