<?php

use Games\Exceptions\StoreException;

$lang[StoreException::Error] = 'An error occurred when use store! [cause]';
$lang[StoreException::NotEnoughCurrency] = 'not enough currencies!';
$lang[StoreException::Refreshed] = 'Store has been refreshed!';
$lang[StoreException::OutofStock] = 'Out of stock!';
$lang[StoreException::NoRefreshCount] = 'no refresh count !';
$lang[StoreException::PurchaseCancelled] = 'Purchase cancelled';
$lang[StoreException::PurchaseIsComplete] = 'Purchase is complete !';
$lang[StoreException::PurchaseFailure] = 'Payment Fail!';
$lang[StoreException::PurchaseProcessing] = 'Purchasing in progress!';
$lang[StoreException::ProductNotExist] = 'Product does not exist!';

return $lang;