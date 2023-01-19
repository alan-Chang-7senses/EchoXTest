<?php

namespace Games\Store\Holders;

use Games\Exceptions\StoreException;
use Games\Store\GoogleUtility;
use stdClass;
use function GuzzleHttp\json_decode;

/*
 * Description of GoogleHolder
 * 取得儲值商品回應
 * https://developers.google.com/android-publisher/api-ref/rest/v3/purchases.products
 */

class GooglePurchaseData extends stdClass {

    public function __construct(string $metatstr, string $receiptStr) {
        $data = json_decode($receiptStr);
        $payLoad = json_decode($data->Payload);
        if (GoogleUtility::VerifySignature($payLoad->json, $payLoad->signature) == false) {
            throw new StoreException(StoreException::Error, ['[cause]' => 'signature error']);
        }
        $this->transactionID = $data->TransactionID;
        $jsonData = json_decode($payLoad->json);
        $this->orderId = $jsonData->orderId;
        $this->packageName = $jsonData->packageName;
        $this->productId = $jsonData->productId;
        $this->purchaseTime = $jsonData->purchaseTime;
        $this->purchaseState = $jsonData->purchaseState;
        $this->purchaseToken = $jsonData->purchaseToken;

        $metadata = json_decode($metatstr);
        $this->localizedPriceString = $metadata->localizedPriceString;
        $this->localizedTitle = $metadata->localizedTitle;
        $this->localizedDescription = $metadata->localizedDescription;
        $this->isoCurrencyCode = $metadata->isoCurrencyCode;
        $this->localizedPrice = $metadata->localizedPrice;
    }

    /** @var string 當地價格字串 */
    public string $localizedPriceString;

    /** @var string 當地標題 */
    public string $localizedTitle;

    /** @var string 當地描述 */
    public string $localizedDescription;

    /** @var string 當地幣別 */
    public string $isoCurrencyCode;

    /** @var int 當地價格 */
    public float $localizedPrice;

    /** @var int 交易序號 */
    public string $transactionID;

    /** @var int 交易的專屬訂購單。 */
    public string $orderId;

    /** @var string 產生購買交易的應用程式套件。 */
    public string $packageName;

    /** @var int 該商品的產品 ID */
    public string $productId;

    /** @var int 產品的購買時間 */
    public string $purchaseTime;

    /** @var string 訂購單的購買狀態 */
    public string $purchaseState;

    /** 專門用來識別指定商品和使用者成對資料的購買交易權杖 */
    public string $purchaseToken;

}
