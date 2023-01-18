<?php

namespace Games\Store\Holders;

use stdClass;

/*
 * Description of GoogleHolder
 * 取得儲值商品回應
 * https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.products
 */

class GooglePurchaseData extends stdClass {

    public function __construct(string $jsonstr) {
        $data = json_decode($jsonstr);

        $this->autoRenewing = $data->autoRenewing;
    }

    /** @var string  表示訂閱項目是否自動續訂 */
    public string $autoRenewing;

    /** @var int 交易的專屬訂購單。 */
    public string $orderId;

    /** @var string 產生購買交易的應用程式套件。 */
    public string $packageName;

    /** @var int 該商品的產品 ID */
    public int $productId;

    /** @var int 產品的購買時間 */
    public string $purchaseTime;

    /** @var string 訂購單的購買狀態 */
    public string $purchaseState;

    /** @var string 開發人員指定的字串 */
    public string $developerPayload;

    /** 專門用來識別指定商品和使用者成對資料的購買交易權杖 */
    public string $purchaseToken;

}
