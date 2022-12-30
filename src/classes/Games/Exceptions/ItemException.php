<?php

namespace Games\Exceptions;

use Exceptions\NormalException;

class ItemException extends NormalException
{
    const ItemNotEnough = 5001;
    const ItemNotExists = 5002;
    const UserNotItemOwner = 5003;
    const UseItemError = 5004;
    const UseRewardIDError = 5005;
    const UserItemStacklimitReached = 5006;
    const MailNotExist = 5007;
    const MailRewardsReceived = 5008;
    const AddItemError = 5009;
    const DecItemError = 5010;
    const DeleteMailAfterOpenMail = 5011;
    const DeleteMailAfterReceiveReward = 5012;
}