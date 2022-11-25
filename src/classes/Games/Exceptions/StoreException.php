<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class StoreException extends NormalException {

    const Error = 8001;
    const NotEnoughCurrency = 8002;
    const Refreshed = 8003;
    const OutofStock = 8004;
    const NoRefreshCount = 8005;
    const PurchaseCancelled = 8006;
    const PurchaseIsComplete = 8007;
    const PurchaseFailure = 8008;
    const PurchaseProcessing = 8009;
    const ProductNotExist = 8010;
    const MyCardError = 8011;

}
