<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class ItemException extends NormalException
{

    const UserItemStacklimitReached  = 5001;    
    const MailNotExist = 5002;
    const MailRewardsReceived  = 5003; 


}