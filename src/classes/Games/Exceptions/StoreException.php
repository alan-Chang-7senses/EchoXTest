<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class StoreException extends NormalException {

    const Error = 8001;
    const NotEnoughCurrency = 8002;
    const Refreshed = 8003;
    const OutofStock = 8004;

}
