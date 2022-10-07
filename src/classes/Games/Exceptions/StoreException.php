<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class StoreException extends NormalException {

    const Error = 8000;
    const NotEnoughCurrency = 8001;
    const StoreRefreshed = 8002;

}
